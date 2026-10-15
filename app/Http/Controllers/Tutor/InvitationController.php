<?php

namespace App\Http\Controllers\Tutor;

use App\Http\Controllers\Controller;
use App\Models\Invitation;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class InvitationController extends Controller
{
    /**
     * Display a listing of received invitations
     */
    public function index()
    {
        $invitations = Invitation::where('tutor_id', Auth::id())
            ->with(['parent', 'tuitionOffer'])
            ->orderBy('created_at', 'desc')
            ->paginate(15);

        return view('tutor.invitations.index', compact('invitations'));
    }

    /**
     * Show the details of an invitation
     */
    public function show(Invitation $invitation)
    {
        // Ensure tutor owns this invitation
        if ($invitation->tutor_id !== Auth::id()) {
            abort(403, 'Unauthorized action.');
        }

        $invitation->load(['parent', 'tuitionOffer']);

        return view('tutor.invitations.show', compact('invitation'));
    }

    /**
     * Accept an invitation
     */
    public function accept(Request $request, Invitation $invitation)
    {
        // Ensure tutor owns this invitation
        if ($invitation->tutor_id !== Auth::id()) {
            abort(403, 'Unauthorized action.');
        }

        // Can only accept pending invitations
        if ($invitation->status !== 'pending') {
            return back()->with('error', 'This invitation cannot be accepted.');
        }

        $request->validate([
            'response_message' => 'nullable|string|max:500',
        ]);

        // Mark as accepted
        $invitation->update([
            'status' => 'accepted',
            'accepted_at' => now(),
            'tutor_response_message' => $request->response_message,
        ]);

        // Automatically send to admin queue
        $invitation->sendToAdmin();

        return back()->with('success', 'Invitation accepted! The admin has been notified and will contact you soon.');
    }

    /**
     * Reject an invitation
     */
    public function reject(Request $request, Invitation $invitation)
    {
        // Ensure tutor owns this invitation
        if ($invitation->tutor_id !== Auth::id()) {
            abort(403, 'Unauthorized action.');
        }

        // Can only reject pending invitations
        if ($invitation->status !== 'pending') {
            return back()->with('error', 'This invitation cannot be rejected.');
        }

        $request->validate([
            'response_message' => 'nullable|string|max:500',
        ]);

        // Mark as rejected
        $invitation->update([
            'status' => 'rejected',
            'rejected_at' => now(),
            'tutor_response_message' => $request->response_message,
        ]);

        return back()->with('success', 'Invitation rejected.');
    }
}
