<?php

namespace App\Http\Controllers;

use App\Models\Subject;
use App\Models\User;
use Illuminate\Http\Request;

class PublicSubjectController extends Controller
{
    /**
     * Show tutors for a specific subject
     */
    public function show($slug)
    {
        $subject = Subject::where('slug', $slug)
            ->where('is_active', true)
            ->firstOrFail();

        // Get tutors who teach this subject
        $tutors = User::where('role', 'tutor')
            ->whereHas('tutorProfile', function($query) use ($subject) {
                $query->where('is_active', true)
                    ->where(function($q) use ($subject) {
                        $q->where('subjects', 'like', '%' . $subject->name . '%')
                          ->orWhere('subjects', 'like', '%' . $subject->slug . '%');
                    });
            })
            ->with(['tutorProfile'])
            ->paginate(12);

        return view('subjects.show', compact('subject', 'tutors'));
    }
}
