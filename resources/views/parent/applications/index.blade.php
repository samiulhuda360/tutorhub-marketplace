@extends('layouts.app')
@section('title', 'Applications | TutorHub')

@section('content')
<section class="section">
  <div class="sectionTitle">
    <div>
      <h2>APPLICATIONS</h2>
      <div class="hint">Tutors who applied to your offers</div>
    </div>
    <a class="btn" href="{{ route('parent.dashboard') }}">Dashboard</a>
  </div>

  @if(session('success'))<div class="flash ok">{{ session('success') }}</div>@endif
  @if(session('error'))<div class="flash err">{{ session('error') }}</div>@endif

  <form method="GET" class="card p row" style="margin-top:14px;gap:10px;flex-wrap:wrap;">
    <select class="select" name="offer" style="max-width:340px;">
      <option value="">All offers</option>
      @foreach($offers as $o)
        <option value="{{ $o->id }}" @selected((string) request('offer') === (string) $o->id)>{{ $o->title }}</option>
      @endforeach
    </select>
    <select class="select" name="status" style="max-width:180px;">
      <option value="">Any status</option>
      @foreach(['pending', 'accepted', 'rejected'] as $st)
        <option value="{{ $st }}" @selected(request('status') === $st)>{{ ucfirst($st) }}</option>
      @endforeach
    </select>
    <button class="btn primary" type="submit">Filter</button>
  </form>

  <div class="card p" style="margin-top:14px;">
    @if($applications->isEmpty())
      <p class="small" style="margin:0;">No applications yet. Open one of your offers to see tutors who fit and invite them.</p>
    @else
      <table class="table">
        <thead><tr><th>Tutor</th><th>Offer</th><th>Applied</th><th>Status</th><th></th></tr></thead>
        <tbody>
          @foreach($applications as $app)
            <tr>
              <td><strong>{{ $app->tutor->name }}</strong><div class="small">{{ $app->tutor->tutor_code }} · {{ $app->tutor->tutorProfile?->experience_years }} years</div></td>
              <td>{{ $app->tuitionOffer->title }}</td>
              <td>{{ optional($app->applied_at)->diffForHumans() }}</td>
              <td><span class="badge {{ $app->status === 'accepted' ? 'good' : ($app->status === 'rejected' ? 'warn' : '') }}">{{ ucfirst($app->status) }}</span></td>
              <td><a class="btn" href="{{ route('parent.applications.show', $app) }}">Open</a></td>
            </tr>
          @endforeach
        </tbody>
      </table>
      <div class="pagination-wrap">{{ $applications->withQueryString()->links() }}</div>
    @endif
  </div>
</section>
@endsection
