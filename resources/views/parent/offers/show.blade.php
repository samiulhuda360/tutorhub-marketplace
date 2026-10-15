@extends('layouts.app')
@section('title', 'Tuition Offer Details')

@section('content')
<section class="section">
  <div class="sectionTitle">
    <div>
      <h2>OFFER DETAILS</h2>
      <div class="hint">Manage your tuition offer and review applications</div>
    </div>
    <a class="btn" href="{{ route('parent.offers.index') }}">Back to My Offers</a>
  </div>

  @if(session('success'))
    <div class="card p" style="margin-top:12px;border-color:rgba(0,200,120,.35);background:rgba(0,200,120,.06);">
      {{ session('success') }}
    </div>
  @endif

  @if(session('error'))
    <div class="card p" style="margin-top:12px;border-color:rgba(255,0,0,.35);background:rgba(255,0,0,.06);">
      {{ session('error') }}
    </div>
  @endif

  <div class="grid cols-3" style="margin-top:14px;gap:14px;">
    
    <!-- Left: Offer Details (2 columns) -->
    <div style="grid-column:span 2;">
      <div class="card p">
        <!-- Header -->
        <div class="spread" style="margin-bottom:14px;">
          <div class="row" style="gap:8px;">
            <span class="badge">{{ strtoupper($offer->tuition_type) }}</span>
            @if($offer->category)
              <span class="badge">{{ $offer->category->name }}</span>
            @endif
            @if($offer->status === 'open')
              <span class="badge" style="background:rgba(0,200,120,.1);color:#00c878;">OPEN</span>
            @elseif($offer->status === 'filled')
              <span class="badge" style="background:rgba(0,100,255,.1);color:#0064ff;">FILLED</span>
            @else
              <span class="badge" style="background:rgba(100,100,100,.1);color:#666;">CLOSED</span>
            @endif
          </div>
          <span style="font-size:12px;color:var(--muted);">Posted {{ $offer->posted_at->diffForHumans() }}</span>
        </div>

        <!-- Title -->
        <h2 style="margin-bottom:14px;">{{ $offer->title }}</h2>

        <!-- Salary Highlight -->
        <div style="padding:16px;background:rgba(0,200,120,.06);border-radius:8px;margin-bottom:20px;">
          <div style="font-size:13px;color:var(--muted);margin-bottom:4px;">Offering</div>
          <div style="font-size:28px;font-weight:950;color:#00c878;">{{ number_format($offer->salary) }} Tk/month</div>
        </div>

        <!-- Description -->
        <div>
          <h3 style="margin-bottom:10px;">Description</h3>
          <p style="color:var(--text);line-height:1.6;white-space:pre-line;">{{ $offer->description }}</p>
        </div>

        <!-- Details Grid -->
        <div style="margin-top:24px;">
          <h3 style="margin-bottom:14px;">Details</h3>
          
          <div class="grid cols-2" style="gap:16px;">
            <div>
              <div style="font-size:12px;color:var(--muted);margin-bottom:4px;">Student Class</div>
              <div style="font-weight:700;">{{ $offer->student_class }}</div>
            </div>

            <div>
              <div style="font-size:12px;color:var(--muted);margin-bottom:4px;">Subjects</div>
              <div style="font-weight:700;">{{ is_array($offer->subjects) ? implode(', ', $offer->subjects) : $offer->subjects }}</div>
            </div>

            <div>
              <div style="font-size:12px;color:var(--muted);margin-bottom:4px;">Days Per Week</div>
              <div style="font-weight:700;">{{ $offer->days_per_week }} days</div>
            </div>

            <div>
              <div style="font-size:12px;color:var(--muted);margin-bottom:4px;">Tutor Preference</div>
              <div style="font-weight:700;">{{ ucfirst($offer->tutor_gender_preference) }}</div>
            </div>

            <div>
              <div style="font-size:12px;color:var(--muted);margin-bottom:4px;">Area</div>
              <div style="font-weight:700;">{{ $offer->area->name ?? 'N/A' }}</div>
            </div>

            <div>
              <div style="font-size:12px;color:var(--muted);margin-bottom:4px;">Location</div>
              <div style="font-weight:700;">{{ $offer->location }}</div>
            </div>
          </div>
        </div>

        <!-- Actions -->
        <div style="margin-top:24px;padding-top:20px;border-top:1px solid var(--line);">
          <div class="row" style="gap:10px;">
            <a class="btn" href="{{ route('parent.offers.edit', $offer->id) }}">Edit Offer</a>
            
            <form method="POST" action="{{ route('parent.offers.destroy', $offer->id) }}" 
                  onsubmit="return confirm('Are you sure? This will delete the offer and all applications.')" 
                  style="display:inline;">
              @csrf
              @method('DELETE')
              <button class="btn ghost" type="submit">Delete Offer</button>
            </form>
          </div>
        </div>
      </div>

      <!-- Applications Received -->
      <div class="card p" style="margin-top:14px;">
        <h3 style="margin-bottom:14px;">Applications Received ({{ $offer->applications->count() }})</h3>

        @if($offer->applications->count() > 0)
          <div class="stack" style="gap:14px;">
            @foreach($offer->applications as $app)
              <div class="card p" style="box-shadow:none;border:1px solid var(--line);">
                <div class="spread" style="margin-bottom:10px;">
                  <div>
                    <div style="font-weight:700;font-size:16px;margin-bottom:4px;">{{ $app->tutor->name }}</div>
                    <div style="font-size:12px;color:var(--muted);">{{ $app->tutor->tutor_code }} · <a href="{{ $app->tutor->profileUrl() }}">View profile</a>{{ $app->tutor->tutorProfile?->is_verified ? ' · Verified' : '' }}</div>
                  </div>
                  
                  @if($app->status === 'pending')
                    <span class="badge" style="background:rgba(255,140,0,.1);color:#ff8c00;">Pending</span>
                  @elseif($app->status === 'accepted')
                    <span class="badge" style="background:rgba(0,200,120,.1);color:#00c878;">Accepted</span>
                  @else
                    <span class="badge" style="background:rgba(255,0,0,.1);color:#ff0000;">Rejected</span>
                  @endif
                </div>

                <!-- Tutor Profile Info -->
                @if($app->tutor->tutorProfile)
                  <div class="grid cols-3" style="gap:12px;margin:12px 0;font-size:13px;">
                    <div>
                      <div style="color:var(--muted);">Experience</div>
                      <div style="font-weight:700;">{{ $app->tutor->tutorProfile->experience_years }} years</div>
                    </div>
                    <div>
                      <div style="color:var(--muted);">Education</div>
                      <div style="font-weight:700;">{{ Str::limit($app->tutor->tutorProfile->education, 30) }}</div>
                    </div>
                    <div>
                      <div style="color:var(--muted);">Expected Salary</div>
                      <div style="font-weight:700;color:#00c878;">{{ number_format($app->tutor->tutorProfile->hourly_rate) }} Tk</div>
                    </div>
                  </div>
                @endif

                <!-- Cover Letter Preview -->
                <div style="margin:12px 0;padding:12px;background:var(--bg);border-radius:8px;">
                  <div style="font-size:12px;color:var(--muted);margin-bottom:6px;">Cover Letter:</div>
                  <div style="font-size:13px;color:var(--text);line-height:1.5;">
                    {{ Str::limit($app->cover_letter, 150) }}
                  </div>
                </div>

                <div style="font-size:12px;color:var(--muted);margin-bottom:12px;">
                  Applied {{ $app->applied_at->diffForHumans() }}
                </div>

                <!-- Actions -->
                <div class="row" style="gap:8px;">
                  <a class="btn primary" href="{{ route('parent.applications.show', $app->id) }}">View Full Application</a>
                  
                  @if($app->isPending())
                    <form method="POST" action="{{ route('parent.applications.accept', $app->id) }}" style="display:inline;">
                      @csrf
                      <button class="btn" type="submit">Accept</button>
                    </form>
                    
                    <form method="POST" action="{{ route('parent.applications.reject', $app->id) }}" style="display:inline;">
                      @csrf
                      <button class="btn ghost" type="submit">Reject</button>
                    </form>
                  @endif
                </div>
              </div>
            @endforeach
          </div>
        @else
          <div style="text-align:center;padding:40px 20px;color:var(--muted);">
            <div style="font-size:40px;margin-bottom:10px;">📭</div>
            <div>No applications received yet.</div>
          </div>
        @endif
      </div>

      @include('parent.offers._matches')
    </div>

    <!-- Right: Sidebar -->
    <div>
      <!-- Quick Stats -->
      <div class="card p" style="margin-bottom:14px;">
        <h4 style="margin-bottom:10px;">Quick Stats</h4>
        
        <div class="stack" style="gap:8px;">
          <div class="spread">
            <span style="font-size:13px;color:var(--muted);">Applications</span>
            <span style="font-weight:700;">{{ $offer->applications->count() }}</span>
          </div>
          
          <div class="spread">
            <span style="font-size:13px;color:var(--muted);">Pending</span>
            <span style="font-weight:700;color:#ff8c00;">{{ $offer->applications->where('status', 'pending')->count() }}</span>
          </div>
          
          <div class="spread">
            <span style="font-size:13px;color:var(--muted);">Accepted</span>
            <span style="font-weight:700;color:#00c878;">{{ $offer->applications->where('status', 'accepted')->count() }}</span>
          </div>
          
          <div class="spread">
            <span style="font-size:13px;color:var(--muted);">Status</span>
            @if($offer->status === 'open')
              <span class="badge" style="background:rgba(0,200,120,.1);color:#00c878;">Open</span>
            @elseif($offer->status === 'filled')
              <span class="badge" style="background:rgba(0,100,255,.1);color:#0064ff;">Filled</span>
            @else
              <span class="badge" style="background:rgba(100,100,100,.1);color:#666;">Closed</span>
            @endif
          </div>
        </div>
      </div>

      <!-- Quick Actions -->
      <div class="card p">
        <div class="stack" style="gap:8px;">
          <a class="btn" href="{{ route('parent.offers.edit', $offer->id) }}" style="width:100%;">Edit Offer</a>
          <a class="btn" href="{{ route('parent.applications.index', ['offer' => $offer->id]) }}" style="width:100%;">All Applications</a>
          <a class="btn ghost" href="{{ route('parent.dashboard') }}" style="width:100%;">Back to Dashboard</a>
        </div>
      </div>
    </div>

  </div>
</section>
@endsection