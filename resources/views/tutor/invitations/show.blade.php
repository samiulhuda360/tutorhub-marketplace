@extends('layouts.app')
@section('title', 'Invitation | TutorHub')

@section('content')
<section class="section">
  <div class="sectionTitle">
    <div>
      <h2>INVITATION</h2>
      <div class="hint">From {{ $invitation->parent->name }}, {{ $invitation->created_at->diffForHumans() }}</div>
    </div>
    <a class="btn" href="{{ route('tutor.invitations.index') }}">All invitations</a>
  </div>

  @if(session('success'))<div class="flash ok">{{ session('success') }}</div>@endif
  @if(session('error'))<div class="flash err">{{ session('error') }}</div>@endif

  <div class="card p stack" style="margin-top:14px;gap:12px;max-width:820px;">
    <div class="spread">
      <h3 style="margin:0;">{{ $invitation->tuitionOffer?->title ?? 'Private invitation' }}</h3>
      <span class="badge">{{ ucfirst(str_replace('_', ' ', $invitation->status)) }}</span>
    </div>
    @if($invitation->tuitionOffer)
      @php($o = $invitation->tuitionOffer)
      <div class="kv">
        <div>Class</div><div>{{ $o->student_class }}</div>
        <div>Subjects</div><div>{{ implode(', ', (array) $o->subjects) }}</div>
        <div>Area</div><div>{{ $o->area?->name }}</div>
        <div>Salary</div><div>৳{{ number_format($o->salary) }} / month · {{ $o->days_per_week }} days a week</div>
      </div>
    @endif
    <div>
      <div class="small" style="margin-bottom:6px;">Message</div>
      <p style="margin:0;white-space:pre-line;">{{ $invitation->message }}</p>
    </div>

    @if($invitation->status === 'pending')
      <div class="grid cols-2" style="gap:12px;">
        <form method="POST" action="{{ route('tutor.invitations.accept', $invitation) }}" class="stack" style="gap:8px;">
          @csrf
          <textarea class="input" name="response_message" maxlength="500" placeholder="Optional reply to the parent"></textarea>
          <button class="btn primary" type="submit">Accept</button>
        </form>
        <form method="POST" action="{{ route('tutor.invitations.reject', $invitation) }}" class="stack" style="gap:8px;">
          @csrf
          <textarea class="input" name="response_message" maxlength="500" placeholder="Optional reason"></textarea>
          <button class="btn ghost" type="submit">Decline</button>
        </form>
      </div>
    @else
      <div class="small">
        @if($invitation->status === 'completed')
          The match is confirmed. TutorHub has shared the parent's contact details with you.
        @elseif($invitation->status === 'rejected')
          You declined this invitation.
        @else
          You accepted. TutorHub will contact you about the commission and then share the parent's contact details.
        @endif
      </div>
    @endif
  </div>
</section>
@endsection
