@extends('layouts.app')
@section('title', 'Tutor Dashboard | TutorHub')

@section('content')
<section class="section">
  <div class="sectionTitle">
    <div>
      <h2>WELCOME, {{ strtoupper(auth()->user()->name) }}</h2>
      <div class="hint">Your profile, the jobs that fit you, and your applications</div>
    </div>
    <div class="row" style="gap:8px;">
      <a class="btn" href="{{ route('tutor.offers.index') }}">Browse all jobs</a>
      <a class="btn primary" href="{{ $profile ? route('tutor.profile.edit') : route('tutor.profile.create') }}">{{ $profile ? 'Edit profile' : 'Create profile' }}</a>
    </div>
  </div>

  @if(session('success'))<div class="flash ok">{{ session('success') }}</div>@endif
  @if(session('info'))<div class="flash">{{ session('info') }}</div>@endif

  @if(! $profile)
    <div class="card p" style="margin-top:14px;">
      <h3 style="margin-top:0;">Create your profile to start getting jobs</h3>
      <p class="small">Parents and TutorHub's matching use your subjects, classes, areas, fee and experience.</p>
      <a class="btn primary" href="{{ route('tutor.profile.create') }}">Create profile</a>
    </div>
  @elseif(! $profile->isComplete())
    <div class="flash err">Your profile is {{ $profile->completionPercentage() }}% complete. Finish it to apply for jobs. <a href="{{ route('tutor.profile.edit') }}">Complete it</a></div>
  @endif

  <div class="grid cols-4" style="margin-top:14px;gap:12px;">
    <div class="card p"><div class="small">Applications</div><div class="score">{{ $stats['total_applications'] }}</div></div>
    <div class="card p"><div class="small">Waiting</div><div class="score">{{ $stats['pending_applications'] }}</div></div>
    <div class="card p"><div class="small">Accepted</div><div class="score">{{ $stats['accepted_applications'] }}</div></div>
    <div class="card p"><div class="small">Open jobs</div><div class="score">{{ $stats['available_offers'] }}</div></div>
  </div>

  <div class="grid cols-3" style="margin-top:14px;gap:14px;align-items:start;">
    <div class="card p ai-panel" style="grid-column:span 2;">
      <div class="spread">
        <div>
          <h3>Jobs that fit you</h3>
          <div class="small">Ranked by your subjects, classes, areas and fee against each open job.</div>
        </div>
      </div>
      @forelse($forYou as $m)
        <div class="match" style="grid-template-columns:1fr auto;">
          <div>
            <a href="{{ route('tutor.offers.show', $m->offer) }}" style="font-weight:800;">{{ $m->offer->title }}</a>
            <div class="small">{{ $m->offer->area?->name }} · {{ $m->offer->student_class }} · ৳{{ number_format($m->offer->salary) }} a month · {{ $m->offer->days_per_week }} days</div>
            <div class="chips">
              @foreach(array_slice($m->reasons, 0, 3) as $reason)<span class="chip">{{ $reason }}</span>@endforeach
              @foreach(array_slice($m->concerns, 0, 2) as $concern)<span class="chip no">{{ $concern }}</span>@endforeach
            </div>
          </div>
          <div style="text-align:right;">
            <div class="score">{{ $m->score }}</div>
            <div class="scorebar" style="margin-left:auto;"><span style="width:{{ $m->score }}%"></span></div>
          </div>
        </div>
      @empty
        <p class="small" style="margin-bottom:0;">No open jobs match your subjects right now. Add more subjects or areas to your profile to see more.</p>
      @endforelse
    </div>

    <div class="card p">
      <h3 style="margin-top:0;">Your recent applications</h3>
      @forelse($myApplications as $app)
        <div class="spread" style="padding:8px 0;border-top:1px solid var(--line);">
          <a href="{{ route('tutor.applications.show', $app) }}">{{ \Illuminate\Support\Str::limit($app->tuitionOffer->title, 38) }}</a>
          <span class="badge {{ $app->status === 'accepted' ? 'good' : ($app->status === 'rejected' ? 'warn' : '') }}">{{ ucfirst($app->status) }}</span>
        </div>
      @empty
        <p class="small">No applications yet.</p>
      @endforelse
      <a class="btn" style="margin-top:10px;" href="{{ route('tutor.applications.index') }}">All applications</a>
    </div>
  </div>
</section>
@endsection
