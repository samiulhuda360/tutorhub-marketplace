@extends('layouts.app')
@section('title', ($level->meta_title ?? $level->name . ' Tutors in Bangladesh') . ' | TutorHub')
@section('meta_description', $level->meta_description ?? 'Find qualified ' . $level->name . ' tutors in Bangladesh. Browse verified tutors, compare rates, and hire the best teacher for your needs.')

@section('content')
<div class="location-sub-page">
  <div class="container">

    <!-- Breadcrumb -->
    <div class="breadcrumb">
      <a href="{{ route('home') }}">Home</a>
      <span>/</span>
      <a href="{{ route('tutors.index') }}">Tutors</a>
      <span>/</span>
      <span>{{ $level->name }}</span>
    </div>

    <!-- Page Header -->
    <div class="page-hero">
      <div class="hero-icon">{{ $level->icon }}</div>
      <h1 class="page-title">{{ $level->name }} Tutors in Bangladesh</h1>
      <p class="page-subtitle">{{ $tutors->total() }} verified tutors available</p>
    </div>

    <!-- SEO Content -->
    @if($level->seo_content)
      <div class="seo-content-section">
        {!! nl2br(e($level->seo_content)) !!}
      </div>
    @endif

    <!-- Tutors Grid -->
    @if($tutors->count() > 0)
      <div class="tutors-section">
        <h2 class="section-title">Available {{ $level->name }} Tutors</h2>

        <div class="tutors-grid">
          @foreach($tutors as $tutor)
            @php
              $profile = $tutor->tutorProfile;
            @endphp
            <a href="{{ route('tutors.show', $tutor->tutor_code) }}" class="tutor-card">
              <div class="tutor-avatar">
                @if($profile->avatar)
                  <img src="{{ asset('storage/' . $profile->avatar) }}" alt="{{ $tutor->name }}">
                @else
                  <div class="avatar-placeholder">{{ strtoupper(substr($tutor->name, 0, 1)) }}</div>
                @endif
              </div>

              <div class="tutor-info">
                <h3 class="tutor-name">{{ $tutor->name }}</h3>

                <div class="tutor-education">
                  <svg class="icon" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 14l9-5-9-5-9 5 9 5z M12 14l6.16-3.422a12.083 12.083 0 01.665 6.479A11.952 11.952 0 0012 20.055a11.952 11.952 0 00-6.824-2.998 12.078 12.078 0 01.665-6.479L12 14z"/>
                  </svg>
                  {{ $profile->education ?? 'Not specified' }}
                </div>

                @if($profile->subjects)
                  <div class="tutor-subjects">
                    <strong>Subjects:</strong> {{ Str::limit(implode(', ', (array) $profile->subjects), 50) }}
                  </div>
                @endif

                @if($profile->experience_years)
                  <div class="tutor-experience">
                    {{ $profile->experience_years }} years experience
                  </div>
                @endif

                @if($profile->hourly_rate)
                  <div class="tutor-rate">
                    ৳{{ number_format($profile->hourly_rate) }}/hour
                  </div>
                @endif
              </div>
            </a>
          @endforeach
        </div>

        <!-- Pagination -->
        <div class="pagination-wrapper">
          {{ $tutors->links() }}
        </div>
      </div>
    @else
      <div class="empty-state">
        <div class="empty-icon">📚</div>
        <h3>No tutors found</h3>
        <p>We couldn't find any {{ $level->name }} tutors at the moment. Please check back later or browse other subjects.</p>
        <a href="{{ route('tutors.index') }}" class="btn-primary">Browse All Tutors</a>
      </div>
    @endif

  </div>
</div>

<style>
.location-sub-page {
  padding: 40px 20px;
  background: #F9FAFB;
  min-height: 100vh;
}

.container {
  max-width: 1600px;
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
  margin-bottom: 48px;
}

.hero-icon {
  font-size: 64px;
  margin-bottom: 16px;
}

.page-title {
  font-size: 42px;
  font-weight: 900;
  color: #111827;
  margin-bottom: 12px;
}

.page-subtitle {
  font-size: 18px;
  color: #6B7280;
}

/* SEO Content */
.seo-content-section {
  background: white;
  padding: 32px;
  border-radius: 16px;
  margin-bottom: 48px;
  line-height: 1.8;
  font-size: 16px;
  color: #374151;
  box-shadow: 0 1px 3px rgba(0, 0, 0, 0.1);
}

/* Tutors Section */
.tutors-section {
  margin-bottom: 48px;
}

.section-title {
  font-size: 28px;
  font-weight: 800;
  color: #111827;
  margin-bottom: 32px;
}

.tutors-grid {
  display: grid;
  grid-template-columns: repeat(auto-fill, minmax(320px, 1fr));
  gap: 24px;
  margin-bottom: 32px;
}

.tutor-card {
  background: white;
  border-radius: 16px;
  padding: 24px;
  text-decoration: none;
  border: 2px solid #E5E7EB;
  transition: all 0.2s;
  display: flex;
  flex-direction: column;
  gap: 16px;
}

.tutor-card:hover {
  border-color: #006A4E;
  transform: translateY(-2px);
  box-shadow: 0 4px 12px rgba(0, 106, 78, 0.15);
}

.tutor-avatar {
  width: 80px;
  height: 80px;
  border-radius: 50%;
  overflow: hidden;
  margin: 0 auto;
}

.tutor-avatar img {
  width: 100%;
  height: 100%;
  object-fit: cover;
}

.avatar-placeholder {
  width: 100%;
  height: 100%;
  background: linear-gradient(135deg, #006A4E, #00C878);
  display: flex;
  align-items: center;
  justify-content: center;
  color: white;
  font-size: 32px;
  font-weight: 700;
}

.tutor-info {
  text-align: center;
}

.tutor-name {
  font-size: 20px;
  font-weight: 700;
  color: #111827;
  margin-bottom: 8px;
}

.tutor-education {
  display: flex;
  align-items: center;
  justify-content: center;
  gap: 6px;
  color: #6B7280;
  font-size: 14px;
  margin-bottom: 12px;
}

.tutor-education .icon {
  width: 16px;
  height: 16px;
}

.tutor-subjects,
.tutor-experience,
.tutor-rate {
  font-size: 14px;
  color: #374151;
  margin-bottom: 8px;
}

.tutor-rate {
  font-size: 18px;
  font-weight: 700;
  color: #006A4E;
}

/* Empty State */
.empty-state {
  text-align: center;
  padding: 80px 20px;
}

.empty-icon {
  font-size: 80px;
  margin-bottom: 24px;
}

.empty-state h3 {
  font-size: 24px;
  font-weight: 700;
  color: #111827;
  margin-bottom: 12px;
}

.empty-state p {
  font-size: 16px;
  color: #6B7280;
  margin-bottom: 24px;
}

.btn-primary {
  display: inline-block;
  padding: 12px 32px;
  background: #006A4E;
  color: white;
  border-radius: 8px;
  text-decoration: none;
  font-weight: 600;
  transition: all 0.2s;
}

.btn-primary:hover {
  background: #004A35;
  transform: translateY(-2px);
}

/* Pagination */
.pagination-wrapper {
  display: flex;
  justify-content: center;
  margin-top: 32px;
}

/* Responsive */
@media (max-width: 768px) {
  .page-title {
    font-size: 32px;
  }

  .tutors-grid {
    grid-template-columns: 1fr;
  }
}
</style>
@endsection
