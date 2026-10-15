@extends('layouts.app')
@section('title', 'Tuition Jobs in Dhaka | TutorHub')

@section('content')
  <section class="section">
    <div class="spread">
      <div class="stack" style="gap:6px;">
        <h1 style="margin:0;">Tuition Jobs</h1>
        <div class="small">{{ $offers->total() }} open offers from parents. TutorHub shares contact details once a match is confirmed.</div>
      </div>
    </div>

    <form method="GET" class="card p grid cols-4" style="margin-top:16px;gap:10px;align-items:end;">
      <div>
        <label class="small">Search</label>
        <input class="input" name="q" value="{{ request('q') }}" placeholder="Subject, class, keyword">
      </div>
      <div>
        <label class="small">Area</label>
        <select class="select" name="area">
          <option value="">All areas</option>
          @foreach($areas as $id => $name)
            <option value="{{ $id }}" @selected((string) request('area') === (string) $id)>{{ $name }}</option>
          @endforeach
        </select>
      </div>
      <div>
        <label class="small">Type</label>
        <select class="select" name="type">
          <option value="">Any</option>
          <option value="home" @selected(request('type') === 'home')>Home</option>
          <option value="online" @selected(request('type') === 'online')>Online</option>
          <option value="center" @selected(request('type') === 'center')>Coaching centre</option>
        </select>
      </div>
      <div class="row">
        <select class="select" name="sort" style="flex:1;">
          <option value="latest">Newest</option>
          <option value="salary_high" @selected(request('sort') === 'salary_high')>Salary: high</option>
          <option value="salary_low" @selected(request('sort') === 'salary_low')>Salary: low</option>
        </select>
        <button class="btn primary" type="submit">Search</button>
      </div>
    </form>

    <div class="grid cols-2" style="margin-top:14px;gap:14px;">
      @forelse($offers as $offer)
        <article class="card p stack" style="gap:10px;">
          <div class="spread">
            <div class="row" style="gap:6px;">
              <span class="badge">{{ strtoupper($offer->tuition_type) }}</span>
              @if($offer->category)<span class="badge">{{ $offer->category->name }}</span>@endif
            </div>
            <span class="small">{{ optional($offer->posted_at)->diffForHumans() }}</span>
          </div>
          <h3 style="margin:0;"><a href="{{ route('tuition_offers.show', $offer->id) }}">{{ $offer->title }}</a></h3>
          <div class="kv">
            <div>Class</div><div>{{ $offer->student_class }}</div>
            <div>Subjects</div><div>{{ implode(', ', (array) $offer->subjects) }}</div>
            <div>Area</div><div>{{ $offer->area?->name }}</div>
            <div>Salary</div><div><strong>৳{{ number_format($offer->salary) }}</strong> / month · {{ $offer->days_per_week }} days a week</div>
          </div>
          <div><a class="btn" href="{{ route('tuition_offers.show', $offer->id) }}">View job</a></div>
        </article>
      @empty
        <div class="card p">No open offers match. <a href="{{ route('tuition_offers.index') }}">Clear filters</a></div>
      @endforelse
    </div>
    <div class="pagination-wrap">{{ $offers->links() }}</div>
  </section>
@endsection
