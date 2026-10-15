@extends('layouts.app')
@section('title','Admin Dashboard - TutorHub')

@section('content')
<section class="admin-dashboard">
  <div class="container">

    <!-- Header -->
    <div class="dashboard-header">
      <div>
        <h1 class="dashboard-title">Admin Dashboard</h1>
        <p class="dashboard-subtitle">Manage your TutorHub platform</p>
      </div>
      <form method="POST" action="{{ route('admin.logout') }}">
        @csrf
        <button class="btn btn-secondary" type="submit">Logout</button>
      </form>
    </div>

    <!-- Stats Grid -->
    <div class="stats-grid">
      <div class="stat-card">
        <div class="stat-icon" style="background: #E0F2FE;">👨‍🏫</div>
        <div class="stat-content">
          <div class="stat-label">Total Tutors</div>
          <div class="stat-value">{{ $stats['total_tutors'] }}</div>
        </div>
      </div>

      <div class="stat-card">
        <div class="stat-icon" style="background: #CCFBF1;">👨‍👩‍👧</div>
        <div class="stat-content">
          <div class="stat-label">Total Parents</div>
          <div class="stat-value">{{ $stats['total_parents'] }}</div>
        </div>
      </div>

      <div class="stat-card">
        <div class="stat-icon" style="background: #DBEAFE;">📝</div>
        <div class="stat-content">
          <div class="stat-label">Total Jobs</div>
          <div class="stat-value">{{ $stats['total_offers'] }}</div>
        </div>
      </div>

      <div class="stat-card">
        <div class="stat-icon" style="background: #D1FAE5;">✅</div>
        <div class="stat-content">
          <div class="stat-label">Open Jobs</div>
          <div class="stat-value">{{ $stats['open_offers'] }}</div>
        </div>
      </div>

      <div class="stat-card">
        <div class="stat-icon" style="background: #FEF3C7;">📬</div>
        <div class="stat-content">
          <div class="stat-label">Total Applications</div>
          <div class="stat-value">{{ $stats['total_applications'] }}</div>
        </div>
      </div>

      <div class="stat-card">
        <div class="stat-icon" style="background: #FFE4E6;">⏳</div>
        <div class="stat-content">
          <div class="stat-label">Pending Applications</div>
          <div class="stat-value">{{ $stats['pending_applications'] }}</div>
        </div>
      </div>

      <div class="stat-card">
        <div class="stat-icon" style="background: #FEF9C3;">🤝</div>
        <div class="stat-content">
          <div class="stat-label">Pending Matches</div>
          <div class="stat-value">{{ $stats['pending_matches'] }}</div>
        </div>
      </div>

      <div class="stat-card">
        <div class="stat-icon" style="background: #D1FAE5;">💰</div>
        <div class="stat-content">
          <div class="stat-label">Total Commissions</div>
          <div class="stat-value">{{ number_format($stats['total_commissions']) }} BDT</div>
        </div>
      </div>

      <div class="stat-card">
        <div class="stat-icon" style="background: #FBCFE8;">⚠️</div>
        <div class="stat-content">
          <div class="stat-label">Pending Commissions</div>
          <div class="stat-value">{{ $stats['pending_commissions'] }}</div>
        </div>
      </div>
    </div>

    <!-- Quick Actions -->
    <div class="section-card">
      <h2 class="section-title">Quick Actions</h2>
      <div class="quick-actions-grid">
        <a href="{{ route('admin.matches.index') }}" class="action-card" style="background: linear-gradient(135deg, #ff4d2e 0%, #ff7a18 100%); color: white;">
          <div class="action-icon" style="background: rgba(255,255,255,0.2);">🤝</div>
          <div class="action-label" style="color: white;">Manage Matches</div>
          <div class="action-count" style="color: rgba(255,255,255,0.9);">
            @if($stats['pending_matches'] > 0)
              {{ $stats['pending_matches'] }} need action
            @else
              View all matches
            @endif
          </div>
        </a>

        <a href="{{ route('admin.subjects.index') }}" class="action-card">
          <div class="action-icon">📚</div>
          <div class="action-label">Manage Subjects</div>
          <div class="action-count">{{ $stats['total_subjects'] }} subjects</div>
        </a>

        <a href="{{ route('admin.ai') }}" class="action-card">
          <div class="action-icon">✨</div>
          <div class="action-label">AI Usage</div>
          <div class="action-count">{{ $stats['ai_calls_week'] }} calls this week</div>
        </a>

        <a href="{{ route('admin.areas.index') }}" class="action-card">
          <div class="action-icon">📍</div>
          <div class="action-label">Manage Areas</div>
          <div class="action-count">{{ $stats['total_areas'] }} areas</div>
        </a>

        <a href="{{ route('admin.categories.index') }}" class="action-card">
          <div class="action-icon">🏷️</div>
          <div class="action-label">Manage Categories</div>
          <div class="action-count">View all</div>
        </a>

        <a href="{{ route('admin.review') }}" class="action-card">
          <div class="action-icon">🛡️</div>
          <div class="action-label">Profiles to Review</div>
          <div class="action-count">{{ $stats['flagged_profiles'] }} flagged</div>
        </a>

        <a href="{{ route('admin.matches.index') }}" class="action-card">
          <div class="action-icon">📋</div>
          <div class="action-label">Accepted Matches</div>
          <div class="action-count">{{ $stats['pending_applications'] }} pending</div>
        </a>
      </div>
    </div>

    <!-- Recent Activity -->
    <div class="activity-grid">

      <!-- Recent Job Postings -->
      <div class="section-card">
        <h2 class="section-title">Recent Job Postings</h2>
        @if($recentOffers->count() > 0)
          <div class="activity-list">
            @foreach($recentOffers as $offer)
              <div class="activity-item">
                <div class="activity-icon">📝</div>
                <div class="activity-content">
                  <div class="activity-title">{{ $offer->title }}</div>
                  <div class="activity-meta">
                    by {{ $offer->user?->name }} • {{ $offer->created_at->diffForHumans() }}
                  </div>
                </div>
                <span class="status-badge status-{{ $offer->status }}">{{ ucfirst($offer->status) }}</span>
              </div>
            @endforeach
          </div>
          <a href="{{ route('tuition_offers.index') }}" class="view-all-link">View all job postings →</a>
        @else
          <p class="empty-state-text">No job postings yet</p>
        @endif
      </div>

      <!-- Recent Applications -->
      <div class="section-card">
        <h2 class="section-title">Recent Applications</h2>
        @if($recentApplications->count() > 0)
          <div class="activity-list">
            @foreach($recentApplications as $application)
              <div class="activity-item">
                <div class="activity-icon">👨‍🏫</div>
                <div class="activity-content">
                  <div class="activity-title">{{ $application->tutor->name }}</div>
                  <div class="activity-meta">
                    applied to "{{ Str::limit($application->tuitionOffer->title, 30) }}" • {{ $application->created_at->diffForHumans() }}
                  </div>
                </div>
                <span class="status-badge status-{{ $application->status }}">{{ ucfirst($application->status) }}</span>
              </div>
            @endforeach
          </div>
          <a href="{{ route('admin.matches.index') }}" class="view-all-link">View all matches →</a>
        @else
          <p class="empty-state-text">No applications yet</p>
        @endif
      </div>

    </div>

  </div>
