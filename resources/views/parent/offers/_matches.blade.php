{{-- Recommended tutors for an open offer: score, reasons, what does not fit, and an invite button. --}}
@if($offer->status === 'open')
  <div class="card p ai-panel" style="margin-top:14px;">
    <div class="spread">
      <div>
        <h3>Tutors who fit this offer</h3>
        <div class="small">Ranked by subjects, class, area, budget, experience and profile. You decide who to invite.</div>
      </div>
      <span class="ai-tag">{{ $matches->contains(fn ($m) => $m->explanationSource === 'ai') ? 'AI explanations' : 'Rule-based ranking' }}</span>
    </div>

    @forelse($matches as $m)
      <div class="match">
        <div class="avatar">{{ mb_substr($m->tutor->name, 0, 1) }}</div>
        <div>
          <div class="row" style="gap:8px;">
            <a href="{{ $m->tutor->profileUrl() }}" style="font-weight:800;">{{ $m->tutor->name }}</a>
            @if($m->tutor->tutorProfile?->is_verified)<span class="badge good">Verified</span>@endif
            <span class="small">{{ $m->tutor->tutor_code }}</span>
          </div>
          <div style="margin-top:4px;line-height:1.5;">{{ $m->explanation }}</div>
          <div class="chips">
            @foreach(array_slice($m->reasons, 0, 4) as $reason)<span class="chip">{{ $reason }}</span>@endforeach
            @foreach($m->concerns as $concern)<span class="chip no">{{ $concern }}</span>@endforeach
          </div>
        </div>
        <div style="text-align:right;">
          <div class="score">{{ $m->score }}</div>
          <div class="scorebar" style="margin-left:auto;"><span style="width:{{ $m->score }}%"></span></div>
          <div style="margin-top:10px;">
            @if(in_array($m->tutor->id, $appliedTutorIds, true))
              <span class="badge good">Applied</span>
            @elseif(in_array($m->tutor->id, $invitedTutorIds, true))
              <span class="badge">Invited</span>
            @else
              <form method="POST" action="{{ route('parent.invitations.store') }}">
                @csrf
                <input type="hidden" name="tutor_id" value="{{ $m->tutor->id }}">
                <input type="hidden" name="tuition_offer_id" value="{{ $offer->id }}">
                <input type="hidden" name="message" value="Hello, we posted &quot;{{ $offer->title }}&quot; and think you could be a good fit. Would you like to teach our child?">
                <button class="btn primary" type="submit">Invite</button>
              </form>
            @endif
          </div>
        </div>
      </div>
    @empty
      <p class="small" style="margin-bottom:0;">No tutor on TutorHub teaches these subjects{{ $offer->tutor_gender_preference !== 'any' ? ' and matches the tutor you asked for' : '' }} yet. Tutors who join later will see your offer.</p>
    @endforelse
  </div>
@endif
