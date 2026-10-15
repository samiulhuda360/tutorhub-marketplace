@extends('layouts.app')
@section('title','Register as Parent')

@section('content')
<section class="section">
  <h2>Register as Parent</h2>

  @if($errors->any())
    <div class="card p" style="margin-top:12px;border-color:rgba(255,0,0,.35);background:rgba(255,0,0,.06);">
      <ul style="margin:0;padding-left:18px;">
        @foreach($errors->all() as $e)<li>{{ $e }}</li>@endforeach
      </ul>
    </div>
  @endif

  <form method="POST" action="{{ route('register.parent.submit') }}" class="stack" style="gap:12px;">
    @csrf

    <div>
      <div class="hint">Full Name</div>
      <input class="input" type="text" name="name" value="{{ old('name') }}" required>
    </div>

    <div>
      <div class="hint">Email</div>
      <input class="input" type="email" name="email" value="{{ old('email') }}" required>
    </div>

    <div>
      <div class="hint">Password</div>
      <input class="input" type="password" name="password" required>
    </div>

    <div>
      <div class="hint">Confirm Password</div>
      <input class="input" type="password" name="password_confirmation" required>
    </div>

    <button class="btn primary" type="submit">Register</button>
  </form>
</section>
@endsection
