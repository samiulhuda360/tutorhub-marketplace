@extends('layouts.app')
@section('title', 'Application from '.$application->tutor->name.' | TutorHub')

@section('content')
@php($p = $application->tutor->tutorProfile)
<section class="section">
  <div class="sectionTitle">
    <div>
      <h2>APPLICATION</h2>
      <div class="hint">For "{{ $application->tuitionOffer->title }}"</div>
    </div>
    <a class="btn" href="{{ route('parent.offers.show', $application->tuitionOffer) }}">Back to the offer</a>
  </div>

  @if(session('success'))<div class="flash ok">{{ session('success') }}</div>@endif
  @if(session('error'))<div class="flash err">{{ session('error') }}</div>@endif

  <div class="grid cols-3" style="margin-top:14px;gap:14px;align-items:start;">
    <article class="card p stack" style="grid-column:span 2;gap:12px;">
      <div class="tutor">
        <div class="avatar">{{ mb_substr($application->tutor->name, 0, 1) }}</div>
        <div style="flex:1;">
          <div class="spread">
            <h3 style="margin:0;">{{ $application->tutor->name }}</h3>
            <span class="badge {{ $application->status === 'accepted' ? 'good' : ($application->status === 'rejected' ? 'warn' : '') }}">{{ ucfirst($application->status) }}</span>
          </div>
          <div class="small">{{ $application->tutor->tutor_code }}{{ $p?->is_verified ? ' · Verified' : '' }} · applied {{ optional($application->applied_at)->diffForHumans() }}</div>
        </div>
      </div>
      <div>
        <div class="small" style="margin-bottom:6px;">Message from the tutor</div>
        <p style="margin:0;line-height:1.7;white-space:pre-line;">{{ $application->cover_letter }}</p>
      </div>
      @if($application->isPending())
        <div class="row" style="gap:8px;">
          <form method="POST" action="{{ route('parent.applications.accept', $application) }}">@csrf<button class="btn primary">Accept</button></form>
          <form method="POST" action="{{ route('parent.applications.reject', $application) }}">@csrf<button class="btn ghost">Reject</button></form>
        </div>
        <div class="small">Accepting sends the match to TutorHub, which contacts the tutor and shares contact details once it is confirmed.</div>
      @endif
    </article>

    @if($p)
      <aside class="card p stack" style="gap:10px;">
        <h3 style="margin:0;">Profile</h3>
        <div class="kv">
          <div>Education</div><div>{{ $p->education }}</div>
          <div>Experience</div><div>{{ $p->experience_years }} years</div>
          <div>Subjects</div><div>{{ implode(', ', (array) $p->subjects) }}</div>
          <div>Classes</div><div>{{ implode(', ', (array) $p->classes) }}</div>
          <div>Fee</div><div>৳{{ number_format($p->hourly_rate) }} / month</div>
        </div>
        <a class="btn" href="{{ $application->tutor->profileUrl() }}">Full profile</a>
      </aside>
    @endif
  </div>
</section>
@endsection
