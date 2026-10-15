<?php

namespace App\Http\Controllers\Tutor;

use App\Http\Controllers\Controller;
use App\Models\Application;
use Illuminate\Http\Request;

class TutorApplicationController extends Controller
{
    public function index(Request $request)
    {
        $query = Application::with(['tuitionOffer.category', 'tuitionOffer.area'])
            ->where('tutor_id', auth()->id());

        // Filter by status
        if ($request->filled('status')) {
            $query->where('status', $request->status);
        }

        $applications = $query->orderBy('applied_at', 'desc')->paginate(15);

        return view('tutor.applications.index', compact('applications'));
    }

    public function show(Application $application)
    {
        // Ensure tutor can only see their own applications
        if ($application->tutor_id !== auth()->id()) {
            abort(403, 'Unauthorized access.');
        }

        $application->load(['tuitionOffer.category', 'tuitionOffer.area', 'tuitionOffer.user']);

        return view('tutor.applications.show', compact('application'));
    }
}
