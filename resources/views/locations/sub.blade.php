@extends('layouts.app')
@section('title', ($area->meta_title ?? 'Tutors in ' . $area->full_name) . ' | TutorHub Bangladesh')
@section('meta_description', $area->meta_description ?? 'Find qualified tutors in ' . $area->full_name . '. Browse verified tutors for all subjects and levels.')

@section('content')
<div class="location-sub-page">
  <div class="container">

    <!-- Breadcrumb -->
    <div class="breadcrumb">
      <a href="{{ route('home') }}">Home</a>
      <span>/</span>
      <a href="{{ route('tutors.index') }}">Tutors</a>
      <span>/</span>
      <a href="{{ route('locations.main', $parentArea->slug) }}">{{ $parentArea->name }}</a>
      <span>/</span>
      <span>{{ $area->name }}</span>
    </div>

    <!-- Page Header -->
    <div class="page-hero">
      <h1 class="page-title">Tutors in {{ $area->name }}, {{ $parentArea->name }}</h1>
      <p class="page-subtitle">{{ $tutors->total() }} verified tutors available in {{ $area->name }}</p>
    </div>

    <!-- SEO Content -->
    @if($area->seo_content)
      <div class="seo-content">
        {!! nl2br(e($area->seo_content)) !!}
      </div>
    @else
      <div class="seo-content">
        <p>Find experienced and qualified tutors in {{ $area->name }}, {{ $parentArea->name }}. TutorHub connects you with verified tutors for all subjects including Mathematics, English, Physics, Chemistry, and more. Whether you need home tuition or online tutoring, our tutors in {{ $area->name }} are ready to help.</p>
      </div>
    @endif

    <!-- Tutors Grid -->
    @if($tutors->count() > 0)
      <div class="tutors-section">
        <div class="section-header">
          <h2 class="section-title">Available Tutors in {{ $area->name }}</h2>
          <p class="section-subtitle">All tutors are verified and background checked</p>
        </div>

        <div class="tutors-grid">
          @foreach($tutors as $tutor)
            <div class="tutor-card">
              <!-- Avatar -->
              <div class="tutor-avatar-section">
                @if($tutor->tutorProfile && $tutor->tutorProfile->profile_photo)
                  <img src="{{ asset('storage/' . $tutor->tutorProfile->profile_photo) }}" alt="{{ $tutor->name }}" class="tutor-avatar">
                @else
                  <div class="tutor-avatar avatar-placeholder">
                    {{ strtoupper(substr($tutor->name, 0, 2)) }}
                  </div>
                @endif
                <div class="verified-badge">
                  <svg width="16" height="16" fill="currentColor" viewBox="0 0 20 20">
                    <path fill-rule="evenodd" d="M6.267 3.455a3.066 3.066 0 001.745-.723 3.066 3.066 0 013.976 0 3.066 3.066 0 001.745.723 3.066 3.066 0 012.812 2.812c.051.643.304 1.254.723 1.745a3.066 3.066 0 010 3.976 3.066 3.066 0 00-.723 1.745 3.066 3.066 0 01-2.812 2.812 3.066 3.066 0 00-1.745.723 3.066 3.066 0 01-3.976 0 3.066 3.066 0 00-1.745-.723 3.066 3.066 0 01-2.812-2.812 3.066 3.066 0 00-.723-1.745 3.066 3.066 0 010-3.976 3.066 3.066 0 00.723-1.745 3.066 3.066 0 012.812-2.812zm7.44 5.252a1 1 0 00-1.414-1.414L9 10.586 7.707 9.293a1 1 0 00-1.414 1.414l2 2a1 1 0 001.414 0l4-4z" clip-rule="evenodd"/>
                  </svg>
                </div>
              </div>

              <!-- Info -->
              <div class="tutor-info">
                <h3 class="tutor-name">{{ $tutor->name }}</h3>
                <div class="tutor-rating">
                  <span class="stars">⭐⭐⭐⭐⭐</span>
                  <span class="rating-text">(4.8)</span>
                </div>

                @if($tutor->tutorProfile)
                  <!-- Bio -->
                  @if($tutor->tutorProfile->bio)
                    <p class="tutor-bio">{{ Str::limit($tutor->tutorProfile->bio, 100) }}</p>
                  @endif

                  <!-- Stats -->
                  <div class="tutor-stats">
                    <div class="stat-item">
                      <svg width="16" height="16" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M21 13.255A23.931 23.931 0 0112 15c-3.183 0-6.22-.62-9-1.745M16 6V4a2 2 0 00-2-2h-4a2 2 0 00-2 2v2m4 6h.01M5 20h14a2 2 0 002-2V8a2 2 0 00-2-2H5a2 2 0 00-2 2v10a2 2 0 002 2z"/>
                      </svg>
                      <span>{{ $tutor->tutorProfile->experience_years ?? 0 }} years</span>
                    </div>
                    <div class="stat-item">
                      <svg width="16" height="16" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 6.253v13m0-13C10.832 5.477 9.246 5 7.5 5S4.168 5.477 3 6.253v13C4.168 18.477 5.754 18 7.5 18s3.332.477 4.5 1.253m0-13C13.168 5.477 14.754 5 16.5 5c1.747 0 3.332.477 4.5 1.253v13C19.832 18.477 18.247 18 16.5 18c-1.746 0-3.332.477-4.5 1.253"/>
                      </svg>
                      <span>{{ count((array) $tutor->tutorProfile->subjects) }} subjects</span>
                    </div>
                  </div>

                  <!-- Subjects -->
                  @if(count((array) $tutor->tutorProfile->subjects) > 0)
                    <div class="subject-tags">
                      @foreach(array_slice((array) $tutor->tutorProfile->subjects, 0, 3) as $subject)
                        <span class="subject-tag">{{ $subject }}</span>
                      @endforeach
                      @if(count((array) $tutor->tutorProfile->subjects) > 3)
                        <span class="subject-tag more">+{{ count((array) $tutor->tutorProfile->subjects) - 3 }} more</span>
                      @endif
                    </div>
                  @endif

                  <!-- Pricing -->
                  @if($tutor->tutorProfile->hourly_rate)
                    <div class="tutor-pricing">
                      <span class="price-label">Tuition Fee:</span>
                      <span class="price">{{ number_format($tutor->tutorProfile->hourly_rate) }} BDT/month</span>
                    </div>
                  @endif
                @endif

                <!-- View Profile Button -->
                <a href="{{ route('tutors.show', $tutor->tutor_code) }}" class="btn-view-profile">
                  View Full Profile
                  <svg width="16" height="16" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5l7 7-7 7"/>
                  </svg>
                </a>
              </div>
            </div>
          @endforeach
        </div>

        <!-- Pagination -->
        <div class="pagination-wrapper">
          {{ $tutors->links() }}
        </div>
      </div>
    @else
      <!-- Empty State -->
      <div class="empty-state">
        <svg width="64" height="64" fill="none" stroke="currentColor" viewBox="0 0 24 24">
          <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17 20h5v-2a3 3 0 00-5.356-1.857M17 20H7m10 0v-2c0-.656-.126-1.283-.356-1.857M7 20H2v-2a3 3 0 015.356-1.857M7 20v-2c0-.656.126-1.283.356-1.857m0 0a5.002 5.002 0 019.288 0M15 7a3 3 0 11-6 0 3 3 0 016 0zm6 3a2 2 0 11-4 0 2 2 0 014 0zM7 10a2 2 0 11-4 0 2 2 0 014 0z"/>
        </svg>
        <h3>No Tutors Found in {{ $area->name }}</h3>
        <p>We're adding more tutors to this area. Try browsing other areas or check back soon.</p>
        <a href="{{ route('locations.main', $parentArea->slug) }}" class="btn-back">
          Browse Other Areas in {{ $parentArea->name }}
        </a>
      </div>
    @endif

    <!-- CTA Section -->
    <div class="cta-section">
      <h2>Looking for a Specific Tutor?</h2>
      <p>Post your requirement and let qualified tutors in {{ $area->name }} contact you</p>
      <a href="{{ route('register.parent') }}" class="btn-cta">Post Your Requirement</a>
    </div>

  </div>
