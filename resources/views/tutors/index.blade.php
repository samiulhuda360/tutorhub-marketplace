@extends('layouts.app')
@section('title', 'Find Tutors | TutorHub')

@section('content')
  <section class="section">
    <div class="spread">
      <div class="stack" style="gap:6px;">
        <h1 style="margin:0;">Find Tutors</h1>
        <div class="small">{{ $tutors->total() }} tutors in Dhaka. Filter by subject, class, area and more.</div>
      </div>
      @auth
        @if(auth()->user()->role === 'parent')
          <a class="btn primary" href="{{ route('parent.offers.create') }}">Post a Tuition Job</a>
        @endif
      @else
        <a class="btn primary" href="{{ route('register.parent') }}">Post a Tuition Job</a>
      @endauth
    </div>

    <div class="grid cols-2" style="margin-top:16px;grid-template-columns:280px 1fr;align-items:start;">
      <aside class="card p" style="position:sticky;top:84px;">
        <form method="GET" class="stack" style="gap:12px;">
          <div>
            <label class="small">Search</label>
            <input class="input" name="q" value="{{ request('q') }}" placeholder="Name, subject, university">
          </div>
          <div>
            <label class="small">Subject</label>
            <select class="select" name="subject">
              <option value="">Any subject</option>
              @foreach($subjects as $s)
                <option value="{{ $s }}" @selected(request('subject') === $s)>{{ $s }}</option>
              @endforeach
            </select>
          </div>
          <div>
            <label class="small">Class</label>
            <select class="select" name="class">
              <option value="">Any class</option>
              @foreach($classes as $c)
                <option value="{{ $c }}" @selected(request('class') === $c)>{{ $c }}</option>
              @endforeach
            </select>
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
          <div class="grid cols-2" style="gap:8px;">
            <div>
              <label class="small">Tutor</label>
              <select class="select" name="gender">
                <option value="">Any</option>
                <option value="female" @selected(request('gender') === 'female')>Female</option>
                <option value="male" @selected(request('gender') === 'male')>Male</option>
              </select>
            </div>
            <div>
              <label class="small">Sort</label>
              <select class="select" name="sort">
                <option value="latest">Newest</option>
                <option value="experience" @selected(request('sort') === 'experience')>Experience</option>
                <option value="rate_low" @selected(request('sort') === 'rate_low')>Fee: low</option>
                <option value="rate_high" @selected(request('sort') === 'rate_high')>Fee: high</option>
              </select>
            </div>
          </div>
          <div class="row">
            <button class="btn primary" type="submit">Apply filters</button>
            <a class="btn" href="{{ route('tutors.index') }}">Reset</a>
          </div>
        </form>
      </aside>

      <section class="stack">
        @forelse($tutors as $tutor)
          @php($p = $tutor->tutorProfile)
          <article class="card p">
            <div class="tutor">
              <div class="avatar">{{ mb_substr($tutor->name, 0, 1) }}</div>
              <div class="stack" style="gap:10px;flex:1;">
                <div class="spread">
                  <div>
                    <h3 style="margin:0;">{{ $tutor->name }}</h3>
                    <div class="small">{{ $p->education }}</div>
                  </div>
                  @if($p->is_verified)
                    <span class="badge good">Verified</span>
                  @endif
                </div>
                <div class="kv">
                  <div>Subjects</div><div>{{ implode(', ', (array) $p->subjects) }}</div>
                  <div>Classes</div><div>{{ implode(', ', (array) $p->classes) }}</div>
                  <div>Experience</div><div>{{ $p->experience_years }} {{ $p->experience_years == 1 ? 'year' : 'years' }}</div>
                  <div>Fee</div><div>৳{{ number_format($p->hourly_rate) }} / month</div>
                </div>
                <div class="row">
                  <a class="btn primary" href="{{ $tutor->profileUrl() }}">View profile</a>
                  <span class="small">ID {{ $tutor->tutor_code }}</span>
                </div>
              </div>
            </div>
          </article>
        @empty
          <div class="card p">No tutors match these filters. <a href="{{ route('tutors.index') }}">Clear filters</a></div>
        @endforelse

        <div class="pagination-wrap">{{ $tutors->links() }}</div>
      </section>
    </div>
  </section>
@endsection
