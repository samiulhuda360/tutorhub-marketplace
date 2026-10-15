<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\TutorProfile;
use App\Trust\ProfileReview;

/** Tutor profiles worth a look before verifying them, each with the reason it was flagged. */
class ReviewController extends Controller
{
    public function index(ProfileReview $review)
    {
        $flagged = $review->flagged();
        $unverified = TutorProfile::with('user')->where('is_verified', false)
            ->whereNotIn('id', $flagged->keys())->latest('id')->limit(20)->get();

        return view('admin.review', compact('flagged', 'unverified'));
    }

    public function verify(TutorProfile $profile)
    {
        $profile->update(['is_verified' => ! $profile->is_verified]);

        return back()->with('success', $profile->user->name.($profile->is_verified ? ' is now verified.' : ' is no longer verified.'));
    }
}
