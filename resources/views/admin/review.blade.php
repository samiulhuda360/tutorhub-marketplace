@extends('layouts.app')
@section('title', 'Profiles to review - Admin')

@section('content')
<section class="section">
  <div class="sectionTitle">
    <div>
      <h2>PROFILES TO REVIEW</h2>
      <div class="hint">Signals worth a look before you verify a tutor. Nothing here blocks anyone by itself.</div>
    </div>
    <a class="btn" href="{{ route('admin.dashboard') }}">Dashboard</a>
  </div>

  @if(session('success'))<div class="flash ok">{{ session('success') }}</div>@endif

  <div class="card p" style="margin-top:14px;">
    <h3 style="margin-top:0;">Flagged ({{ $flagged->count() }})</h3>
    @forelse($flagged as $row)
      @php($p = $row['profile'])
      <div class="spread" style="padding:12px 0;border-top:1px solid var(--line);align-items:flex-start;">
        <div>
          <a href="{{ $p->user->profileUrl() }}" style="font-weight:800;">{{ $p->user->name }}</a>
          <span class="small">{{ $p->user->tutor_code }} · joined {{ $p->created_at->diffForHumans() }}</span>
          <div class="chips">@foreach($row['signals'] as $signal)<span class="chip no">{{ $signal }}</span>@endforeach</div>
        </div>
        <form method="POST" action="{{ route('admin.tutors.verify', $p) }}">
          @csrf
          <button class="btn {{ $p->is_verified ? 'ghost' : '' }}" type="submit">{{ $p->is_verified ? 'Remove verification' : 'Verify anyway' }}</button>
        </form>
      </div>
    @empty
      <p class="small" style="margin-bottom:0;">Nothing flagged: no shared phone numbers, copied bios, copied cover letters or impossible experience.</p>
    @endforelse
  </div>

  <div class="card p" style="margin-top:14px;">
    <h3 style="margin-top:0;">Not yet verified</h3>
    @forelse($unverified as $p)
      <div class="spread" style="padding:10px 0;border-top:1px solid var(--line);">
        <div>
          <a href="{{ $p->user->profileUrl() }}" style="font-weight:700;">{{ $p->user->name }}</a>
          <span class="small">{{ $p->education }} · {{ $p->experience_years }} years</span>
        </div>
        <form method="POST" action="{{ route('admin.tutors.verify', $p) }}">@csrf<button class="btn primary" type="submit">Verify</button></form>
      </div>
    @empty
      <p class="small" style="margin-bottom:0;">Every other tutor is verified.</p>
    @endforelse
  </div>
</section>
@endsection