</div>

<style>
.location-sub-page {
  padding: 40px 20px;
  background: #F9FAFB;
  min-height: 100vh;
}

.container {
  max-width: 1200px;
  margin: 0 auto;
}

/* Breadcrumb */
.breadcrumb {
  display: flex;
  align-items: center;
  gap: 8px;
  margin-bottom: 24px;
  font-size: 14px;
  color: #6B7280;
}

.breadcrumb a {
  color: #006A4E;
  text-decoration: none;
}

.breadcrumb a:hover {
  text-decoration: underline;
}

/* Page Hero */
.page-hero {
  text-align: center;
  margin-bottom: 32px;
}

.page-title {
  font-size: 40px;
  font-weight: 900;
  color: #111827;
  margin-bottom: 12px;
}

.page-subtitle {
  font-size: 18px;
  color: #6B7280;
}

/* SEO Content */
.seo-content {
  background: white;
  padding: 24px;
  border-radius: 12px;
  margin-bottom: 40px;
  line-height: 1.8;
  color: #374151;
  box-shadow: 0 1px 3px rgba(0, 0, 0, 0.1);
}

/* Tutors Section */
.tutors-section {
  margin-bottom: 48px;
}

.section-header {
  text-align: center;
  margin-bottom: 32px;
}

.section-title {
  font-size: 32px;
  font-weight: 800;
  color: #111827;
  margin-bottom: 8px;
}

.section-subtitle {
  font-size: 16px;
  color: #6B7280;
}

/* Tutors Grid */
.tutors-grid {
  display: grid;
  grid-template-columns: repeat(auto-fill, minmax(340px, 1fr));
  gap: 24px;
  margin-bottom: 32px;
}

.tutor-card {
  background: white;
  border-radius: 16px;
  padding: 24px;
  box-shadow: 0 1px 3px rgba(0, 0, 0, 0.1);
  transition: all 0.2s;
  display: flex;
  flex-direction: column;
}

