<?php

namespace App\Http\Controllers;

use App\Models\User;
use Illuminate\Http\Request;

class PublicTypeController extends Controller
{
    /**
     * Show tutors by tuition type
     */
    public function show($type)
    {
        // Validate type
        $validTypes = ['home', 'online', 'group', 'assignment'];
        if (!in_array($type, $validTypes)) {
            abort(404);
        }

        $typeLabels = [
            'home' => 'Home Tuition',
            'online' => 'Online Tuition',
            'group' => 'Group Tuition',
            'assignment' => 'Assignment Help',
        ];

        $typeLabel = $typeLabels[$type];

        // Get tutors offering this type
        $tutors = User::where('role', 'tutor')
            ->whereHas('tutorProfile', function($query) use ($type) {
                $query->where('is_active', true)
                    ->where(function ($q) use ($type) {
                        $q->whereJsonContains('tuition_types', $type);
                        if ($type === 'home') {
                            $q->orWhereNull('tuition_types');
                        }
                    });
            })
            ->with(['tutorProfile'])
            ->paginate(12);

        return view('types.show', compact('type', 'typeLabel', 'tutors'));
    }
}
