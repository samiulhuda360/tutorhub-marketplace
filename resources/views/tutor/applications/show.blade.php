@extends('layouts.app')
@section('title', 'Application Details')

@section('content')
<section class="section">
  <div class="sectionTitle">
    <div>
      <h2>APPLICATION DETAILS</h2>
      <div class="hint">Review your application and the tuition offer</div>
    </div>
    <a class="btn" href="{{ route('tutor.applications.index') }}">Back to Applications</a>
  </div>

  <!-- Application Status Banner -->
  <div class="card p" style="margin-top:14px;">
    <div class="spread">
      <div>
        <div style="font-size:13px;color:var(--muted);margin-bottom:6px;">Application Status</div>
        @if($application->status === 'pending')
          <span class="badge" style="background:rgba(255,140,0,.1);color:#ff8c00;font-weight:800;font-size:16px;padding:8px 16px;">
            ⏳ PENDING REVIEW
          </span>
        @elseif($application->status === 'accepted')
          <span class="badge" style="background:rgba(0,200,120,.1);color:#00c878;font-weight:800;font-size:16px;padding:8px 16px;">
            ✅ ACCEPTED
          </span>
        @else
          <span class="badge" style="background:rgba(255,0,0,.1);color:#ff0000;font-weight:800;font-size:16px;padding:8px 16px;">
            ❌ REJECTED
          </span>
        @endif
      </div>
      <div style="text-align:right;">
        <div style="font-size:12px;color:var(--muted);">Applied</div>
        <div style="font-weight:700;">{{ $application->applied_at->format('M d, Y') }}</div>
        <div style="font-size:12px;color:var(--muted);">{{ $application->applied_at->diffForHumans() }}</div>
      </div>
    </div>

    @if($application->status === 'accepted')
      <div style="margin-top:16px;padding:16px;background:rgba(0,200,120,.06);border-radius:8px;border:1px solid rgba(0,200,120,.2);">
        <div style="font-weight:800;color:#00c878;margin-bottom:6px;">🎉 Congratulations!</div>
        <div style="font-size:13px;color:var(--text);line-height:1.6;">
          The parent accepted your application. TutorHub will contact you to confirm the match; the parent's
          address and phone number are shared here once the commission is recorded.
        </div>
      </div>
    @elseif($application->status === 'pending')
      <div style="margin-top:16px;padding:16px;background:rgba(255,140,0,.06);border-radius:8px;border:1px solid rgba(255,140,0,.2);">
        <div style="font-weight:800;color:#ff8c00;margin-bottom:6px;">⏳ Under Review</div>
        <div style="font-size:13px;color:var(--text);">
          The parent is reviewing your application. You'll be notified once they make a decision.
        </div>
      </div>
    @endif
  </div>

  <div class="grid cols-3" style="margin-top:14px;gap:14px;">

    <!-- Left: Offer Details (2 columns) -->
    <div style="grid-column:span 2;">

      <!-- Tuition Offer Info -->
      <div class="card p">
        <div class="spread" style="margin-bottom:14px;">
          <h3>TUITION OFFER</h3>
          <div class="row" style="gap:6px;">
            @if($application->tuitionOffer->category)
              <span class="badge">{{ $application->tuitionOffer->category->name }}</span>
            @endif
            <span class="badge">{{ strtoupper($application->tuitionOffer->tuition_type) }}</span>
          </div>
        </div>

        <h2 style="margin-bottom:16px;">{{ $application->tuitionOffer->title }}</h2>

        <!-- Salary Highlight -->
        <div style="padding:16px;background:rgba(0,200,120,.06);border-radius:8px;margin-bottom:20px;">
          <div style="font-size:13px;color:var(--muted);margin-bottom:4px;">Monthly Salary</div>
          <div style="font-size:28px;font-weight:950;color:#00c878;">{{ number_format($application->tuitionOffer->salary) }} Tk</div>
        </div>

        <!-- Description -->
        @if($application->tuitionOffer->description)
          <div style="margin-bottom:20px;">
            <h4 style="margin-bottom:10px;">Description</h4>
            <p style="color:var(--text);line-height:1.6;white-space:pre-line;">{{ $application->tuitionOffer->description }}</p>
          </div>
        @endif

        <!-- Details Grid -->
        <div>
          <h4 style="margin-bottom:12px;">Details</h4>

          <div class="grid cols-2" style="gap:16px;">
            <div>
              <div style="font-size:12px;color:var(--muted);margin-bottom:4px;">Student Class</div>
              <div style="font-weight:700;">{{ $application->tuitionOffer->student_class }}</div>
            </div>

            <div>
              <div style="font-size:12px;color:var(--muted);margin-bottom:4px;">Subjects</div>
              <div style="font-weight:700;">{{ is_array($application->tuitionOffer->subjects) ? implode(', ', $application->tuitionOffer->subjects) : $application->tuitionOffer->subjects }}</div>
            </div>

            <div>
              <div style="font-size:12px;color:var(--muted);margin-bottom:4px;">Days Per Week</div>
              <div style="font-weight:700;">{{ $application->tuitionOffer->days_per_week }} days</div>
            </div>

            <div>
              <div style="font-size:12px;color:var(--muted);margin-bottom:4px;">Tutor Preference</div>
              <div style="font-weight:700;">{{ ucfirst($application->tuitionOffer->tutor_gender_preference) }}</div>
            </div>

            <div>
              <div style="font-size:12px;color:var(--muted);margin-bottom:4px;">Area</div>
              <div style="font-weight:700;">{{ $application->tuitionOffer->area->name ?? 'N/A' }}</div>
            </div>

            <div>
              <div style="font-size:12px;color:var(--muted);margin-bottom:4px;">Address</div>
              <div style="font-weight:700;">{{ $application->parent_contact_shared_at ? $application->tuitionOffer->location : 'Shared after the match is confirmed' }}</div>
            </div>
          </div>
        </div>
      </div>

      <!-- Your Cover Letter -->
      <div class="card p" style="margin-top:14px;">
        <h3 style="margin-bottom:14px;">YOUR COVER LETTER</h3>
        <div style="padding:16px;background:var(--bg);border-radius:8px;border:1px solid var(--line);">
          <p style="color:var(--text);line-height:1.8;white-space:pre-line;">{{ $application->cover_letter }}</p>
        </div>
      </div>

    </div>

    <!-- Right: Sidebar -->
    <div>

      <!-- Parent Contact Info -->
      <div class="card p" style="margin-bottom:14px;">
        <h4 style="margin-bottom:12px;">Parent contact</h4>
        @if($application->parent_contact_shared_at)
          <div style="font-weight:800;font-size:16px;margin-bottom:4px;">{{ $application->tuitionOffer->user->name }}</div>
          <div style="margin-top:12px;padding:12px;background:var(--bg);border-radius:8px;">
            <div style="font-size:11px;color:var(--muted);margin-bottom:4px;">Email</div>
            <div style="font-weight:700;font-size:13px;">{{ $application->tuitionOffer->user->email }}</div>
          </div>
        @else
          <div style="margin-top:8px;font-size:12px;color:var(--muted);">
            TutorHub shares the parent's name, address and contact details once the parent accepts and the match is confirmed.
          </div>
        @endif
      </div>

      <!-- Application Timeline -->
      <div class="card p" style="margin-bottom:14px;">
        <h4 style="margin-bottom:12px;">Timeline</h4>
        <div class="stack" style="gap:10px;">
          <div>
            <div style="font-size:11px;color:var(--muted);">Offer Posted</div>
            <div style="font-weight:700;font-size:13px;">{{ $application->tuitionOffer->posted_at->format('M d, Y') }}</div>
          </div>

          <div>
            <div style="font-size:11px;color:var(--muted);">You Applied</div>
            <div style="font-weight:700;font-size:13px;">{{ $application->applied_at->format('M d, Y') }}</div>
          </div>

          @if($application->updated_at->ne($application->created_at))
            <div>
              <div style="font-size:11px;color:var(--muted);">Last Updated</div>
              <div style="font-weight:700;font-size:13px;">{{ $application->updated_at->format('M d, Y') }}</div>
            </div>
          @endif
        </div>
      </div>

      <!-- Quick Actions -->
      <div class="card p">
        <div class="stack" style="gap:8px;">
          <a class="btn" href="{{ route('tutor.offers.show', $application->tuitionOffer->id) }}" style="width:100%;">View Full Offer</a>
          <a class="btn" href="{{ route('tutor.applications.index') }}" style="width:100%;">All Applications</a>
          <a class="btn ghost" href="{{ route('tutor.dashboard') }}" style="width:100%;">Dashboard</a>
        </div>
      </div>
    </div>

  </div>
</section>
@endsection
