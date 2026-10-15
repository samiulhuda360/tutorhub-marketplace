<?php

namespace App\Http\Controllers\Tutor;

use App\Ai\ProfileWriter;
use App\Http\Controllers\Controller;
use App\Models\TuitionOffer;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;

/** Draft buttons on the profile form (bio) and the apply form (cover letter). */
class WritingController extends Controller
{
    public function bio(Request $request, ProfileWriter $writer): JsonResponse
    {
        $data = $request->validate([
            'education' => ['nullable', 'string', 'max:255'],
            'experience_years' => ['nullable', 'integer', 'min:0', 'max:50'],
            'subjects' => ['nullable', 'array'],
            'subjects.*' => ['string', 'max:60'],
            'classes' => ['nullable', 'array'],
            'classes.*' => ['string', 'max:40'],
            'areas' => ['nullable', 'array'],
            'areas.*' => ['integer'],
            'availability' => ['nullable', 'string', 'max:255'],
        ]);

        return response()->json($writer->bio($data + ['name' => $request->user()->name]));
    }

    public function coverLetter(Request $request, TuitionOffer $offer, ProfileWriter $writer): JsonResponse
    {
        abort_unless($offer->status === 'open', 404);
        $profile = $request->user()->tutorProfile;
        abort_unless($profile, 422, 'Create your profile first.');

        return response()->json($writer->coverLetter($offer, $profile->only(
            ['education', 'experience_years', 'subjects', 'classes', 'areas', 'availability']
        ) + ['name' => $request->user()->name]));
    }
}
