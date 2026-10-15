<?php

namespace App\Http\Controllers;

use App\Models\Area;
use App\Models\User;
use Illuminate\Http\Request;

class PublicLocationController extends Controller
{
    /**
     * Show main area with sub-locations
     * Example: /tutors/dhaka
     */
    public function showMainArea($slug)
    {
        $area = Area::where('slug', $slug)
            ->whereNull('parent_id')
            ->where('is_active', true)
            ->firstOrFail();

        // Get all sub-locations
        $subLocations = $area->children()->get();

        // Get tutors count in this main area (including all sub-locations)
        // Note: areas is stored as JSON array in tutor_profiles table
        $tutorsCount = User::where('role', 'tutor')
            ->whereHas('tutorProfile', function($query) use ($area, $subLocations) {
                $query->where('is_active', true)
                    ->where(function ($q) use ($area, $subLocations) {
                        // areas is a JSON list of area ids
                        foreach ($subLocations->pluck('id')->push($area->id) as $id) {
                            $q->orWhereJsonContains('areas', $id);
                        }
                    });
            })
            ->count();

        return view('locations.main', compact('area', 'subLocations', 'tutorsCount'));
    }

    /**
     * Show sub-location with tutors
     * Example: /tutors/dhaka/dhanmondi
     */
    public function showSubLocation($parentSlug, $slug)
    {
        // Find parent area
        $parentArea = Area::where('slug', $parentSlug)
            ->whereNull('parent_id')
            ->where('is_active', true)
            ->firstOrFail();

        // Find sub-location
        $area = Area::where('slug', $slug)
            ->where('parent_id', $parentArea->id)
            ->where('is_active', true)
            ->firstOrFail();

        // Get tutors in this sub-location
        // Note: areas is stored as JSON array in tutor_profiles table
        $tutors = User::where('role', 'tutor')
            ->whereHas('tutorProfile', function($query) use ($area) {
                $query->where('is_active', true)
                    ->whereJsonContains('areas', $area->id);
            })
            ->with(['tutorProfile'])
            ->paginate(12);

        return view('locations.sub', compact('area', 'parentArea', 'tutors'));
    }
}
