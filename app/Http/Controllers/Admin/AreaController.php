<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Area;
use Illuminate\Http\Request;
use Illuminate\Support\Str;

class AreaController extends Controller
{
  public function index()
  {
    $areas = Area::orderBy('sort_order')->orderBy('id')->get();
    return view('admin.areas.index', compact('areas'));
  }

  public function create()
  {
    return view('admin.areas.create');
  }

  public function store(Request $request)
  {
    $data = $request->validate([
      'name' => ['required','string','max:255'],
      'slug' => ['nullable','string','max:255','unique:areas,slug'],
      'sort_order' => ['nullable','integer','min:0'],
      'is_active' => ['nullable'],
    ]);

    $data['slug'] = $data['slug'] ?: Str::slug($data['name']);
    $data['is_active'] = $request->boolean('is_active');
    $data['sort_order'] = $data['sort_order'] ?? 0;

    Area::create($data);

    return redirect()->route('admin.areas.index')->with('ok', 'Area created.');
  }

  public function edit(Area $area)
  {
    return view('admin.areas.edit', compact('area'));
  }

  public function update(Request $request, Area $area)
  {
    $data = $request->validate([
      'name' => ['required','string','max:255'],
      'slug' => ['required','string','max:255','unique:areas,slug,'.$area->id],
      'sort_order' => ['nullable','integer','min:0'],
      'is_active' => ['nullable'],
    ]);

    $data['is_active'] = $request->boolean('is_active');
    $data['sort_order'] = $data['sort_order'] ?? 0;

    $area->update($data);

    return redirect()->route('admin.areas.index')->with('ok', 'Area updated.');
  }

  public function destroy(Area $area)
  {
    $area->delete();
    return redirect()->route('admin.areas.index')->with('ok', 'Area deleted.');
  }
}
