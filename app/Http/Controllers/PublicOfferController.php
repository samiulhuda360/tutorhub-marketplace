<?php

namespace App\Http\Controllers;

use App\Models\Area;
use App\Models\Category;
use App\Models\TuitionOffer;
use Illuminate\Http\Request;

class PublicOfferController extends Controller
{
    public function index(Request $request)
    {
        $query = TuitionOffer::where('status', 'open')->with(['category', 'area']);

        if ($request->filled('q')) {
            $search = $request->string('q')->limit(80)->toString();
            $query->where(fn ($q) => $q->where('title', 'like', "%{$search}%")
                ->orWhere('description', 'like', "%{$search}%")
                ->orWhere('subjects', 'like', "%{$search}%"));
        }
        if ($request->filled('category')) {
            $query->whereHas('category', fn ($q) => $q->where('slug', $request->category));
        }
        if ($request->filled('area')) {
            $query->where('area_id', (int) $request->area);
        }
        if (in_array($request->input('type'), ['home', 'online', 'center'], true)) {
            $query->where('tuition_type', $request->input('type'));
        }
        if ($request->filled('min_salary')) {
            $query->where('salary', '>=', (int) $request->min_salary);
        }
        if ($request->filled('max_salary')) {
            $query->where('salary', '<=', (int) $request->max_salary);
        }

        match ($request->get('sort', 'latest')) {
            'salary_high' => $query->orderByDesc('salary'),
            'salary_low' => $query->orderBy('salary'),
            default => $query->latest('posted_at'),
        };

        return view('tuition_offers.index', [
            'offers' => $query->paginate(12)->withQueryString(),
            'categories' => Category::where('is_active', true)->orderBy('sort_order')->get(),
            'areas' => Area::whereNotNull('parent_id')->where('is_active', true)->orderBy('name')->pluck('name', 'id'),
        ]);
    }

    public function show($id)
    {
        $offer = TuitionOffer::with(['category', 'area'])->findOrFail($id);
        if ($offer->status !== 'open') {
            abort(404, 'This tuition offer is no longer available');
        }

        return view('tuition_offers.show', compact('offer'));
    }
}
