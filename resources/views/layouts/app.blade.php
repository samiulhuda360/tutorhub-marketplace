<!doctype html>
<html lang="en">
<head>
  <meta charset="utf-8">
  <meta name="viewport" content="width=device-width, initial-scale=1">
  <meta name="csrf-token" content="{{ csrf_token() }}">
  <title>@yield('title', config('app.name'))</title>
  <meta name="description" content="@yield('meta_description', 'Find tutors by category and area.')">

  <link rel="stylesheet" href="{{ asset('assets/app.css') }}?v=2">
  @stack('head')
</head>
<body>
  @include('partials.nav')

  <main class="container">
    @yield('content')
    @include('partials.footer')
  </main>

  <script src="{{ asset('assets/app.js') }}?v=1" defer></script>
  <script src="{{ asset('assets/ai.js') }}?v=1" defer></script>
  @stack('scripts')
</body>
</html>
