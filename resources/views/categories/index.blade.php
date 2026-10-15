@extends('layouts.app')
@section('title', 'Subjects and Levels | TutorHub')

@section('content')
<section class="section">
  <div class="sectionTitle">
    <div>
      <h2>BROWSE BY SUBJECT OR LEVEL</h2>
      <div class="hint">Every subject and level tutors teach on TutorHub</div>
    </div>
  </div>

  <h3 style="margin-top:18px;">Subjects</h3>
  <div class="grid cols-4" style="gap:12px;">
    @foreach($subjects as $subject)
      <a class="card p row" style="gap:10px;" href="{{ route('subjects.show', $subject->slug) }}">
        <span style="font-size:22px;">{{ $subject->icon }}</span><strong>{{ $subject->name }}</strong>
      </a>
    @endforeach
  </div>

  <h3 style="margin-top:22px;">Levels</h3>
  <div class="grid cols-4" style="gap:12px;">
    @foreach($levels as $level)
      <a class="card p row" style="gap:10px;" href="{{ route('levels.show', $level->slug) }}">
        <span style="font-size:22px;">{{ $level->icon }}</span><strong>{{ $level->name }}</strong>
      </a>
    @endforeach
  </div>
</section>
@endsection
