<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\User;
use App\Models\TuitionOffer;
use App\Models\Application;
use App\Models\Invitation;
use App\Models\Commission;
use App\Models\Subject;
use App\Models\Level;
use App\Models\Area;
use App\Trust\ProfileReview;
use Illuminate\Support\Facades\DB;

class DashboardController extends Controller
{
  public function index(ProfileReview $review)
  {
    // Get statistics
    $stats = [
      'total_tutors' => User::where('role', 'tutor')->count(),
      'total_parents' => User::where('role', 'parent')->count(),
      'total_offers' => TuitionOffer::count(),
      'open_offers' => TuitionOffer::where('status', 'open')->count(),
      'total_applications' => Application::count(),
      'pending_applications' => Application::where('status', 'pending')->count(),
      'total_subjects' => Subject::count(),
      'total_levels' => Level::count(),
      'total_areas' => Area::count(),

      // Match Management Stats
      'pending_matches' => Application::whereNotNull('sent_to_admin_at')
          ->whereNull('admin_contacted_at')
          ->count() +
        Invitation::where('status', 'with_admin')->count(),
      'total_commissions' => Commission::sum('amount') ?? 0,
      'pending_commissions' => Application::whereNotNull('admin_contacted_at')
          ->whereNull('commission_id')
          ->count() +
        Invitation::whereIn('status', ['contacted', 'negotiating'])->count(),

      // AI features and trust signals
      'flagged_profiles' => $review->flagged()->count(),
      'ai_calls_week' => DB::table('ai_calls')->where('created_at', '>=', now()->subDays(7))->count(),
    ];

    // Get recent job postings
    $recentOffers = TuitionOffer::with('user')
      ->latest()
      ->take(5)
      ->get();

    // Get recent applications
    $recentApplications = Application::with(['tutor', 'tuitionOffer'])
      ->latest()
      ->take(5)
      ->get();

    return view('admin.dashboard', compact('stats', 'recentOffers', 'recentApplications'));
  }
}
