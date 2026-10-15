@extends('layouts.app')
@section('title', $offer->title . ' | TutorHub Bangladesh')
@section('meta_description', substr($offer->description ?? 'Tuition job opportunity on TutorHub Bangladesh', 0, 155))

@section('content')

<div class="offer-detail-page">
  <div class="container-offer-detail">

    <!-- Back Button -->
    <div class="back-nav">
      <a href="{{ route('tuition_offers.index') }}" class="back-link">
        <svg width="20" height="20" fill="none" stroke="currentColor" viewBox="0 0 24 24">
          <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 19l-7-7 7-7"/>
        </svg>
        Back to Tuition Jobs
      </a>
    </div>

    <div class="offer-grid">

      <!-- Main Content -->
      <div class="offer-main-section">

        <!-- Header Card -->
        <div class="offer-header-card">
          <div class="offer-badges">
            <span class="offer-type-badge {{ strtolower(str_replace(' ', '-', $offer->tuition_type)) }}">
              {{ $offer->tuition_type }}
            </span>
            @if($offer->category)
              <span class="category-badge">
                {{ $offer->category->icon ?? '📚' }} {{ $offer->category->name }}
              </span>
            @endif
          </div>

          <h1 class="offer-title">{{ $offer->title }}</h1>

          <div class="offer-meta">
            <div class="meta-item">
              <svg class="meta-icon" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M16 7a4 4 0 11-8 0 4 4 0 018 0zM12 14a7 7 0 00-7 7h14a7 7 0 00-7-7z"/>
              </svg>
              <span>Posted {{ optional($offer->posted_at)->diffForHumans() }}</span>
            </div>
            <div class="meta-item">
              <svg class="meta-icon" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8v4l3 3m6-3a9 9 0 11-18 0 9 9 0 0118 0z"/>
              </svg>
              <span>Posted {{ $offer->posted_at ? $offer->posted_at->diffForHumans() : 'recently' }}</span>
            </div>
          </div>
        </div>

        <!-- Description -->
        @if($offer->description)
          <div class="offer-section-card">
            <h3 class="section-title">
              <svg class="section-icon" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12h6m-6 4h6m2 5H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z"/>
              </svg>
              Job Description
            </h3>
            <p class="description-text">{{ $offer->description }}</p>
          </div>
        @endif

        <!-- Subjects Required -->
        @if(!empty($offer->subjects))
          <div class="offer-section-card">
            <h3 class="section-title">
              <svg class="section-icon" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 6.253v13m0-13C10.832 5.477 9.246 5 7.5 5S4.168 5.477 3 6.253v13C4.168 18.477 5.754 18 7.5 18s3.332.477 4.5 1.253m0-13C13.168 5.477 14.754 5 16.5 5c1.747 0 3.332.477 4.5 1.253v13C19.832 18.477 18.247 18 16.5 18c-1.746 0-3.332.477-4.5 1.253"/>
              </svg>
              Subjects Required
            </h3>
            <div class="subjects-list">
              @foreach($offer->subjects as $subject)
                <span class="subject-pill">{{ $subject }}</span>
              @endforeach
            </div>
          </div>
        @endif

        <!-- Requirements -->
        @if($offer->requirements)
          <div class="offer-section-card">
            <h3 class="section-title">
              <svg class="section-icon" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5H7a2 2 0 00-2 2v12a2 2 0 002 2h10a2 2 0 002-2V7a2 2 0 00-2-2h-2M9 5a2 2 0 002 2h2a2 2 0 002-2M9 5a2 2 0 012-2h2a2 2 0 012 2m-6 9l2 2 4-4"/>
              </svg>
              Requirements
            </h3>
            <div class="requirements-text">{{ $offer->requirements }}</div>
          </div>
        @endif

      </div>

      <!-- Sidebar -->
      <div class="offer-sidebar">

        <!-- Quick Details Card -->
        <div class="sidebar-card highlight-card">
          <div class="salary-display">
            <div class="salary-label">Monthly Salary</div>
            <div class="salary-amount">
              ৳{{ number_format($offer->salary) }}
            </div>
          </div>

          <div class="details-list">
            <div class="detail-row">
              <div class="detail-label">
                <svg class="detail-icon-sm" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                  <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17.657 16.657L13.414 20.9a1.998 1.998 0 01-2.827 0l-4.244-4.243a8 8 0 1111.314 0z"/>
                  <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 11a3 3 0 11-6 0 3 3 0 016 0z"/>
                </svg>
                Location
              </div>
              <div class="detail-value">
                {{ $offer->area->name ?? 'N/A' }}
              </div>
            </div>

            @if($offer->student_class)
              <div class="detail-row">
                <div class="detail-label">
                  <svg class="detail-icon-sm" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path d="M12 14l9-5-9-5-9 5 9 5z"/>
                    <path d="M12 14l6.16-3.422a12.083 12.083 0 01.665 6.479A11.952 11.952 0 0012 20.055a11.952 11.952 0 00-6.824-2.998 12.078 12.078 0 01.665-6.479L12 14z"/>
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 14l9-5-9-5-9 5 9 5zm0 0l6.16-3.422a12.083 12.083 0 01.665 6.479A11.952 11.952 0 0012 20.055a11.952 11.952 0 00-6.824-2.998 12.078 12.078 0 01.665-6.479L12 14zm-4 6v-7.5l4-2.222"/>
                  </svg>
                  Classes
                </div>
                <div class="detail-value">
                  {{ $offer->student_class }}
                </div>
              </div>
            @endif

            @if($offer->number_of_students)
              <div class="detail-row">
                <div class="detail-label">
                  <svg class="detail-icon-sm" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 4.354a4 4 0 110 5.292M15 21H3v-1a6 6 0 0112 0v1zm0 0h6v-1a6 6 0 00-9-5.197M13 7a4 4 0 11-8 0 4 4 0 018 0z"/>
                  </svg>
                  Students
                </div>
                <div class="detail-value">
                  {{ $offer->number_of_students }}
                </div>
              </div>
            @endif

            @if($offer->days_per_week)
              <div class="detail-row">
                <div class="detail-label">
                  <svg class="detail-icon-sm" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M8 7V3m8 4V3m-9 8h10M5 21h14a2 2 0 002-2V7a2 2 0 00-2-2H5a2 2 0 00-2 2v12a2 2 0 002 2z"/>
                  </svg>
                  Schedule
                </div>
                <div class="detail-value">
                  {{ $offer->days_per_week }} days/week
                </div>
              </div>
            @endif

            @if($offer->hours_per_day)
              <div class="detail-row">
                <div class="detail-label">
                  <svg class="detail-icon-sm" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8v4l3 3m6-3a9 9 0 11-18 0 9 9 0 0118 0z"/>
                  </svg>
                  Duration
                </div>
                <div class="detail-value">
                  {{ $offer->hours_per_day }} hrs/day
                </div>
              </div>
            @endif

            <div class="detail-row">
              <div class="detail-label">
                <svg class="detail-icon-sm" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                  <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 12l2-2m0 0l7-7 7 7M5 10v10a1 1 0 001 1h3m10-11l2 2m-2-2v10a1 1 0 01-1 1h-3m-6 0a1 1 0 001-1v-4a1 1 0 011-1h2a1 1 0 011 1v4a1 1 0 001 1m-6 0h6"/>
                </svg>
                Type
              </div>
              <div class="detail-value">
                {{ $offer->tuition_type }}
              </div>
            </div>
          </div>

          <!-- Apply Button -->
          @auth
            @if(auth()->user()->role === 'tutor')
              <a href="{{ route('tutor.offers.show', $offer->id) }}" class="apply-btn-primary">
                <svg width="20" height="20" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                  <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z"/>
                </svg>
                Apply for This Job
              </a>
            @endif
          @else
            <a href="{{ route('login') }}" class="apply-btn-secondary">
              <svg width="20" height="20" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M11 16l-4-4m0 0l4-4m-4 4h14m-5 4v1a3 3 0 01-3 3H6a3 3 0 01-3-3V7a3 3 0 013-3h7a3 3 0 013 3v1"/>
              </svg>
              Login to Apply
            </a>
          @endauth
        </div>

        <!-- Safety Tips Card -->
        <div class="sidebar-card info-card">
          <h4 class="info-title">
            <svg class="info-icon" fill="none" stroke="currentColor" viewBox="0 0 24 24">
              <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M13 16h-1v-4h-1m1-4h.01M21 12a9 9 0 11-18 0 9 9 0 0118 0z"/>
            </svg>
            Safety Tips
          </h4>
          <ul class="tips-list">
            <li>✓ Never pay any fee for getting a job</li>
            <li>✓ Meet at a safe public location</li>
            <li>✓ Verify parent credentials</li>
            <li>✓ Discuss all terms before starting</li>
          </ul>
        </div>

      </div>
    </div>

  </div>
