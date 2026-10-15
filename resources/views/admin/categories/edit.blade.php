@extends('layouts.app')
@section('title','Admin • Edit Category')

@section('content')
<section class="section">
  <div class="sectionTitle">
    <div>
      <h2>EDIT CATEGORY</h2>
      <div class="hint">Update category details</div>
    </div>
    <a class="btn" href="{{ route('admin.categories.index') }}">Back</a>
  </div>

  @if($errors->any())
    <div class="card p" style="margin-top:12px;border-color:rgba(255,0,0,.35);background:rgba(255,0,0,.06);">
      <ul style="margin:0;padding-left:18px;">
        @foreach($errors->all() as $e)<li>{{ $e }}</li>@endforeach
      </ul>
    </div>
  @endif

  <div class="card p" style="margin-top:14px;max-width:720px;">
    <form method="POST" action="{{ route('admin.categories.update', $category->id) }}" class="stack" style="gap:12px;">
      @csrf
      @method('PUT')

      <div class="grid cols-2">
        <div>
          <div class="hint" style="margin-bottom:6px;">Name</div>
          <input class="input" name="name" value="{{ old('name', $category->name) }}" required>
        </div>
        <div>
          <div class="hint" style="margin-bottom:6px;">Slug</div>
          <input class="input" name="slug" value="{{ old('slug', $category->slug) }}" required>
        </div>
      </div>

      <div class="grid cols-2">
        <div>
          <div class="hint" style="margin-bottom:6px;">Icon</div>
          <input class="input" name="icon" value="{{ old('icon', $category->icon) }}">
        </div>
        <div>
          <div class="hint" style="margin-bottom:6px;">Sort Order</div>
          <input class="input" type="number" name="sort_order" value="{{ old('sort_order', $category->sort_order) }}">
        </div>
      </div>

      <label class="row" style="gap:8px;">
        <input type="checkbox" name="is_active" value="1" {{ old('is_active', $category->is_active) ? 'checked' : '' }}>
        Active
      </label>

      <button class="btn primary" type="submit">Save Changes</button>
    </form>
  </div>
</section>
@endsection
