<?php

namespace App\Http\Controllers\Tutor;

use App\Http\Controllers\Controller;
use App\Models\TutorProfile;
use App\Models\Area;
use App\Models\Subject;
use Illuminate\Http\Request;

class ProfileController extends Controller
{
    public function create()
    {
        // Check if profile already exists
        if (auth()->user()->tutorProfile) {
            return redirect()->route('tutor.profile.edit')
                ->with('info', 'You already have a profile. Edit it here.');
        }
        
        $areas = Area::where('is_active', 1)->orderBy('name')->get();
        $subjects = Subject::where('is_active', 1)->orderBy('name')->get();
        
        return view('tutor.profile.create', compact('areas', 'subjects'));
    }

    public function store(Request $request)
    {
        // Check if profile already exists
        if (auth()->user()->tutorProfile) {
            return redirect()->route('tutor.profile.edit')
                ->with('error', 'Profile already exists.');
        }
        
        $data = $request->validate([
            'phone' => ['required', 'string', 'max:20'],
            'gender' => ['required', 'in:male,female,other'],
            'date_of_birth' => ['nullable', 'date', 'before:today'],
            'education' => ['required', 'string'],
            'experience_years' => ['required', 'integer', 'min:0', 'max:50'],
            'bio' => ['nullable', 'string', 'max:1000'],
            'subjects' => ['required', 'array', 'min:1'],
            'subjects.*' => ['string'],
            'classes' => ['required', 'array', 'min:1'],
            'classes.*' => ['string'],
            'hourly_rate' => ['required', 'numeric', 'min:0'],
            'areas' => ['required', 'array', 'min:1'],
            'areas.*' => ['integer', 'exists:areas,id'],
            'availability' => ['nullable', 'string'],
        ]);

        $data['user_id'] = auth()->id();
        
        TutorProfile::create($data);

        return redirect()->route('tutor.dashboard')
            ->with('success', 'Profile created successfully!');
    }

    public function edit()
    {
        $profile = auth()->user()->tutorProfile;
        
        if (!$profile) {
            return redirect()->route('tutor.profile.create')
                ->with('info', 'Please create your profile first.');
        }
        
        $areas = Area::where('is_active', 1)->orderBy('name')->get();
        $subjects = Subject::where('is_active', 1)->orderBy('name')->get();
        
        return view('tutor.profile.edit', compact('profile', 'areas', 'subjects'));
    }

    public function update(Request $request)
    {
        $profile = auth()->user()->tutorProfile;
        
        if (!$profile) {
            return redirect()->route('tutor.profile.create');
        }

        $data = $request->validate([
            'phone' => ['required', 'string', 'max:20'],
            'gender' => ['required', 'in:male,female,other'],
            'date_of_birth' => ['nullable', 'date', 'before:today'],
            'education' => ['required', 'string'],
            'experience_years' => ['required', 'integer', 'min:0', 'max:50'],
            'bio' => ['nullable', 'string', 'max:1000'],
            'subjects' => ['required', 'array', 'min:1'],
            'subjects.*' => ['string'],
            'classes' => ['required', 'array', 'min:1'],
            'classes.*' => ['string'],
            'hourly_rate' => ['required', 'numeric', 'min:0'],
            'areas' => ['required', 'array', 'min:1'],
            'areas.*' => ['integer', 'exists:areas,id'],
            'availability' => ['nullable', 'string'],
        ]);

        $profile->update($data);

        return redirect()->route('tutor.dashboard')
            ->with('success', 'Profile updated successfully!');
    }
}