</section>

<style>
.admin-dashboard {
  background: #F9FAFB;
  min-height: 100vh;
  padding: 40px 20px;
}

.container {
  max-width: 1400px;
  margin: 0 auto;
}

/* Header */
.dashboard-header {
  display: flex;
  justify-content: space-between;
  align-items: center;
  margin-bottom: 32px;
}

.dashboard-title {
  font-size: 32px;
  font-weight: 900;
  color: #111827;
  margin-bottom: 4px;
}

.dashboard-subtitle {
  color: #6B7280;
  font-size: 16px;
}

/* Stats Grid */
.stats-grid {
  display: grid;
  grid-template-columns: repeat(auto-fit, minmax(200px, 1fr));
  gap: 20px;
  margin-bottom: 32px;
}

.stat-card {
  background: white;
  border-radius: 12px;
  padding: 20px;
  display: flex;
  align-items: center;
  gap: 16px;
  box-shadow: 0 1px 3px rgba(0, 0, 0, 0.1);
  transition: all 0.2s;
}

.stat-card:hover {
  transform: translateY(-2px);
  box-shadow: 0 4px 12px rgba(0, 0, 0, 0.1);
}

.stat-icon {
  width: 56px;
  height: 56px;
  border-radius: 12px;
  display: flex;
  align-items: center;
  justify-content: center;
  font-size: 24px;
}