.tutor-card:hover {
  box-shadow: 0 4px 12px rgba(0, 0, 0, 0.15);
  transform: translateY(-2px);
}

/* Avatar */
.tutor-avatar-section {
  position: relative;
  width: 80px;
  height: 80px;
  margin-bottom: 16px;
}

.tutor-avatar {
  width: 80px;
  height: 80px;
  border-radius: 16px;
  object-fit: cover;
}

.avatar-placeholder {
  width: 80px;
  height: 80px;
  border-radius: 16px;
  background: linear-gradient(135deg, #006A4E, #00C878);
  color: white;
  display: flex;
  align-items: center;
  justify-content: center;
  font-size: 28px;
  font-weight: 700;
}

.verified-badge {
  position: absolute;
  bottom: -4px;
  right: -4px;
  width: 28px;
  height: 28px;
  background: #10B981;
  border-radius: 50%;
  display: flex;
  align-items: center;
  justify-content: center;
  border: 3px solid white;
  color: white;
}

/* Info */
.tutor-info {
  flex: 1;
  display: flex;
  flex-direction: column;
}

.tutor-name {
  font-size: 20px;
  font-weight: 700;
  color: #111827;
  margin-bottom: 8px;
}

.tutor-rating {
  display: flex;
  align-items: center;
  gap: 8px;
  margin-bottom: 12px;
}

.stars {
  font-size: 14px;
}

.rating-text {
  font-size: 14px;
  color: #6B7280;
}

.tutor-bio {
  font-size: 14px;
  color: #6B7280;
  line-height: 1.6;
  margin-bottom: 16px;
}

/* Stats */
.tutor-stats {
  display: flex;
  gap: 16px;
  margin-bottom: 12px;
}

.stat-item {
  display: flex;
  align-items: center;
  gap: 6px;
  font-size: 14px;
  color: #6B7280;
}

.stat-item svg {
  flex-shrink: 0;
}

/* Subject Tags */
.subject-tags {
  display: flex;
  flex-wrap: wrap;
  gap: 8px;
  margin-bottom: 16px;
}

.subject-tag {
  padding: 6px 12px;
  background: #EFF6FF;
  color: #1E40AF;
  border-radius: 6px;
  font-size: 13px;
  font-weight: 500;
}

.subject-tag.more {
  background: #F3F4F6;
  color: #6B7280;
}

/* Pricing */
.tutor-pricing {
  padding: 12px;
  background: #F0FDF4;
  border-radius: 8px;
  margin-bottom: 16px;
}

.price-label {
  font-size: 13px;
  color: #047857;
  display: block;
  margin-bottom: 4px;
}

.price {
  font-size: 16px;
  font-weight: 700;
  color: #047857;
}

/* Button */
.btn-view-profile {
  display: flex;
  align-items: center;
  justify-content: center;
  gap: 8px;
  padding: 12px 20px;
  background: #006A4E;
  color: white;
  border-radius: 10px;
  font-size: 15px;
  font-weight: 600;
  text-decoration: none;
  transition: all 0.2s;
  margin-top: auto;
}

.btn-view-profile:hover {
  background: #004A35;
  transform: translateX(2px);
}

/* Empty State */
.empty-state {
  text-align: center;
  padding: 80px 20px;
  background: white;
  border-radius: 16px;
  box-shadow: 0 1px 3px rgba(0, 0, 0, 0.1);
}

.empty-state svg {
  color: #D1D5DB;
  margin-bottom: 24px;
}

.empty-state h3 {
  font-size: 24px;
  font-weight: 800;
  color: #111827;
  margin-bottom: 12px;
}

.empty-state p {
  font-size: 16px;
  color: #6B7280;
  margin-bottom: 24px;
}

.btn-back {
  display: inline-block;
  padding: 14px 32px;
  background: #006A4E;
  color: white;
  border-radius: 12px;
  font-size: 16px;
  font-weight: 600;
  text-decoration: none;
  transition: all 0.2s;
}

.btn-back:hover {
  background: #004A35;
  transform: translateY(-2px);
}

/* CTA Section */
.cta-section {
  background: linear-gradient(135deg, #006A4E, #00C878);
  padding: 48px 32px;
  border-radius: 16px;
  text-align: center;
  color: white;
}

.cta-section h2 {
  font-size: 32px;
  font-weight: 800;
  margin-bottom: 16px;
}

.cta-section p {
  font-size: 18px;
  margin-bottom: 32px;
  opacity: 0.9;
}

.btn-cta {
  display: inline-block;
  padding: 16px 40px;
  background: white;
  color: #006A4E;
  border-radius: 12px;
  font-size: 18px;
  font-weight: 700;
  text-decoration: none;
  transition: all 0.2s;
}

.btn-cta:hover {
  transform: translateY(-2px);
  box-shadow: 0 8px 20px rgba(0, 0, 0, 0.2);
}

/* Pagination */
.pagination-wrapper {
  display: flex;
  justify-content: center;
}

/* Responsive */
@media (max-width: 768px) {
  .page-title {
    font-size: 28px;
  }

  .tutors-grid {
    grid-template-columns: 1fr;
  }
}
</style>
@endsection
