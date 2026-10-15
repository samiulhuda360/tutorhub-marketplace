@extends('layouts.app')
@section('title', 'Invitation | TutorHub')

@section('content')
<section class="section">
  <div class="sectionTitle">
    <div>
      <h2>INVITATION</h2>
      <div class="hint">You invited {{ $invitation->tutor->name }} {{ $invitation->created_at->diffForHumans() }}</div>
    </div>
    <a class="btn" href="{{ route('parent.invitations.index') }}">All invitations</a>
  </div>

  <div class="card p stack" style="margin-top:14px;gap:12px;max-width:820px;">
    <div class="spread">
      <h3 style="margin:0;">{{ $invitation->tutor->name }} <span class="small">{{ $invitation->tutor->tutor_code }}</span></h3>
      <span class="badge {{ in_array($invitation->status, ['accepted', 'with_admin', 'contacted', 'negotiating', 'commission_paid', 'completed']) ? 'good' : ($invitation->status === 'rejected' ? 'warn' : '') }}">{{ ucfirst(str_replace('_', ' ', $invitation->status)) }}</span>
    </div>
    @if($invitation->tuitionOffer)
      <div class="small">For your offer: <a href="{{ route('parent.offers.show', $invitation->tuitionOffer) }}">{{ $invitation->tuitionOffer->title }}</a></div>
    @endif
    <div>
      <div class="small" style="margin-bottom:6px;">Your message</div>
      <p style="margin:0;white-space:pre-line;">{{ $invitation->message }}</p>
    </div>
    @if($invitation->tutor_response_message)
      <div>
        <div class="small" style="margin-bottom:6px;">Tutor's reply</div>
        <p style="margin:0;white-space:pre-line;">{{ $invitation->tutor_response_message }}</p>
      </div>
    @endif
    <div class="small">
      @switch($invitation->status)
        @case('pending') Waiting for the tutor to answer. @break
        @case('rejected') The tutor declined. You can invite someone else from your offer page. @break
        @case('completed') The match is confirmed and contact details have been shared. @break
        @default The tutor accepted. TutorHub is arranging the match and will contact you.
      @endswitch
    </div>
    @if($invitation->status === 'pending')
      <form method="POST" action="{{ route('parent.invitations.destroy', $invitation) }}">
        @csrf @method('DELETE')
        <button class="btn ghost" type="submit">Cancel invitation</button>
      </form>
    @endif
  </div>
</section>
@endsection
