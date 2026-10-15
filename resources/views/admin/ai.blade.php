@extends('layouts.app')
@section('title', 'AI usage - Admin')

@section('content')
<section class="section">
  <div class="sectionTitle">
    <div>
      <h2>AI USAGE</h2>
      <div class="hint">Every model call in the last 7 days: what it was for, how fast it was, and whether it worked</div>
    </div>
    <a class="btn" href="{{ route('admin.dashboard') }}">Dashboard</a>
  </div>

  <div class="grid cols-3" style="margin-top:14px;gap:12px;">
    <div class="card p">
      <div class="small">Mode</div>
      <div class="score" style="font-size:20px;">{{ $live ? 'AI on' : 'AI off (rules only)' }}</div>
      <div class="small">{{ $live ? 'Every feature falls back to rules if a call fails.' : 'Set AI_API_KEY in .env to turn the AI features on.' }}</div>
    </div>
    <div class="card p">
      <div class="small">Models</div>
      <div style="font-weight:700;">{{ $chatModel }}</div>
      <div class="small">Embeddings: {{ $embeddingModel }}</div>
    </div>
    <div class="card p">
      <div class="small">Calls this week</div>
      <div class="score">{{ $total }}</div>
      <div class="small">Repeated questions are answered from the cache.</div>
    </div>
  </div>

  <div class="card p" style="margin-top:14px;">
    <h3 style="margin-top:0;">By feature</h3>
    @if($byFeature->isEmpty())
      <p class="small" style="margin:0;">No calls yet.</p>
    @else
      <table class="table">
        <thead><tr><th>Feature</th><th>Calls</th><th>From cache</th><th>Failed</th><th>Median</th><th>p95</th><th>Tokens</th></tr></thead>
        <tbody>
          @foreach($byFeature as $row)
            <tr>
              <td><strong>{{ str_replace('_', ' ', $row['feature']) }}</strong></td>
              <td>{{ $row['calls'] }}</td>
              <td>{{ $row['cached'] }}</td>
              <td>{!! $row['failed'] ? '<span class="badge warn">'.e($row['failed']).'</span>' : '0' !!}</td>
              <td>{{ $row['p50'] !== null ? number_format($row['p50']).' ms' : '-' }}</td>
              <td>{{ $row['p95'] !== null ? number_format($row['p95']).' ms' : '-' }}</td>
              <td>{{ number_format($row['tokens']) }}</td>
            </tr>
          @endforeach
        </tbody>
      </table>
    @endif
  </div>

  @if($recentErrors->isNotEmpty())
    <div class="card p" style="margin-top:14px;">
      <h3 style="margin-top:0;">Recent failures</h3>
      <table class="table">
        <thead><tr><th>When</th><th>Feature</th><th>Error</th></tr></thead>
        <tbody>
          @foreach($recentErrors as $e)
            <tr><td>{{ \Illuminate\Support\Carbon::parse($e->created_at)->diffForHumans() }}</td><td>{{ $e->feature }}</td><td>{{ $e->error }}</td></tr>
          @endforeach
        </tbody>
      </table>
      <p class="small" style="margin-bottom:0;">Users were not affected: these requests used the rule-based fallback.</p>
    </div>
  @endif
</section>
@endsection
