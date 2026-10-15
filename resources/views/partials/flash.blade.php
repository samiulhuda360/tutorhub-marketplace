@if (session('ok'))
  <article style="background:#eaffea;border:1px solid #a8e6a8;">
    {{ session('ok') }}
  </article>
@endif

@if ($errors->any())
  <article style="background:#ffecec;border:1px solid #f2a0a0;">
    <strong>Fix these:</strong>
    <ul>
      @foreach ($errors->all() as $e)
        <li>{{ $e }}</li>
      @endforeach
    </ul>
  </article>
@endif
