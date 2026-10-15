<?php

namespace App\Http\Controllers\Parent;

use App\Http\Controllers\Controller;
use App\Models\TuitionOffer;
use App\Models\Application;

class DashboardController extends Controller
{
    public function index()
    {
        $user = auth()->user();
        
        // Get stats
        $stats = [
            'total_offers' => TuitionOffer::where('user_id', $user->id)->count(),
            'open_offers' => TuitionOffer::where('user_id', $user->id)->where('status', 'open')->count(),
            'filled_offers' => TuitionOffer::where('user_id', $user->id)->where('status', 'filled')->count(),
            'total_applications' => Application::whereHas('tuitionOffer', function($q) use ($user) {
                $q->where('user_id', $user->id);
            })->count(),
        ];
        
        // My tuition offers (limit 5)
        $myOffers = TuitionOffer::with(['category', 'area'])
            ->where('user_id', $user->id)
            ->orderBy('posted_at', 'desc')
            ->limit(5)
            ->get();
        
        // Recent applications to my offers (limit 5)
        $recentApplications = Application::with(['tutor.tutorProfile', 'tuitionOffer'])
            ->whereHas('tuitionOffer', function($q) use ($user) {
                $q->where('user_id', $user->id);
            })
            ->orderBy('applied_at', 'desc')
            ->limit(5)
            ->get();
        
        return view('parent.dashboard', compact('stats', 'myOffers', 'recentApplications'));
    }
}