@extends('layouts.app')
@section('title','Blog Post')
@section('content')
  <section class="section">
    <h2>Post: {{ $slug }}</h2>
    <p>Later: full blog content.</p>
  </section>
@endsection
