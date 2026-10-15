<?php

namespace App\Http\Controllers;

use App\Models\Level;
use App\Models\User;
use Illuminate\Http\Request;

class PublicLevelController extends Controller
{
    /**
     * Show tutors for a specific education level
     */
    public function show($slug)
    {
        $level = Level::where('slug', $slug)
            ->where('is_active', true)
            ->firstOrFail();

        // Get tutors who teach this level
        $tutors = User::where('role', 'tutor')
            ->whereHas('tutorProfile', function($query) use ($level) {
                $query->where('is_active', true)
                    ->where(function ($q) use ($level) {
                        foreach (\App\Support\TuitionCatalog::classesForLevel($level->name) as $class) {
                            $q->orWhereJsonContains('classes', $class);
                        }
                    });
            })
            ->with(['tutorProfile'])
            ->paginate(12);

        return view('levels.show', compact('level', 'tutors'));
    }
}
