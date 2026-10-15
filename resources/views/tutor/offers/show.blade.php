@extends('layouts.app')
@section('title', $offer->title.' | TutorHub')

@section('content')
<section class="section">
  <div class="sectionTitle">
    <div>
      <h2>TUITION JOB</h2>
      <div class="hint">Read the details, check how you fit, and apply</div>
    </div>
    <a class="btn" href="{{ route('tutor.offers.index') }}">All jobs</a>
  </div>

  @if(session('success'))<div class="flash ok">{{ session('success') }}</div>@endif
  @if(session('error'))<div class="flash err">{{ session('error') }}</div>@endif

  <div class="grid cols-3" style="margin-top:14px;gap:14px;align-items:start;">
    <div class="stack" style="grid-column:span 2;gap:14px;">
      <article class="card p stack" style="gap:12px;">
        <div class="row" style="gap:6px;">
          <span class="badge">{{ strtoupper($offer->tuition_type) }}</span>
          @if($offer->category)<span class="badge">{{ $offer->category->name }}</span>@endif
          <span class="small" style="margin-left:auto;">Posted {{ optional($offer->posted_at)->diffForHumans() }}</span>
        </div>
        <h2 style="margin:0;">{{ $offer->title }}</h2>
        <p style="margin:0;line-height:1.6;white-space:pre-line;">{{ $offer->description }}</p>
        <div class="kv">
          <div>Class</div><div>{{ $offer->student_class }}</div>
          <div>Subjects</div><div>{{ implode(', ', (array) $offer->subjects) }}</div>
          <div>Area</div><div>{{ $offer->area?->name }}</div>
          <div>Days</div><div>{{ $offer->days_per_week }} days a week</div>
          <div>Tutor</div><div>{{ $offer->tutor_gender_preference === 'any' ? 'Male or female' : ucfirst($offer->tutor_gender_preference).' tutor preferred' }}</div>
          <div>Salary</div><div><strong>৳{{ number_format($offer->salary) }}</strong> a month</div>
        </div>
        <div class="small">The exact address and the parent's phone number are shared by TutorHub once the match is confirmed.</div>
      </article>

      <article class="card p">
        @if($hasApplied)
          <h3 style="margin-top:0;">You applied {{ optional($application->applied_at)->diffForHumans() }}</h3>
          <div class="row" style="gap:8px;">
            <span class="badge {{ $application->status === 'accepted' ? 'good' : ($application->status === 'rejected' ? 'warn' : '') }}">{{ ucfirst($application->status) }}</span>
            <a class="btn" href="{{ route('tutor.applications.show', $application) }}">View application</a>
          </div>
        @elseif(! $profile || ! $profile->isComplete())
          <h3 style="margin-top:0;">Complete your profile to apply</h3>
          <p class="small">Parents see your subjects, classes, areas and education. Add them first.</p>
          <a class="btn primary" href="{{ $profile ? route('tutor.profile.edit') : route('tutor.profile.create') }}">Complete profile</a>
        @elseif($offer->status !== 'open')
          <h3 style="margin-top:0;">This job is no longer open</h3>
        @else
          <form method="POST" action="{{ route('tutor.offers.apply', $offer) }}" class="stack" style="gap:10px;">
            @csrf
            <div class="spread">
              <h3 style="margin:0;">Apply for this job</h3>
              <button type="button" class="btn" data-ai-draft data-target="textarea[name=cover_letter]"
                      data-url="{{ route('tutor.offers.cover-letter', $offer) }}">Draft my message</button>
            </div>
            <textarea class="input" name="cover_letter" minlength="50" maxlength="1000" required
                      placeholder="Introduce yourself to the parent: what you teach, your experience, and when you can come.">{{ old('cover_letter') }}</textarea>
            @error('cover_letter')<div class="small" style="color:#b91c1c;">{{ $message }}</div>@enderror
            <div class="spread">
              <span class="small" data-ai-status>50 to 1000 characters. The draft is only a start: make it yours.</span>
              <button class="btn primary" type="submit">Send application</button>
            </div>
          </form>
        @endif
      </article>
    </div>

    @if($fit)
      <aside class="card p ai-panel stack" style="gap:12px;">
        <div class="spread">
          <h3 style="margin:0;">How you fit</h3>
          <div style="text-align:right;">
            <div class="score">{{ $fit->score }}<span class="small">/100</span></div>
            <div class="scorebar"><span style="width:{{ $fit->score }}%"></span></div>
          </div>
        </div>
        <div class="parts">
          @foreach($fit->parts as $part)
            <span>{{ $part['label'] }}</span>
            <span class="bar"><span style="width:{{ $part['max'] ? round(100 * $part['points'] / $part['max']) : 0 }}%"></span></span>
            <span class="small">{{ $part['points'] }}/{{ $part['max'] }}</span>
          @endforeach
        </div>
        <div class="chips">
          @foreach($fit->reasons as $reason)<span class="chip">{{ $reason }}</span>@endforeach
          @foreach($fit->concerns as $concern)<span class="chip no">{{ $concern }}</span>@endforeach
        </div>
        <div class="small">TutorHub uses this score when it suggests tutors to the parent.</div>
      </aside>
    @endif
  </div>
</section>
@endsection
