@extends('layouts.app')
@section('title','Admin • Add Category')

@section('content')
<section class="section">
  <div class="sectionTitle">
    <div>
      <h2>ADD CATEGORY</h2>
      <div class="hint">Slug can be auto-generated</div>
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
    <form method="POST" action="{{ route('admin.categories.store') }}" class="stack" style="gap:12px;">
      @csrf

      <div class="grid cols-2">
        <div>
          <div class="hint" style="margin-bottom:6px;">Name</div>
          <input class="input" name="name" value="{{ old('name') }}" required>
        </div>
        <div>
          <div class="hint" style="margin-bottom:6px;">Slug (optional)</div>
          <input class="input" name="slug" value="{{ old('slug') }}" placeholder="auto from name">
        </div>
      </div>

      <div class="grid cols-2">
        <div>
          <div class="hint" style="margin-bottom:6px;">Icon (optional)</div>
          <input class="input" name="icon" value="{{ old('icon') }}" placeholder="Ex: 🎓 or Ab">
        </div>
        <div>
          <div class="hint" style="margin-bottom:6px;">Sort Order</div>
          <input class="input" type="number" name="sort_order" value="{{ old('sort_order', 0) }}">
        </div>
      </div>

      <label class="row" style="gap:8px;">
        <input type="checkbox" name="is_active" value="1" checked> Active
      </label>

      <button class="btn primary" type="submit">Create</button>
    </form>
  </div>
</section>
@endsection