.stat-content {
  flex: 1;
}

.stat-label {
  font-size: 14px;
  color: #6B7280;
  margin-bottom: 4px;
}

.stat-value {
  font-size: 28px;
  font-weight: 800;
  color: #111827;
}

/* Section Card */
.section-card {
  background: white;
  border-radius: 12px;
  padding: 24px;
  box-shadow: 0 1px 3px rgba(0, 0, 0, 0.1);
  margin-bottom: 24px;
}

.section-title {
  font-size: 20px;
  font-weight: 700;
  color: #111827;
  margin-bottom: 20px;
}

/* Quick Actions */
.quick-actions-grid {
  display: grid;
  grid-template-columns: repeat(auto-fit, minmax(180px, 1fr));
  gap: 16px;
}

.action-card {
  background: #F9FAFB;
  border: 2px solid #E5E7EB;
  border-radius: 12px;
  padding: 20px;
  text-align: center;
  text-decoration: none;
  transition: all 0.2s;
}

.action-card:hover {
  border-color: #006A4E;
  background: #F0FDF4;
  transform: translateY(-2px);
}

.action-icon {
  font-size: 32px;
  margin-bottom: 12px;
}

.action-label {
  font-size: 16px;
  font-weight: 600;
  color: #111827;
  margin-bottom: 4px;
}

.action-count {
  font-size: 13px;
  color: #6B7280;
}

/* Activity Grid */
.activity-grid {
  display: grid;
  grid-template-columns: repeat(auto-fit, minmax(400px, 1fr));
  gap: 24px;
}

.activity-list {
  display: flex;
  flex-direction: column;
  gap: 12px;
}

.activity-item {
  display: flex;
  align-items: center;
  gap: 12px;
  padding: 12px;
  background: #F9FAFB;
  border-radius: 8px;
  transition: all 0.2s;
}

.activity-item:hover {
  background: #F3F4F6;
}

.activity-icon {
  width: 40px;
  height: 40px;
  background: white;
  border-radius: 8px;
  display: flex;
  align-items: center;
  justify-content: center;
  font-size: 20px;
}

.activity-content {
  flex: 1;
  min-width: 0;
}

.activity-title {
  font-weight: 600;
  color: #111827;
  font-size: 14px;
  white-space: nowrap;
  overflow: hidden;
  text-overflow: ellipsis;
}

.activity-meta {
  font-size: 12px;
  color: #6B7280;
  margin-top: 2px;
}

.status-badge {
  padding: 4px 12px;
  border-radius: 12px;
  font-size: 12px;
  font-weight: 600;
  white-space: nowrap;
}

.status-open {
  background: #DBEAFE;
  color: #1E40AF;
}

.status-pending {
  background: #FEF3C7;
  color: #92400E;
}

.status-accepted {
  background: #D1FAE5;
  color: #065F46;
}

.status-rejected {
  background: #FEE2E2;
  color: #991B1B;
}

.status-closed {
  background: #E5E7EB;
  color: #374151;
}

.view-all-link {
  display: inline-block;
  margin-top: 16px;
  color: #006A4E;
  font-weight: 600;
  font-size: 14px;
  text-decoration: none;
}

.view-all-link:hover {
  color: #004A35;
}

.empty-state-text {
  color: #9CA3AF;
  font-size: 14px;
  text-align: center;
  padding: 20px;
}

/* Buttons */
.btn {
  padding: 10px 20px;
  border-radius: 8px;
  font-weight: 600;
  border: none;
  cursor: pointer;
  text-decoration: none;
  transition: all 0.2s;
}

.btn-secondary {
  background: #E5E7EB;
  color: #374151;
}

.btn-secondary:hover {
  background: #D1D5DB;
}

/* Responsive */
@media (max-width: 768px) {
  .dashboard-header {
    flex-direction: column;
    align-items: flex-start;
    gap: 16px;
  }

  .stats-grid {
    grid-template-columns: repeat(2, 1fr);
  }

  .quick-actions-grid {
    grid-template-columns: repeat(2, 1fr);
  }

  .activity-grid {
    grid-template-columns: 1fr;
  }
}
</style>
@endsection
