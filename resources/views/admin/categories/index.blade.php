@extends('layouts.app')
@section('title','Admin • Categories')

@section('content')
<section class="section">
  <div class="sectionTitle">
    <div>
      <h2>CATEGORIES</h2>
      <div class="hint">Add / edit / delete categories</div>
    </div>
    <div class="row">
      <a class="btn" href="{{ route('admin.dashboard') }}">Back</a>
      <a class="btn primary" href="{{ route('admin.categories.create') }}">Add Category</a>
    </div>
  </div>

  @if(session('ok'))
    <div class="card p" style="margin-top:12px;border-color:rgba(0,200,120,.35);background:rgba(0,200,120,.06);">
      {{ session('ok') }}
    </div>
  @endif

  <div class="card p" style="margin-top:14px;">
    <div style="overflow:auto;">
      <table style="width:100%;border-collapse:collapse;">
        <thead>
          <tr style="text-align:left;border-bottom:1px solid var(--line);">
            <th style="padding:10px;">Icon</th>
            <th style="padding:10px;">Name</th>
            <th style="padding:10px;">Slug</th>
            <th style="padding:10px;">Order</th>
            <th style="padding:10px;">Active</th>
            <th style="padding:10px;width:220px;">Action</th>
          </tr>
        </thead>
        <tbody>
          @foreach($categories as $c)
          <tr style="border-bottom:1px solid var(--line);">
            <td style="padding:10px;"><span class="badge">{{ $c->icon ?? '—' }}</span></td>
            <td style="padding:10px;font-weight:800;">{{ $c->name }}</td>
            <td style="padding:10px;color:var(--muted);">{{ $c->slug }}</td>
            <td style="padding:10px;">{{ $c->sort_order }}</td>
            <td style="padding:10px;">
              <span class="badge">{{ $c->is_active ? 'Yes' : 'No' }}</span>
            </td>
            <td style="padding:10px;">
              <div class="row" style="gap:8px;flex-wrap:wrap;">
                <a class="btn" href="{{ route('admin.categories.edit', $c->id) }}">Edit</a>

                <form method="POST" action="{{ route('admin.categories.destroy', $c->id) }}"
                      onsubmit="return confirm('Delete this category?')">
                  @csrf
                  @method('DELETE')
                  <button class="btn ghost" type="submit">Delete</button>
                </form>
              </div>
            </td>
          </tr>
          @endforeach
        </tbody>
      </table>
    </div>
  </div>
</section>
@endsection
