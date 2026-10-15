@extends('layouts.app')
@section('title','Login')

@section('content')
<section class="section">
  <h2>Login</h2>

  @if($errors->any())
    <div class="card p" style="margin-top:12px;border-color:rgba(255,0,0,.35);background:rgba(255,0,0,.06);">
      <ul style="margin:0;padding-left:18px;">
        @foreach($errors->all() as $e)<li>{{ $e }}</li>@endforeach
      </ul>
    </div>
  @endif

  <form method="POST" action="{{ route('login.submit') }}" class="stack" style="gap:12px;">
    @csrf

    <div>
      <div class="hint">Email</div>
      <input class="input" type="email" name="email" value="{{ old('email') }}" required>
    </div>

    <div>
      <div class="hint">Password</div>
      <input class="input" type="password" name="password" required>
    </div>

    <label class="row" style="gap:8px;font-size:13px;color:var(--muted);">
      <input type="checkbox" name="remember" value="1"> Remember me
    </label>

    <button class="btn primary" type="submit">Login</button>
  </form>

  <div style="margin-top:12px;text-align:center;">
    <a href="{{ route('register') }}" class="hint">Don’t have an account? Register here</a>
  </div>
</section>
@endsection
