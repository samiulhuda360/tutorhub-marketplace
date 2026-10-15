@extends('layouts.app')
@section('title', 'My Tuition Offers')

@section('content')
<section class="section">
  <div class="sectionTitle">
    <div>
      <h2>MY TUITION OFFERS</h2>
      <div class="hint">Manage your posted tuition offers</div>
    </div>
    <div class="row" style="gap:10px;">
      <a class="btn" href="{{ route('parent.dashboard') }}">Back to Dashboard</a>
      <a class="btn primary" href="{{ route('parent.offers.create') }}">Post New Offer</a>
    </div>
  </div>

  @if(session('success'))
    <div class="card p" style="margin-top:12px;border-color:rgba(0,200,120,.35);background:rgba(0,200,120,.06);">
      {{ session('success') }}
    </div>
  @endif

  <!-- Offers Count -->
  <div style="margin-top:14px;color:var(--muted);font-size:14px;">
    Total: {{ $offers->total() }} offer{{ $offers->total() != 1 ? 's' : '' }}
  </div>

  <!-- Offers List -->
  @if($offers->count() > 0)
    <div class="stack" style="margin-top:14px;gap:14px;">
      @foreach($offers as $offer)
        <div class="card p">
          <div class="spread" style="margin-bottom:12px;">
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
            <span class="badge">{{ $offer->posted_at->diffForHumans() }}</span>
          </div>

          <h3 style="margin-bottom:10px;">{{ $offer->title }}</h3>

          <div class="grid cols-4" style="gap:12px;margin-bottom:14px;">
            <div>
              <div style="font-size:12px;color:var(--muted);margin-bottom:4px;">Location</div>
              <div style="font-weight:700;font-size:14px;">{{ $offer->area->name ?? 'N/A' }}</div>
            </div>

            <div>
              <div style="font-size:12px;color:var(--muted);margin-bottom:4px;">Class</div>
              <div style="font-weight:700;font-size:14px;">{{ $offer->student_class }}</div>
            </div>

            <div>
              <div style="font-size:12px;color:var(--muted);margin-bottom:4px;">Salary</div>
              <div style="font-weight:700;font-size:14px;color:#00c878;">{{ number_format($offer->salary) }} Tk</div>
            </div>

            <div>
              <div style="font-size:12px;color:var(--muted);margin-bottom:4px;">Applications</div>
              <div style="font-weight:700;font-size:14px;">{{ $offer->getApplicationsCount() }}</div>
            </div>
          </div>

          <div style="font-size:13px;color:var(--text);margin-bottom:14px;">
            <strong>Subjects:</strong> {{ is_array($offer->subjects) ? implode(', ', $offer->subjects) : $offer->subjects }}
          </div>

          <div class="row" style="gap:8px;">
            <a class="btn primary" href="{{ route('parent.offers.show', $offer->id) }}">View Details</a>
            <a class="btn" href="{{ route('parent.offers.edit', $offer->id) }}">Edit</a>
            
            <form method="POST" action="{{ route('parent.offers.destroy', $offer->id) }}" 
                  onsubmit="return confirm('Are you sure you want to delete this offer?')" 
                  style="display:inline;">
              @csrf
              @method('DELETE')
              <button class="btn ghost" type="submit">Delete</button>
            </form>
          </div>
        </div>
      @endforeach
    </div>

    <!-- Pagination -->
    <div style="margin-top:20px;">
      {{ $offers->links() }}
    </div>
  @else
    <!-- Empty State -->
    <div class="card p" style="margin-top:14px;text-align:center;padding:60px 20px;">
      <div style="font-size:48px;margin-bottom:14px;">📝</div>
      <h3 style="margin-bottom:8px;">No tuition offers yet</h3>
      <p style="color:var(--muted);margin-bottom:20px;">
        Post your first tuition offer to find the perfect tutor for your child.
      </p>
      <a class="btn primary" href="{{ route('parent.offers.create') }}">Post Your First Offer</a>
    </div>
  @endif

</section>
@endsection