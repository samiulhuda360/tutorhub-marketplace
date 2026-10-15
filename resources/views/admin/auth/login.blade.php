@extends('layouts.app')
@section('title','Admin Login')

@section('content')
<section class="section">
  <div class="card p" style="max-width:520px;margin:0 auto;">
    <h2 style="margin:0 0 6px;">Admin Login</h2>
    <div style="color:var(--muted);margin-bottom:14px;font-size:13px;">
      Use your admin email & password to access the panel.
    </div>

    @if($errors->any())
      <div class="card p" style="border-color:rgba(255,0,0,.35);background:rgba(255,0,0,.06);margin-bottom:12px;">
        <div style="font-weight:900;margin-bottom:6px;">Login failed</div>
        <ul style="margin:0;padding-left:18px;">
          @foreach($errors->all() as $e)<li>{{ $e }}</li>@endforeach
        </ul>
      </div>
    @endif

    <form method="POST" action="{{ route('admin.login.submit') }}" class="stack" style="gap:10px;">
      @csrf
      <input class="input" name="email" value="{{ old('email') }}" placeholder="Email" required>
      <input class="input" type="password" name="password" placeholder="Password" required>

      <label class="row" style="gap:8px;font-size:13px;color:var(--muted);">
        <input type="checkbox" name="remember" value="1"> Remember me
      </label>

      <button class="btn primary" type="submit" style="width:100%;">Login</button>
<a class="btn ghost" href="{{ route('home') }}" style="width:100%;text-align:center;">Back to Home</a>
    </form>
  </div>
</section>
@endsection
