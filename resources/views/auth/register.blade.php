@extends('layouts.app')
@section('title','Register')
@section('content')
  <section class="section">
    <h2>Register</h2>
    <div class="row" style="margin-top:12px;">
      <a class="btn primary" href="{{ route('register.tutor') }}">Register as Tutor</a>
      <a class="btn" href="{{ route('register.parent') }}">Register as Parent</a>
    </div>
  </section>
@endsection
