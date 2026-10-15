<?php

namespace App\Http\Controllers\Parent;

use App\Http\Controllers\Controller;
use App\Models\TuitionOffer;
use App\Models\Category;
use App\Models\Area;
use App\Models\Subject;
use App\Models\Invitation;
use App\Matching\TutorMatcher;
use App\Support\TuitionCatalog;
use Illuminate\Http\Request;
use Illuminate\Validation\Rule;

class TuitionOfferController extends Controller
{
    public function index()
    {
        $offers = TuitionOffer::with(['category', 'area'])
            ->where('user_id', auth()->id())
            ->orderBy('posted_at', 'desc')
            ->paginate(10);

        return view('parent.offers.index', compact('offers'));
    }

    public function create()
    {
        $categories = Category::where('is_active', 1)->orderBy('name')->get();
        $areas = Area::where('is_active', 1)->orderBy('name')->get();
        $subjects = Subject::where('is_active', 1)->orderBy('name')->get();

        return view('parent.offers.create', compact('categories', 'areas', 'subjects'));
    }

    public function store(Request $request)
    {
        $data = $request->validate([
            'category_id' => ['required', 'exists:categories,id'],
            'area_id' => ['required', 'exists:areas,id'],
            'title' => ['required', 'string', 'max:255'],
            'description' => ['required', 'string'],
            'student_class' => ['required', Rule::in(TuitionCatalog::CLASSES)],
            'subjects' => ['required', 'array', 'min:1'],
            'subjects.*' => ['string', Rule::in(TuitionCatalog::subjects())],
            'tutor_gender_preference' => ['required', 'in:male,female,any'],
            'salary' => ['required', 'numeric', 'min:0'],
            'tuition_type' => ['required', 'in:home,online,center'],
            'days_per_week' => ['required', 'integer', 'min:1', 'max:7'],
            'location' => ['required', 'string', 'max:255'],
        ]);

        $data['user_id'] = auth()->id();
        $data['status'] = 'open';
        $data['posted_at'] = now();

        $offer = TuitionOffer::create($data);

        return redirect()->route('parent.offers.show', $offer->id)
            ->with('success', 'Tuition offer posted successfully!');
    }

    public function show(TuitionOffer $offer, TutorMatcher $matcher)
    {
        // Ensure parent can only see their own offers
        if ($offer->user_id !== auth()->id()) {
            abort(403, 'Unauthorized access.');
        }

        $offer->load(['category', 'area', 'applications.tutor.tutorProfile']);

        // Best-matched tutors, with the reasons and (when AI is on) one sentence each.
        $matches = $offer->status === 'open' ? $matcher->forOffer($offer, 5, explain: true) : collect();
        $invitedTutorIds = Invitation::where('parent_id', auth()->id())->pluck('tutor_id')->all();
        $appliedTutorIds = $offer->applications->pluck('tutor_id')->all();

        return view('parent.offers.show', compact('offer', 'matches', 'invitedTutorIds', 'appliedTutorIds'));
    }

    public function edit(TuitionOffer $offer)
    {
        // Ensure parent can only edit their own offers
        if ($offer->user_id !== auth()->id()) {
            abort(403, 'Unauthorized access.');
        }

        $categories = Category::where('is_active', 1)->orderBy('name')->get();
        $areas = Area::where('is_active', 1)->orderBy('name')->get();
        $subjects = Subject::where('is_active', 1)->orderBy('name')->get();

        return view('parent.offers.edit', compact('offer', 'categories', 'areas', 'subjects'));
    }

    public function update(Request $request, TuitionOffer $offer)
    {
        // Ensure parent can only update their own offers
        if ($offer->user_id !== auth()->id()) {
            abort(403, 'Unauthorized access.');
        }

        $data = $request->validate([
            'category_id' => ['required', 'exists:categories,id'],
            'area_id' => ['required', 'exists:areas,id'],
            'title' => ['required', 'string', 'max:255'],
            'description' => ['required', 'string'],
            'student_class' => ['required', Rule::in(TuitionCatalog::CLASSES)],
            'subjects' => ['required', 'array', 'min:1'],
            'subjects.*' => ['string', Rule::in(TuitionCatalog::subjects())],
            'tutor_gender_preference' => ['required', 'in:male,female,any'],
            'salary' => ['required', 'numeric', 'min:0'],
            'tuition_type' => ['required', 'in:home,online,center'],
            'days_per_week' => ['required', 'integer', 'min:1', 'max:7'],
            'location' => ['required', 'string', 'max:255'],
            'status' => ['required', 'in:open,filled,closed'],
        ]);

        $offer->update($data);

        return redirect()->route('parent.offers.show', $offer->id)
            ->with('success', 'Offer updated successfully!');
    }

    public function destroy(TuitionOffer $offer)
    {
        // Ensure parent can only delete their own offers
        if ($offer->user_id !== auth()->id()) {
            abort(403, 'Unauthorized access.');
        }

        $offer->delete();

        return redirect()->route('parent.offers.index')
            ->with('success', 'Offer deleted successfully!');
    }
}
