<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Matching\TutorMatcher;
use App\Models\Application;
use App\Models\Commission;
use App\Models\Invitation;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

/**
 * Accepted applications and accepted invitations, in one list for the admin:
 * with admin -> contacted -> (negotiating) -> commission paid -> contact shared.
 */
class MatchController extends Controller
{
    private const TYPES = ['application', 'invitation'];

    public function index(Request $request)
    {
        $status = $request->get('status', 'all');
        $search = trim((string) $request->get('search'));

        // Grouped, so the status and search filters below apply to every row.
        $applicationsQuery = Application::with(['tutor.tutorProfile', 'tuitionOffer.user'])
            ->where(fn ($q) => $q->where('status', 'accepted')->orWhereNotNull('sent_to_admin_at'));

        $invitationsQuery = Invitation::with(['tutor.tutorProfile', 'parent', 'tuitionOffer'])
            ->whereIn('status', ['with_admin', 'contacted', 'negotiating', 'commission_paid', 'completed']);

        if ($status !== 'all') {
            if ($status === 'pending_admin') {
                $applicationsQuery->whereNull('admin_contacted_at')->whereNull('commission_id');
                $invitationsQuery->where('status', 'with_admin');
            } elseif ($status === 'contacted') {
                $applicationsQuery->whereNotNull('admin_contacted_at')->whereNull('commission_id');
                $invitationsQuery->where('status', 'contacted');
            } elseif ($status === 'negotiating') {
                $applicationsQuery->whereRaw('1 = 0'); // only invitations negotiate
                $invitationsQuery->where('status', 'negotiating');
            } elseif ($status === 'commission_paid') {
                $applicationsQuery->whereNotNull('commission_id')->whereNull('parent_contact_shared_at');
                $invitationsQuery->where('status', 'commission_paid');
            } elseif ($status === 'completed') {
                $applicationsQuery->whereNotNull('parent_contact_shared_at');
                $invitationsQuery->where('status', 'completed');
            }
        }

        if ($search !== '') {
            $applicationsQuery->where(fn ($q) => $q
                ->whereHas('tutor', fn ($t) => $t->where('name', 'like', "%{$search}%"))
                ->orWhereHas('tuitionOffer', fn ($o) => $o->where('title', 'like', "%{$search}%")));
            $invitationsQuery->where(fn ($q) => $q
                ->whereHas('tutor', fn ($t) => $t->where('name', 'like', "%{$search}%"))
                ->orWhereHas('parent', fn ($p) => $p->where('name', 'like', "%{$search}%")));
        }

        $applications = $applicationsQuery->get()->map(fn ($app) => [
            'type' => 'application',
            'id' => $app->id,
            'tutor' => $app->tutor,
            'parent' => $app->tuitionOffer->user ?? null,
            'offer' => $app->tuitionOffer,
            'status' => $this->getUnifiedStatus($app),
            'created_at' => $app->created_at,
            'sent_to_admin_at' => $app->sent_to_admin_at ?? $app->updated_at,
            'admin_contacted_at' => $app->admin_contacted_at,
            'parent_contact_shared_at' => $app->parent_contact_shared_at,
            'commission_id' => $app->commission_id,
            'admin_notes' => $app->admin_notes,
        ]);

        $invitations = $invitationsQuery->get()->map(fn ($inv) => [
            'type' => 'invitation',
            'id' => $inv->id,
            'tutor' => $inv->tutor,
            'parent' => $inv->parent,
            'offer' => $inv->tuitionOffer,
            'status' => $inv->status,
            'created_at' => $inv->created_at,
            'sent_to_admin_at' => $inv->sent_to_admin_at ?? $inv->updated_at,
            'message' => $inv->message,
            'tutor_response_message' => $inv->tutor_response_message,
        ]);

        $matches = $applications->concat($invitations)->sortByDesc('sent_to_admin_at');

        $counts = [
            'all' => $matches->count(),
            'pending_admin' => $matches->where('status', 'with_admin')->count(),
            'contacted' => $matches->where('status', 'contacted')->count(),
            'negotiating' => $matches->where('status', 'negotiating')->count(),
            'commission_paid' => $matches->where('status', 'commission_paid')->count(),
            'completed' => $matches->where('status', 'completed')->count(),
        ];

        return view('admin.matches.index', compact('matches', 'status', 'counts'));
    }

