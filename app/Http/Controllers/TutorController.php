<?php

namespace App\Http\Controllers;

use App\Models\Area;
use App\Models\Subject;
use App\Models\User;
use App\Support\TuitionCatalog;
use Illuminate\Http\Request;

class TutorController extends Controller
{
    /** Public tutor directory. Subjects, classes and areas are JSON lists on the profile. */
    public function index(Request $request)
    {
        $query = User::where('role', 'tutor')
            ->whereHas('tutorProfile', fn ($q) => $q->where('is_active', true))
            ->with('tutorProfile');

        if ($request->filled('q')) {
            $search = $request->string('q')->limit(80)->toString();
            $query->where(function ($q) use ($search) {
                $q->where('name', 'like', "%{$search}%")
                    ->orWhereHas('tutorProfile', fn ($p) => $p->where('bio', 'like', "%{$search}%")
                        ->orWhere('education', 'like', "%{$search}%")
                        ->orWhere('subjects', 'like', "%{$search}%"));
            });
        }
        if ($request->filled('subject')) {
            $query->whereHas('tutorProfile', fn ($q) => $q->whereJsonContains('subjects', $request->string('subject')->toString()));
        }
        if ($request->filled('class')) {
            $query->whereHas('tutorProfile', fn ($q) => $q->whereJsonContains('classes', $request->string('class')->toString()));
        }
        if ($request->filled('area')) {
            $query->whereHas('tutorProfile', fn ($q) => $q->whereJsonContains('areas', (int) $request->input('area')));
        }
        if (in_array($request->input('gender'), ['male', 'female'], true)) {
            $query->whereHas('tutorProfile', fn ($q) => $q->where('gender', $request->input('gender')));
        }

        $profileColumn = fn (string $column) => fn ($q) => $q->select($column)->from('tutor_profiles')
            ->whereColumn('tutor_profiles.user_id', 'users.id')->limit(1);
        match ($request->get('sort', 'latest')) {
            'experience' => $query->orderByDesc($profileColumn('experience_years')),
            'rate_low' => $query->orderBy($profileColumn('hourly_rate')),
            'rate_high' => $query->orderByDesc($profileColumn('hourly_rate')),
            default => $query->latest('users.created_at'),
        };

        return view('tutors.index', [
            'tutors' => $query->paginate(12)->withQueryString(),
            'subjects' => Subject::where('is_active', true)->orderBy('sort_order')->pluck('name'),
            'areas' => Area::whereNotNull('parent_id')->where('is_active', true)->orderBy('name')->pluck('name', 'id'),
            'classes' => TuitionCatalog::CLASSES,
        ]);
    }

    /** A tutor's public profile, by code (T-1001). Old numeric links still work. */
    public function show(string $code)
    {
        $tutor = User::where('role', 'tutor')
            ->where(fn ($q) => $q->where('tutor_code', $code)->when(ctype_digit($code), fn ($w) => $w->orWhere('id', (int) $code)))
            ->with('tutorProfile')
            ->firstOrFail();

        if (! $tutor->tutorProfile || ! $tutor->tutorProfile->is_active) {
            abort(404, 'Tutor profile not found or inactive');
        }

        $areaNames = Area::whereIn('id', (array) $tutor->tutorProfile->areas)->pluck('name')->all();

        return view('tutors.show', compact('tutor', 'areaNames'));
    }
}
