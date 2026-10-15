@auth
  @php($navUser = auth()->user())
  <a class="btn" href="{{ $navUser->isAdmin() ? route('admin.dashboard') : ($navUser->role === 'tutor' ? route('tutor.dashboard') : route('parent.dashboard')) }}">Dashboard</a>
  <form method="POST" action="{{ $navUser->isAdmin() ? route('admin.logout') : route('logout') }}" style="display:inline;">
    @csrf
    <button class="btn ghost" type="submit">Log out</button>
  </form>
@else
  <a class="btn ghost" href="{{ route('login') }}">Login</a>
@endauth
