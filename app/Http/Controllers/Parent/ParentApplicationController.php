<?php

namespace App\Http\Controllers\Parent;

use App\Http\Controllers\Controller;
use App\Models\Application;
use App\Models\TuitionOffer;
use Illuminate\Http\Request;

class ParentApplicationController extends Controller
{
    public function index(Request $request)
    {
        $query = Application::with(['tutor.tutorProfile', 'tuitionOffer'])
            ->whereHas('tuitionOffer', function($q) {
                $q->where('user_id', auth()->id());
            });

        // Filter by status
        if ($request->filled('status')) {
            $query->where('status', $request->status);
        }

        // Filter by offer
        if ($request->filled('offer')) {
            $query->where('tuition_offer_id', $request->offer);
        }

        $applications = $query->orderBy('applied_at', 'desc')->paginate(15);
        
        // Get parent's offers for filter
        $offers = TuitionOffer::where('user_id', auth()->id())
            ->orderBy('title')
            ->get();

        return view('parent.applications.index', compact('applications', 'offers'));
    }

    public function show(Application $application)
    {
        // Ensure parent owns the tuition offer
        if ($application->tuitionOffer->user_id !== auth()->id()) {
            abort(403, 'Unauthorized access.');
        }

        $application->load(['tutor.tutorProfile', 'tuitionOffer']);

        return view('parent.applications.show', compact('application'));
    }

    public function accept(Application $application)
    {
        // Ensure parent owns the tuition offer
        if ($application->tuitionOffer->user_id !== auth()->id()) {
            abort(403, 'Unauthorized access.');
        }

        // Check if application is still pending
        if ($application->status !== 'pending') {
            return back()->with('error', 'This application has already been processed.');
        }

        // Accepting hands the match to the admin, who arranges the commission and the contact details.
        $application->update(['status' => 'accepted', 'sent_to_admin_at' => now()]);

        // Optionally mark offer as filled
        // $application->tuitionOffer->update(['status' => 'filled']);

        return back()->with('success', 'Application accepted. TutorHub will contact the tutor and arrange the next step.');
    }

    public function reject(Application $application)
    {
        // Ensure parent owns the tuition offer
        if ($application->tuitionOffer->user_id !== auth()->id()) {
            abort(403, 'Unauthorized access.');
        }

        // Check if application is still pending
        if ($application->status !== 'pending') {
            return back()->with('error', 'This application has already been processed.');
        }

        $application->update(['status' => 'rejected']);

        return back()->with('success', 'Application rejected.');
    }
}
