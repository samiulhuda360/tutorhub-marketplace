@extends('layouts.app')
@section('title','Admin • Areas')

@section('content')
<section class="section">
  <div class="sectionTitle">
    <div>
      <h2>AREAS</h2>
      <div class="hint">Add / edit / delete areas</div>
    </div>
    <div class="row">
      <a class="btn" href="{{ route('admin.dashboard') }}">Back</a>
      <a class="btn primary" href="{{ route('admin.areas.create') }}">Add Area</a>
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
            <th style="padding:10px;">Name</th>
            <th style="padding:10px;">Slug</th>
            <th style="padding:10px;">Order</th>
            <th style="padding:10px;">Active</th>
            <th style="padding:10px;width:220px;">Action</th>
          </tr>
        </thead>
        <tbody>
          @foreach($areas as $a)
          <tr style="border-bottom:1px solid var(--line);">
            <td style="padding:10px;font-weight:800;">{{ $a->name }}</td>
            <td style="padding:10px;color:var(--muted);">{{ $a->slug }}</td>
            <td style="padding:10px;">{{ $a->sort_order }}</td>
            <td style="padding:10px;">
              <span class="badge">{{ $a->is_active ? 'Yes' : 'No' }}</span>
            </td>
            <td style="padding:10px;">
              <div class="row" style="gap:8px;flex-wrap:wrap;">
                <a class="btn" href="{{ route('admin.areas.edit', $a->id) }}">Edit</a>

                <form method="POST" action="{{ route('admin.areas.destroy', $a->id) }}"
                      onsubmit="return confirm('Delete this area?')">
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
