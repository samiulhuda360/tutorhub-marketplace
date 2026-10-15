<?php

namespace App\Http\Controllers\Parent;

use App\Http\Controllers\Controller;
use App\Models\Invitation;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class InvitationController extends Controller
{
    /**
     * Display a listing of sent invitations
     */
    public function index()
    {
        $invitations = Invitation::where('parent_id', Auth::id())
            ->with(['tutor.tutorProfile', 'tuitionOffer'])
            ->orderBy('created_at', 'desc')
            ->paginate(15);

        return view('parent.invitations.index', compact('invitations'));
    }

    /**
     * Store a newly created invitation
     */
    public function store(Request $request)
    {
        $request->validate([
            'tutor_id' => 'required|exists:users,id',
            'tuition_offer_id' => 'nullable|exists:tuition_offers,id',
            'message' => 'required|string|min:10|max:1000',
        ]);

        // Check if tutor exists and has tutor role
        $tutor = User::findOrFail($request->tutor_id);
        if ($tutor->role !== 'tutor') {
            return back()->with('error', 'Invalid tutor selected.');
        }

        // Check if invitation already exists and is pending
        $existingInvitation = Invitation::where('parent_id', Auth::id())
            ->where('tutor_id', $request->tutor_id)
            ->whereIn('status', ['pending', 'accepted', 'with_admin'])
            ->first();

        if ($existingInvitation) {
            return back()->with('error', 'You have already sent an invitation to this tutor.');
        }

        // Create invitation
        $invitation = Invitation::create([
            'parent_id' => Auth::id(),
            'tutor_id' => $request->tutor_id,
            'tuition_offer_id' => $request->tuition_offer_id,
            'message' => $request->message,
            'status' => 'pending',
        ]);

        return back()->with('success', 'Invitation sent successfully! The tutor will be notified.');
    }

    /**
     * Show the details of an invitation
     */
    public function show(Invitation $invitation)
    {
        // Ensure parent owns this invitation
        if ($invitation->parent_id !== Auth::id()) {
            abort(403, 'Unauthorized action.');
        }

        $invitation->load(['tutor.tutorProfile', 'tuitionOffer']);

        return view('parent.invitations.show', compact('invitation'));
    }

    /**
     * Cancel a pending invitation
     */
    public function destroy(Invitation $invitation)
    {
        // Ensure parent owns this invitation
        if ($invitation->parent_id !== Auth::id()) {
            abort(403, 'Unauthorized action.');
        }

        // Can only cancel pending invitations
        if ($invitation->status !== 'pending') {
            return back()->with('error', 'Cannot cancel this invitation.');
        }

        $invitation->delete();

        return redirect()->route('parent.invitations.index')
            ->with('success', 'Invitation cancelled successfully.');
    }
}
