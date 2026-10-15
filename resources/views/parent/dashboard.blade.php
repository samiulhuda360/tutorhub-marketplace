@extends('layouts.app')
@section('title', 'Parent Dashboard')

@section('content')
<section class="section">
  <div class="sectionTitle">
    <div>
      <h2>PARENT DASHBOARD</h2>
      <div class="hint">Welcome back, {{ auth()->user()->name }}</div>
    </div>

    <form method="POST" action="{{ route('logout') }}">
      @csrf
      <button class="btn" type="submit">Logout</button>
    </form>
  </div>

  <!-- Stats Cards -->
  <div class="grid cols-4" style="margin-top:14px;">
    <div class="card p">
      <div class="statNum">{{ $stats['total_offers'] }}</div>
      <div class="statLbl">Total Offers Posted</div>
    </div>
    <div class="card p">
      <div class="statNum">{{ $stats['open_offers'] }}</div>
      <div class="statLbl">Open</div>
    </div>
    <div class="card p">
      <div class="statNum">{{ $stats['filled_offers'] }}</div>
      <div class="statLbl">Filled</div>
    </div>
    <div class="card p">
      <div class="statNum">{{ $stats['total_applications'] }}</div>
      <div class="statLbl">Applications Received</div>
    </div>
  </div>

  <!-- Main Content Grid -->
  <div class="grid cols-2" style="margin-top:14px;gap:14px;">
    
    <!-- Left: My Tuition Offers -->
    <div class="card p">
      <div class="spread" style="margin-bottom:14px;">
        <div style="font-weight:950;color:var(--ink);">My Tuition Offers</div>
        <a class="btn primary" href="{{ route('parent.offers.create') }}">Post New Offer</a>
      </div>

      @if($myOffers->count() > 0)
        <div class="stack" style="gap:12px;">
          @foreach($myOffers as $offer)
            <div class="card p" style="box-shadow:none;border:1px solid var(--line);">
              <div class="spread">
                @if($offer->status === 'open')
                  <span class="badge" style="background:rgba(0,200,120,.1);color:#00c878;">Open</span>
                @elseif($offer->status === 'filled')
                  <span class="badge" style="background:rgba(0,100,255,.1);color:#0064ff;">Filled</span>
                @else
                  <span class="badge" style="background:rgba(100,100,100,.1);color:#666;">Closed</span>
                @endif
                <span class="badge">{{ $offer->posted_at->diffForHumans() }}</span>
              </div>
              
              <h4 style="margin:8px 0;">{{ $offer->title }}</h4>
              
              <div style="font-size:13px;color:var(--muted);margin:6px 0;">
                <div>📍 {{ $offer->area->name ?? 'N/A' }}</div>
                <div>📚 {{ $offer->student_class }} • {{ is_array($offer->subjects) ? implode(', ', $offer->subjects) : $offer->subjects }}</div>
                <div>💰 {{ number_format($offer->salary) }} Tk/month</div>
                <div>✉️ {{ $offer->getApplicationsCount() }} applications</div>
              </div>

              <div class="row" style="margin-top:10px;gap:8px;">
                <a class="btn primary" href="{{ route('parent.offers.show', $offer->id) }}">View Details</a>
                <a class="btn" href="{{ route('parent.offers.edit', $offer->id) }}">Edit</a>
              </div>
            </div>
          @endforeach
        </div>
      @else
        <div style="text-align:center;padding:30px;color:var(--muted);">
          <div style="font-size:40px;margin-bottom:10px;">📝</div>
          <div style="font-weight:700;margin-bottom:6px;">No tuition offers yet</div>
          <div style="font-size:13px;margin-bottom:14px;">Post your first tuition offer to find the perfect tutor</div>
          <a class="btn primary" href="{{ route('parent.offers.create') }}">Post Tuition Offer</a>
        </div>
      @endif
    </div>

    <!-- Right: Recent Applications -->
    <div class="card p">
      <div class="spread" style="margin-bottom:14px;">
        <div style="font-weight:950;color:var(--ink);">Recent Applications</div>
        <a class="btn" href="{{ route('parent.applications.index') }}">View All</a>
      </div>

      @if($recentApplications->count() > 0)
        <div class="stack" style="gap:12px;">
          @foreach($recentApplications as $app)
            <div class="card p" style="box-shadow:none;border:1px solid var(--line);">
              <div class="spread">
                @if($app->status === 'pending')
                  <span class="badge" style="background:rgba(255,140,0,.1);color:#ff8c00;">Pending Review</span>
                @elseif($app->status === 'accepted')
                  <span class="badge" style="background:rgba(0,200,120,.1);color:#00c878;">Accepted</span>
                @else
                  <span class="badge" style="background:rgba(255,0,0,.1);color:#ff0000;">Rejected</span>
                @endif
                <span style="font-size:12px;color:var(--muted);">{{ $app->applied_at->diffForHumans() }}</span>
              </div>
              
              <div style="margin-top:8px;">
                <div style="font-weight:700;">{{ $app->tutor->name }}</div>
                <div style="font-size:13px;color:var(--muted);margin-top:4px;">
                  Applied to: {{ $app->tuitionOffer->title }}
                </div>
                @if($app->tutor->tutorProfile)
                  <div style="font-size:13px;color:var(--muted);">
                    📚 {{ $app->tutor->tutorProfile->experience_years }} years exp
                  </div>
                @endif
              </div>

              <div class="row" style="margin-top:10px;gap:8px;">
                <a class="btn primary" href="{{ route('parent.applications.show', $app->id) }}">Review Application</a>
                @if($app->isPending())
                  <form method="POST" action="{{ route('parent.applications.accept', $app->id) }}" style="display:inline;">
                    @csrf
                    <button class="btn" type="submit">Accept</button>
                  </form>
                @endif
              </div>
            </div>
          @endforeach
        </div>
      @else
        <div style="text-align:center;padding:20px;color:var(--muted);">
          No applications received yet.
        </div>
      @endif
    </div>

  </div>

  <!-- Quick Actions -->
  <div class="card p" style="margin-top:14px;">
    <div style="font-weight:950;color:var(--ink);margin-bottom:10px;">Quick Actions</div>
    <div class="row" style="gap:10px;">
      <a class="btn primary" href="{{ route('parent.offers.create') }}">Post New Offer</a>
      <a class="btn" href="{{ route('parent.offers.index') }}">Manage My Offers</a>
      <a class="btn" href="{{ route('tutors.index') }}">Browse Tutors</a>
      <a class="btn" href="{{ route('parent.applications.index') }}">All Applications</a>
      <a class="btn ghost" href="{{ route('home') }}">Back to Home</a>
    </div>
  </div>

</section>
@endsection