</div>

<style>
.offer-detail-page {
  background: #F9FAFB;
  min-height: 100vh;
  padding: 40px 0 80px;
}

.container-offer-detail {
  max-width: 1200px;
  margin: 0 auto;
  padding: 0 24px;
}

.back-nav {
  margin-bottom: 32px;
}

.back-link {
  display: inline-flex;
  align-items: center;
  gap: 8px;
  color: #006A4E;
  text-decoration: none;
  font-weight: 600;
  font-size: 15px;
  transition: all 0.2s;
}

.back-link:hover {
  gap: 12px;
  color: #004A35;
}

.offer-grid {
  display: grid;
  grid-template-columns: 1fr 400px;
  gap: 32px;
}

@media (max-width: 968px) {
  .offer-grid {
    grid-template-columns: 1fr;
  }
}

/* Main Section */
.offer-header-card,
.offer-section-card {
  background: white;
  border-radius: 16px;
  padding: 32px;
  margin-bottom: 24px;
  box-shadow: 0 2px 12px rgba(0, 0, 0, 0.06);
}

.offer-badges {
  display: flex;
  flex-wrap: wrap;
  gap: 12px;
  margin-bottom: 20px;
}

.offer-type-badge,
.category-badge {
  padding: 8px 16px;
  border-radius: 8px;
  font-size: 13px;
  font-weight: 700;
  text-transform: uppercase;
  letter-spacing: 0.5px;
}

