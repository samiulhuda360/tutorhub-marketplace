<?php

namespace App\Http\Controllers\Tutor;

use App\Http\Controllers\Controller;
use App\Models\TuitionOffer;
use App\Models\Application;
use App\Matching\TutorMatcher;

class DashboardController extends Controller
{
    public function index(TutorMatcher $matcher)
    {
        $user = auth()->user();
        
        // Get tutor profile
        $profile = $user->tutorProfile;
        
        // Get stats
        $stats = [
            'total_applications' => Application::where('tutor_id', $user->id)->count(),
            'pending_applications' => Application::where('tutor_id', $user->id)->pending()->count(),
            'accepted_applications' => Application::where('tutor_id', $user->id)->accepted()->count(),
            'available_offers' => TuitionOffer::open()->count(),
        ];
        
        // Recent tuition offers (limit 5)
        $recentOffers = TuitionOffer::with(['category', 'area'])
            ->open()
            ->recent()
            ->limit(5)
            ->get();
        
        // My recent applications (limit 5)
        $myApplications = Application::with(['tuitionOffer.category', 'tuitionOffer.area'])
            ->where('tutor_id', $user->id)
            ->orderBy('applied_at', 'desc')
            ->limit(5)
            ->get();
        
        // Open offers ranked for this tutor (subjects, class, area, budget).
        $forYou = $matcher->forTutor($user, 5);

        return view('tutor.dashboard', compact('profile', 'stats', 'recentOffers', 'myApplications', 'forYou'));
    }
}