@extends('layouts.app')
@section('title','Area')
@section('content')
  <section class="section">
    <h2>Area: {{ $slug }}</h2>
    <p>Later: show tutors filtered by this area.</p>
    <a class="btn primary" href="{{ route('tutors.index', ['area'=>$slug]) }}">Search Tutors</a>
  </section>
@endsection