.offer-type-badge {
  background: #DBEAFE;
  color: #1E40AF;
}

.offer-type-badge.online {
  background: #FEF3C7;
  color: #92400E;
}

.offer-type-badge.home-visit {
  background: #D1FAE5;
  color: #065F46;
}

.category-badge {
  background: #FFEDD5;
  color: #9A3412;
}

.offer-title {
  font-size: 32px;
  font-weight: 900;
  color: #111827;
  margin-bottom: 20px;
  line-height: 1.3;
}

.offer-meta {
  display: flex;
  flex-wrap: wrap;
  gap: 24px;
  color: #6B7280;
  font-size: 14px;
}

.meta-item {
  display: flex;
  align-items: center;
  gap: 8px;
}

.meta-icon {
  width: 18px;
  height: 18px;
  color: #9CA3AF;
}

.section-title {
  display: flex;
  align-items: center;
  gap: 12px;
  font-size: 20px;
  font-weight: 800;
  color: #111827;
  margin-bottom: 20px;
}

.section-icon {
  width: 24px;
  height: 24px;
  color: #006A4E;
}

.description-text,
.requirements-text {
  color: #4B5563;
  font-size: 15px;
  line-height: 1.8;
  white-space: pre-wrap;
}

.subjects-list {
  display: flex;
  flex-wrap: wrap;
  gap: 12px;
}

.subject-pill {
  padding: 10px 18px;
  background: #E0F2FE;
  color: #0369A1;
  border-radius: 8px;
  font-size: 14px;
  font-weight: 600;
}

/* Sidebar */
.sidebar-card {
  background: white;
  border-radius: 16px;
  padding: 28px;
  margin-bottom: 24px;
  box-shadow: 0 2px 12px rgba(0, 0, 0, 0.06);
  position: sticky;
  top: 24px;
}

.highlight-card {
  background: linear-gradient(135deg, #f0fdf4 0%, #dcfce7 100%);
  border: 2px solid #00C878;
}

.salary-display {
  text-align: center;
  padding: 24px;
  background: white;
  border-radius: 12px;
  margin-bottom: 24px;
}

.salary-label {
  font-size: 13px;
  font-weight: 700;
  color: #6B7280;
  text-transform: uppercase;
  letter-spacing: 1px;
  margin-bottom: 8px;
}

.salary-amount {
  font-size: 28px;
  font-weight: 900;
  color: #006A4E;
}

.details-list {
  margin-bottom: 24px;
}

.detail-row {
  display: flex;
  justify-content: space-between;
  align-items: center;
  padding: 14px 0;
  border-bottom: 1px solid #F3F4F6;
}

.detail-row:last-child {
  border-bottom: none;
}

.detail-label {
  display: flex;
  align-items: center;
  gap: 8px;
  color: #6B7280;
  font-size: 14px;
  font-weight: 600;
}

.detail-icon-sm {
  width: 18px;
  height: 18px;
  color: #9CA3AF;
}

.detail-value {
  color: #111827;
  font-weight: 700;
  font-size: 14px;
  text-align: right;
}

.apply-btn-primary,
.apply-btn-secondary {
  width: 100%;
  padding: 16px;
  border-radius: 12px;
  font-size: 16px;
  font-weight: 700;
  display: flex;
  align-items: center;
  justify-content: center;
  gap: 10px;
  transition: all 0.3s;
  border: none;
  cursor: pointer;
  text-decoration: none;
}

.apply-btn-primary {
  background: #006A4E;
  color: white;
}

.apply-btn-primary:hover {
  background: #004A35;
  transform: translateY(-2px);
  box-shadow: 0 8px 20px rgba(0, 106, 78, 0.3);
}

.apply-btn-secondary {
  background: #F3F4F6;
  color: #374151;
}

.apply-btn-secondary:hover {
  background: #E5E7EB;
}

/* Info Card */
.info-card {
  background: #FEF3C7;
  border: 2px solid #FCD34D;
}

.info-title {
  display: flex;
  align-items: center;
  gap: 10px;
  font-size: 16px;
  font-weight: 800;
  color: #92400E;
  margin-bottom: 16px;
}

.info-icon {
  width: 22px;
  height: 22px;
}

.tips-list {
  list-style: none;
  padding: 0;
  margin: 0;
}

.tips-list li {
  padding: 10px 0;
  color: #78350F;
  font-size: 14px;
  font-weight: 600;
  border-bottom: 1px solid #FDE68A;
}

.tips-list li:last-child {
  border-bottom: none;
}
</style>

@endsection
