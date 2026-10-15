@extends('layouts.app')
@section('title', 'My Applications')

@section('content')
<section class="section">
  <div class="sectionTitle">
    <div>
      <h2>MY APPLICATIONS</h2>
      <div class="hint">Track your tuition applications and their status</div>
    </div>
    <a class="btn" href="{{ route('tutor.dashboard') }}">Back to Dashboard</a>
  </div>

  <!-- Status Filter -->
  <div class="card p" style="margin-top:14px;">
    <form method="GET" action="{{ route('tutor.applications.index') }}" class="row" style="gap:12px;align-items:flex-end;">
      <div>
        <label class="hint" style="display:block;margin-bottom:6px;">Filter by Status</label>
        <select class="select" name="status">
          <option value="">All Applications</option>
          <option value="pending" {{ request('status') == 'pending' ? 'selected' : '' }}>Pending</option>
          <option value="accepted" {{ request('status') == 'accepted' ? 'selected' : '' }}>Accepted</option>
          <option value="rejected" {{ request('status') == 'rejected' ? 'selected' : '' }}>Rejected</option>
        </select>
      </div>
      <button class="btn primary" type="submit">Apply Filter</button>
      @if(request('status'))
        <a class="btn ghost" href="{{ route('tutor.applications.index') }}">Clear</a>
      @endif
    </form>
  </div>

  <!-- Applications Count -->
  <div style="margin-top:14px;color:var(--muted);font-size:14px;">
    Showing {{ $applications->count() }} of {{ $applications->total() }} applications
  </div>

  <!-- Applications List -->
  @if($applications->count() > 0)
    <div class="stack" style="margin-top:14px;gap:14px;">
      @foreach($applications as $app)
        <div class="card p">
          <!-- Status Header -->
          <div class="spread" style="margin-bottom:14px;">
            <div class="row" style="gap:8px;">
              @if($app->status === 'pending')
                <span class="badge" style="background:rgba(255,140,0,.1);color:#ff8c00;font-weight:800;">PENDING</span>
              @elseif($app->status === 'accepted')
                <span class="badge" style="background:rgba(0,200,120,.1);color:#00c878;font-weight:800;">ACCEPTED</span>
              @else
                <span class="badge" style="background:rgba(255,0,0,.1);color:#ff0000;font-weight:800;">REJECTED</span>
              @endif

              @if($app->tuitionOffer->category)
                <span class="badge">{{ $app->tuitionOffer->category->name }}</span>
              @endif
              <span class="badge">{{ strtoupper($app->tuitionOffer->tuition_type) }}</span>
            </div>
            <span style="font-size:12px;color:var(--muted);">Applied {{ $app->applied_at->diffForHumans() }}</span>
          </div>

          <!-- Offer Title -->
          <h3 style="margin-bottom:12px;">{{ $app->tuitionOffer->title }}</h3>

          <!-- Offer Details -->
          <div class="grid cols-4" style="gap:16px;margin-bottom:14px;">
            <div>
              <div style="font-size:12px;color:var(--muted);margin-bottom:4px;">Location</div>
              <div style="font-weight:700;font-size:13px;">
                {{ $app->tuitionOffer->area->name ?? 'N/A' }}
              </div>
            </div>

            <div>
              <div style="font-size:12px;color:var(--muted);margin-bottom:4px;">Class</div>
              <div style="font-weight:700;font-size:13px;">{{ $app->tuitionOffer->student_class }}</div>
            </div>

            <div>
              <div style="font-size:12px;color:var(--muted);margin-bottom:4px;">Days/Week</div>
              <div style="font-weight:700;font-size:13px;">{{ $app->tuitionOffer->days_per_week }} days</div>
            </div>

            <div>
              <div style="font-size:12px;color:var(--muted);margin-bottom:4px;">Salary</div>
              <div style="font-weight:800;font-size:14px;color:#00c878;">{{ number_format($app->tuitionOffer->salary) }} Tk</div>
            </div>
          </div>

          <!-- Cover Letter Preview -->
          <div style="padding:12px;background:var(--bg);border-radius:8px;margin-bottom:12px;">
            <div style="font-size:11px;color:var(--muted);margin-bottom:6px;text-transform:uppercase;font-weight:700;">Your Cover Letter</div>
            <div style="font-size:13px;color:var(--text);line-height:1.5;">
              {{ Str::limit($app->cover_letter, 200) }}
            </div>
          </div>

          <!-- Actions -->
          <div class="row" style="gap:8px;">
            <a class="btn primary" href="{{ route('tutor.applications.show', $app->id) }}">View Details</a>
            <a class="btn" href="{{ route('tutor.offers.show', $app->tuitionOffer->id) }}">View Offer</a>

            @if($app->status === 'accepted')
              <span class="badge" style="background:rgba(0,200,120,.1);color:#00c878;padding:8px 14px;">
                🎉 Congratulations! You got this tuition.
              </span>
            @endif
          </div>
        </div>
      @endforeach
    </div>

    <!-- Pagination -->
    <div style="margin-top:20px;">
      {{ $applications->links() }}
    </div>
  @else
    <!-- Empty State -->
    <div class="card p" style="margin-top:14px;text-align:center;padding:60px 20px;">
      <div style="font-size:48px;margin-bottom:14px;">📝</div>
      <h3 style="margin-bottom:8px;">No applications yet</h3>
      <p style="color:var(--muted);margin-bottom:20px;">
        @if(request('status'))
          No {{ request('status') }} applications found. Try changing the filter.
        @else
          You haven't applied to any tuition offers yet. Start browsing available offers!
        @endif
      </p>
      <a class="btn primary" href="{{ route('tutor.offers.index') }}">Browse Tuition Offers</a>
    </div>
  @endif

</section>
@endsection