    public function show(string $type, int $id, TutorMatcher $matcher)
    {
        [$match, $parent] = $this->find($type, $id);
        $commission = Commission::where('match_type', $type)->where('match_id', $id)->first();

        // How well this tutor fits this offer, so the admin can spot weak matches before charging.
        $quality = ($match->tuitionOffer && $match->tutor?->tutorProfile)
            ? $matcher->score($match->tuitionOffer, $match->tutor->tutorProfile, 0.5)
            : null;

        return view('admin.matches.show', compact('match', 'type', 'parent', 'commission', 'quality'));
    }

    public function markAsContacted(string $type, int $id)
    {
        [$match] = $this->find($type, $id);
        $type === 'application'
            ? $match->update(['admin_contacted_at' => now()])
            : $match->update(['status' => 'contacted']);

        return redirect()->back()->with('success', 'Match marked as contacted.');
    }

    public function markAsNegotiating(string $type, int $id)
    {
        if ($type !== 'invitation') {
            return redirect()->back()->with('error', 'Only invitations can be marked as negotiating.');
        }
        [$match] = $this->find($type, $id);
        $match->update(['status' => 'negotiating']);

        return redirect()->back()->with('success', 'Match marked as negotiating.');
    }

    public function recordCommission(Request $request, string $type, int $id)
    {
        $validated = $request->validate([
            'amount' => 'required|numeric|min:0|max:1000000',
            'payment_method' => 'required|in:cash,bkash,nagad,bank_transfer,other',
            'receipt_number' => 'nullable|string|max:255',
            'admin_notes' => 'nullable|string|max:2000',
        ]);

        [$match] = $this->find($type, $id);

        // One commission per match: a second click must not charge the tutor twice.
        if (Commission::where('match_type', $type)->where('match_id', $id)->exists()) {
            return redirect()->back()->with('error', 'A commission is already recorded for this match.');
        }

        DB::transaction(function () use ($type, $id, $match, $validated) {
            $commission = Commission::create([
                'match_type' => $type,
                'match_id' => $id,
                'tutor_id' => $match->tutor_id,
                'parent_id' => $type === 'application' ? $match->tuitionOffer->user_id : $match->parent_id,
                'amount' => $validated['amount'],
                'payment_method' => $validated['payment_method'],
                'receipt_number' => $validated['receipt_number'] ?? null,
                'paid_at' => now(),
                'admin_notes' => $validated['admin_notes'] ?? null,
            ]);

            $type === 'application'
                ? $match->update(['commission_id' => $commission->id])
                : $match->update(['status' => 'commission_paid']);
        });

        return redirect()->back()->with('success', 'Commission recorded.');
    }

    public function shareContactDetails(string $type, int $id)
    {
        [$match] = $this->find($type, $id);

        if ($type === 'application') {
            if (! $match->commission_id) {
                return redirect()->back()->with('error', 'Record the commission before sharing contact details.');
            }
            $match->update(['parent_contact_shared_at' => now()]);
        } else {
            if ($match->status !== 'commission_paid') {
                return redirect()->back()->with('error', 'Record the commission before sharing contact details.');
            }
            $match->update(['status' => 'completed']);
        }

        return redirect()->back()->with('success', 'Contact details shared with the tutor.');
    }

    public function updateNotes(Request $request, string $type, int $id)
    {
        $validated = $request->validate(['admin_notes' => 'required|string|max:2000']);
        if ($type !== 'application') {
            return redirect()->back()->with('error', 'Notes are only kept on applications.');
        }
        [$match] = $this->find($type, $id);
        $match->update(['admin_notes' => $validated['admin_notes']]);

        return redirect()->back()->with('success', 'Notes saved.');
    }

    /** @return array{0: Application|Invitation, 1: \App\Models\User|null} */
    private function find(string $type, int $id): array
    {
        abort_unless(in_array($type, self::TYPES, true), 404);

        if ($type === 'application') {
            $match = Application::with(['tutor.tutorProfile', 'tuitionOffer.user', 'tuitionOffer.area'])->findOrFail($id);

            return [$match, $match->tuitionOffer?->user];
        }

        $match = Invitation::with(['tutor.tutorProfile', 'parent', 'tuitionOffer.area'])->findOrFail($id);

        return [$match, $match->parent];
    }

    private function getUnifiedStatus(Application $application): string
    {
        return match (true) {
            (bool) $application->parent_contact_shared_at => 'completed',
            (bool) $application->commission_id => 'commission_paid',
            (bool) $application->admin_contacted_at => 'contacted',
            default => 'with_admin',
        };
    }
}
