@extends('layouts.app')
@section('title','Category')
@section('content')
  <section class="section">
    <h2>Category: {{ $slug }}</h2>
    <p>Later: show tutors filtered by this category.</p>
    <a class="btn primary" href="{{ route('tutors.index', ['category'=>$slug]) }}">Search Tutors</a>
  </section>
@endsection
