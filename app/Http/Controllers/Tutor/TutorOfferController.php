<?php

namespace App\Http\Controllers\Tutor;

use App\Http\Controllers\Controller;
use App\Models\TuitionOffer;
use App\Models\Application;
use App\Models\Category;
use App\Models\Area;
use App\Matching\TutorMatcher;
use Illuminate\Http\Request;

class TutorOfferController extends Controller
{
    public function index(Request $request)
    {
        $query = TuitionOffer::with(['category', 'area', 'user'])
            ->where('status', 'open');

        // Filters
        if ($request->filled('category')) {
            $query->where('category_id', $request->category);
        }

        if ($request->filled('area')) {
            $query->where('area_id', $request->area);
        }

        if ($request->filled('tuition_type')) {
            $query->where('tuition_type', $request->tuition_type);
        }

        if ($request->filled('min_salary')) {
            $query->where('salary', '>=', $request->min_salary);
        }

        if ($request->filled('max_salary')) {
            $query->where('salary', '<=', $request->max_salary);
        }

        if ($request->filled('search')) {
            $search = $request->search;
            $query->where(function($q) use ($search) {
                $q->where('title', 'like', "%{$search}%")
                  ->orWhere('description', 'like', "%{$search}%")
                  ->orWhere('location', 'like', "%{$search}%");
            });
        }

        $offers = $query->orderBy('posted_at', 'desc')->paginate(12);
        
        $categories = Category::where('is_active', 1)->orderBy('name')->get();
        $areas = Area::where('is_active', 1)->orderBy('name')->get();

        return view('tutor.offers.index', compact('offers', 'categories', 'areas'));
    }

    public function show(TuitionOffer $offer, TutorMatcher $matcher)
    {
        $offer->load(['category', 'area']);

        $application = Application::where('tuition_offer_id', $offer->id)
            ->where('tutor_id', auth()->id())
            ->first();
        $hasApplied = $application !== null;

        // How this tutor fits the offer, from the same scoring the parent sees.
        $profile = auth()->user()->tutorProfile;
        $fit = $profile ? $matcher->score($offer, $profile, 0.5) : null;

        return view('tutor.offers.show', compact('offer', 'hasApplied', 'application', 'profile', 'fit'));
    }

    public function apply(Request $request, TuitionOffer $offer)
    {
        // Check if tutor has profile
        $profile = auth()->user()->tutorProfile;
        if (!$profile || !$profile->isComplete()) {
            return back()->with('error', 'Please complete your profile before applying.');
        }

        // Check if offer is still open
        if ($offer->status !== 'open') {
            return back()->with('error', 'This offer is no longer accepting applications.');
        }

        // Check if already applied
        $exists = Application::where('tuition_offer_id', $offer->id)
            ->where('tutor_id', auth()->id())
            ->exists();

        if ($exists) {
            return back()->with('error', 'You have already applied to this offer.');
        }

        $data = $request->validate([
            'cover_letter' => ['required', 'string', 'min:50', 'max:1000'],
        ]);

        Application::create([
            'tuition_offer_id' => $offer->id,
            'tutor_id' => auth()->id(),
            'cover_letter' => $data['cover_letter'],
            'status' => 'pending',
            'applied_at' => now(),
        ]);

        return redirect()->route('tutor.offers.show', $offer->id)
            ->with('success', 'Application submitted successfully!');
    }
}
