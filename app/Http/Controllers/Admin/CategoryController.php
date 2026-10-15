<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Category;
use Illuminate\Http\Request;
use Illuminate\Support\Str;

class CategoryController extends Controller
{
  public function index()
  {
    $categories = Category::orderBy('sort_order')->orderBy('id')->get();
    return view('admin.categories.index', compact('categories'));
  }

  public function create()
  {
    return view('admin.categories.create');
  }

  public function store(Request $request)
  {
    $data = $request->validate([
      'name' => ['required','string','max:255'],
      'slug' => ['nullable','string','max:255','unique:categories,slug'],
      'icon' => ['nullable','string','max:20'],
      'sort_order' => ['nullable','integer','min:0'],
      'is_active' => ['nullable'],
    ]);

    $data['slug'] = $data['slug'] ?: Str::slug($data['name']);
    $data['is_active'] = $request->boolean('is_active');
    $data['sort_order'] = $data['sort_order'] ?? 0;

    Category::create($data);

    return redirect()->route('admin.categories.index')->with('ok', 'Category created.');
  }

  public function edit(Category $category)
  {
    return view('admin.categories.edit', compact('category'));
  }

  public function update(Request $request, Category $category)
  {
    $data = $request->validate([
      'name' => ['required','string','max:255'],
      'slug' => ['required','string','max:255','unique:categories,slug,'.$category->id],
      'icon' => ['nullable','string','max:20'],
      'sort_order' => ['nullable','integer','min:0'],
      'is_active' => ['nullable'],
    ]);

    $data['is_active'] = $request->boolean('is_active');
    $data['sort_order'] = $data['sort_order'] ?? 0;

    $category->update($data);

    return redirect()->route('admin.categories.index')->with('ok', 'Category updated.');
  }

  public function destroy(Category $category)
  {
    $category->delete();
    return redirect()->route('admin.categories.index')->with('ok', 'Category deleted.');
  }
}
