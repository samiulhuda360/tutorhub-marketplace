<?php

namespace App\Http\Controllers;

use App\Models\Category;
use App\Models\Area;
use App\Models\TutorProfile;
use App\Models\TuitionOffer;
use App\Models\User;
use App\Models\Subject;
use App\Models\Level;

class HomeController extends Controller
{
  public function index()
  {
    // Get active categories
    $categories = Category::where('is_active', 1)
      ->orderBy('sort_order')
      ->orderBy('id')
      ->take(8)
      ->get();

    // Get active areas
    $areas = Area::where('is_active', 1)
      ->orderBy('sort_order')
      ->orderBy('id')
      ->take(8)
      ->get();

    // Get active subjects
    $subjects = Subject::where('is_active', 1)
      ->orderBy('sort_order')
      ->take(8)
      ->get();

    // Get active levels
    $levels = Level::where('is_active', 1)
      ->orderBy('sort_order')
      ->take(8)
      ->get();

    // Get featured tutors (verified and with complete profiles)
    $featuredTutors = User::where('role', 'tutor')
      ->whereHas('tutorProfile', function($q) {
        $q->where('is_active', 1);
      })
      ->with('tutorProfile')
      ->take(6)
      ->get();

    // Get recent tuition offers
    $recentOffers = TuitionOffer::where('status', 'open')
      ->with(['category', 'area', 'user'])
      ->orderBy('posted_at', 'desc')
      ->take(6)
      ->get();

    // Get statistics
    $stats = [
      'total_tutors' => User::where('role', 'tutor')->count(),
      'total_offers' => TuitionOffer::count(),
      'open_offers' => TuitionOffer::where('status', 'open')->count(),
      'total_parents' => User::where('role', 'parent')->count(),
    ];

    return view('home', compact('categories', 'areas', 'subjects', 'levels', 'featuredTutors', 'recentOffers', 'stats'));
  }
}
