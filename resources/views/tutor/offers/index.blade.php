@extends('layouts.app')
@section('title', 'Browse Tuition Offers')

@section('content')
<section class="section">
  <div class="sectionTitle">
    <div>
      <h2>BROWSE TUITION OFFERS</h2>
      <div class="hint">Find the perfect tuition opportunity</div>
    </div>
    <a class="btn" href="{{ route('tutor.dashboard') }}">Back to Dashboard</a>
  </div>

  <!-- Filters -->
  <div class="card p" style="margin-top:14px;">
    <form method="GET" action="{{ route('tutor.offers.index') }}" class="stack" style="gap:12px;">
      
      <div class="grid cols-4" style="gap:12px;">
        <!-- Search -->
        <div>
          <label class="hint" style="display:block;margin-bottom:6px;">Search</label>
          <input class="input" name="search" value="{{ request('search') }}" placeholder="Title, subject, location...">
        </div>

        <!-- Category -->
        <div>
          <label class="hint" style="display:block;margin-bottom:6px;">Category</label>
          <select class="select" name="category">
            <option value="">All Categories</option>
            @foreach($categories as $cat)
              <option value="{{ $cat->id }}" {{ request('category') == $cat->id ? 'selected' : '' }}>
                {{ $cat->name }}
              </option>
            @endforeach
          </select>
        </div>

        <!-- Area -->
        <div>
          <label class="hint" style="display:block;margin-bottom:6px;">Area</label>
          <select class="select" name="area">
            <option value="">All Areas</option>
            @foreach($areas as $area)
              <option value="{{ $area->id }}" {{ request('area') == $area->id ? 'selected' : '' }}>
                {{ $area->name }}
              </option>
            @endforeach
          </select>
        </div>

        <!-- Tuition Type -->
        <div>
          <label class="hint" style="display:block;margin-bottom:6px;">Type</label>
          <select class="select" name="tuition_type">
            <option value="">All Types</option>
            <option value="home" {{ request('tuition_type') == 'home' ? 'selected' : '' }}>Home</option>
            <option value="online" {{ request('tuition_type') == 'online' ? 'selected' : '' }}>Online</option>
            <option value="center" {{ request('tuition_type') == 'center' ? 'selected' : '' }}>Center</option>
          </select>
        </div>
      </div>

      <div class="grid cols-3" style="gap:12px;">
        <!-- Min Salary -->
        <div>
          <label class="hint" style="display:block;margin-bottom:6px;">Min Salary (Tk)</label>
          <input class="input" type="number" name="min_salary" value="{{ request('min_salary') }}" placeholder="e.g., 5000">
        </div>

        <!-- Max Salary -->
        <div>
          <label class="hint" style="display:block;margin-bottom:6px;">Max Salary (Tk)</label>
          <input class="input" type="number" name="max_salary" value="{{ request('max_salary') }}" placeholder="e.g., 15000">
        </div>

        <!-- Submit -->
        <div style="display:flex;align-items:flex-end;">
          <button class="btn primary" type="submit" style="width:100%;">Apply Filters</button>
        </div>
      </div>

      @if(request()->hasAny(['search', 'category', 'area', 'tuition_type', 'min_salary', 'max_salary']))
        <a class="btn ghost" href="{{ route('tutor.offers.index') }}" style="width:fit-content;">Clear Filters</a>
      @endif
    </form>
  </div>

  <!-- Results Count -->
  <div style="margin-top:14px;color:var(--muted);font-size:14px;">
    Showing {{ $offers->count() }} of {{ $offers->total() }} offers
  </div>

  <!-- Offers Grid -->
  @if($offers->count() > 0)
    <div class="grid cols-2" style="margin-top:14px;gap:14px;">
      @foreach($offers as $offer)
        <div class="card p">
          <!-- Header -->
          <div class="spread">
            <div class="row" style="gap:8px;">
              <span class="badge">{{ strtoupper($offer->tuition_type) }}</span>
              @if($offer->category)
                <span class="badge">{{ $offer->category->name }}</span>
              @endif
            </div>
            <span class="badge">{{ $offer->posted_at->diffForHumans() }}</span>
          </div>

          <!-- Title -->
          <h3 style="margin:12px 0 8px;">{{ $offer->title }}</h3>

          <!-- Details -->
          <div class="stack" style="gap:6px;font-size:13px;color:var(--muted);">
            @if($offer->area)
              <div>📍 {{ $offer->area->name }}</div>
            @endif
            <div>📚 {{ $offer->student_class }}</div>
            <div>📖 {{ is_array($offer->subjects) ? implode(', ', $offer->subjects) : $offer->subjects }}</div>
            <div>👤 Tutor: {{ ucfirst($offer->tutor_gender_preference) }}</div>
            <div>📅 {{ $offer->days_per_week }} days/week</div>
          </div>

          <!-- Salary -->
          <div style="margin-top:12px;padding:12px;background:rgba(0,200,120,.06);border-radius:8px;">
            <div style="font-size:12px;color:var(--muted);">Monthly Salary</div>
            <div style="font-size:20px;font-weight:950;color:#00c878;">{{ number_format($offer->salary) }} Tk</div>
          </div>

          <!-- Description Preview -->
          @if($offer->description)
            <div style="margin-top:10px;font-size:13px;color:var(--text);line-height:1.5;">
              {{ Str::limit($offer->description, 120) }}
            </div>
          @endif

          <!-- Actions -->
          <div class="row" style="margin-top:14px;gap:8px;">
            <a class="btn primary" href="{{ route('tutor.offers.show', $offer->id) }}">View Details</a>
            <a class="btn" href="{{ route('tutor.offers.show', $offer->id) }}#apply">Apply Now</a>
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
      <div style="font-size:48px;margin-bottom:14px;">🔍</div>
      <h3 style="margin-bottom:8px;">No offers found</h3>
      <p style="color:var(--muted);margin-bottom:20px;">
        @if(request()->hasAny(['search', 'category', 'area', 'tuition_type', 'min_salary', 'max_salary']))
          Try adjusting your filters to see more results.
        @else
          There are no tuition offers available at the moment.
        @endif
      </p>
      @if(request()->hasAny(['search', 'category', 'area', 'tuition_type', 'min_salary', 'max_salary']))
        <a class="btn primary" href="{{ route('tutor.offers.index') }}">Clear Filters</a>
      @endif
    </div>
  @endif

</section>
@endsection