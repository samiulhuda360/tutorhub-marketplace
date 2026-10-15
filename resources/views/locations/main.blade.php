@extends('layouts.app')
@section('title', ($area->meta_title ?? 'Tutors in ' . $area->name) . ' | TutorHub Bangladesh')
@section('meta_description', $area->meta_description ?? 'Find qualified tutors in ' . $area->name . '. Browse ' . $tutorsCount . '+ verified tutors across all areas.')

@section('content')
<div class="location-main-page">
  <div class="container">

    <!-- Page Header -->
    <div class="page-hero">
      <div class="breadcrumb">
        <a href="{{ route('home') }}">Home</a>
        <span>/</span>
        <a href="{{ route('tutors.index') }}">Tutors</a>
        <span>/</span>
        <span>{{ $area->name }}</span>
      </div>

      <h1 class="page-title">Tutors in {{ $area->name }}</h1>
      <p class="page-subtitle">{{ $tutorsCount }}+ verified tutors available across {{ $area->name }}</p>
    </div>

    <!-- SEO Content -->
    @if($area->seo_content)
      <div class="seo-content-section">
        {!! nl2br(e($area->seo_content)) !!}
      </div>
    @else
      <div class="seo-content-section">
        <p>Looking for a qualified tutor in {{ $area->name }}? TutorHub connects you with experienced tutors across all major areas in {{ $area->name }}. Our verified tutors offer personalized teaching for all subjects and levels.</p>
      </div>
    @endif

    <!-- Sub-locations Grid -->
    @if($subLocations->count() > 0)
      <div class="sub-locations-section">
        <h2 class="section-title">Browse Tutors by Area in {{ $area->name }}</h2>

        <div class="sub-locations-grid">
          @foreach($subLocations as $subLocation)
            <a href="{{ route('locations.sub', [$area->slug, $subLocation->slug]) }}" class="sub-location-card">
              <div class="card-icon">
                <svg width="24" height="24" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                  <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17.657 16.657L13.414 20.9a1.998 1.998 0 01-2.827 0l-4.244-4.243a8 8 0 1111.314 0z"/>
                  <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 11a3 3 0 11-6 0 3 3 0 016 0z"/>
                </svg>
              </div>
              <div class="card-content">
                <h3 class="card-title">{{ $subLocation->name }}</h3>
                <p class="card-subtitle">Find tutors in {{ $subLocation->name }}</p>
              </div>
              <div class="card-arrow">
                <svg width="20" height="20" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                  <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5l7 7-7 7"/>
                </svg>
              </div>
            </a>
          @endforeach
        </div>
      </div>
    @endif

    <!-- Why Choose TutorHub -->
    <div class="features-section">
      <h2 class="section-title">Why Choose TutorHub for {{ $area->name }}?</h2>
      <div class="features-grid">
        <div class="feature-card">
          <div class="feature-icon">✓</div>
          <h3>Verified Tutors</h3>
          <p>All tutors are background checked and verified</p>
        </div>
        <div class="feature-card">
          <div class="feature-icon">🎓</div>
          <h3>Expert Teachers</h3>
          <p>Qualified tutors with proven teaching experience</p>
        </div>
        <div class="feature-card">
          <div class="feature-icon">📍</div>
          <h3>Local Tutors</h3>
          <p>Find tutors near you in {{ $area->name }}</p>
        </div>
        <div class="feature-card">
          <div class="feature-icon">💰</div>
          <h3>Best Rates</h3>
          <p>Competitive pricing and flexible schedules</p>
        </div>
      </div>
    </div>

    <!-- CTA Section -->
    <div class="cta-section">
      <h2>Can't Find What You're Looking For?</h2>
      <p>Post your tuition requirement and let qualified tutors contact you</p>
      <a href="{{ route('register.parent') }}" class="btn-cta">Post Your Requirement</a>
    </div>

  </div>
</div>

<style>
.location-main-page {
  padding: 40px 20px;
  background: #F9FAFB;
  min-height: 100vh;
}

.container {
  max-width: 1200px;
  margin: 0 auto;
}

/* Page Hero */
.page-hero {
  text-align: center;
  margin-bottom: 48px;
}

.breadcrumb {
  display: flex;
  align-items: center;
  justify-content: center;
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

.page-title {
  font-size: 48px;
  font-weight: 900;
  color: #111827;
  margin-bottom: 16px;
}

.page-subtitle {
  font-size: 20px;
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

/* Sub-locations */
.sub-locations-section {
  margin-bottom: 48px;
}

.section-title {
  font-size: 32px;
  font-weight: 800;
  color: #111827;
  margin-bottom: 32px;
  text-align: center;
}

.sub-locations-grid {
  display: grid;
  grid-template-columns: repeat(auto-fill, minmax(300px, 1fr));
  gap: 20px;
}

.sub-location-card {
  display: flex;
  align-items: center;
  gap: 16px;
  padding: 24px;
  background: white;
  border-radius: 16px;
  border: 2px solid #E5E7EB;
  text-decoration: none;
  transition: all 0.2s;
}

.sub-location-card:hover {
  border-color: #006A4E;
  transform: translateY(-2px);
  box-shadow: 0 4px 12px rgba(0, 106, 78, 0.15);
}

.card-icon {
  flex-shrink: 0;
  width: 48px;
  height: 48px;
  background: #F0FDF4;
  border-radius: 12px;
  display: flex;
  align-items: center;
  justify-content: center;
  color: #006A4E;
}

.card-content {
  flex: 1;
}

.card-title {
  font-size: 18px;
  font-weight: 700;
  color: #111827;
  margin-bottom: 4px;
}

.card-subtitle {
  font-size: 14px;
  color: #6B7280;
  margin: 0;
}

.card-arrow {
  flex-shrink: 0;
  color: #9CA3AF;
  transition: all 0.2s;
}

.sub-location-card:hover .card-arrow {
  color: #006A4E;
  transform: translateX(4px);
}

/* Features */
.features-section {
  margin-bottom: 48px;
}

.features-grid {
  display: grid;
  grid-template-columns: repeat(auto-fit, minmax(250px, 1fr));
  gap: 24px;
}

.feature-card {
  background: white;
  padding: 32px;
  border-radius: 16px;
  text-align: center;
  box-shadow: 0 1px 3px rgba(0, 0, 0, 0.1);
}

.feature-icon {
  font-size: 48px;
  margin-bottom: 16px;
}

.feature-card h3 {
  font-size: 20px;
  font-weight: 700;
  color: #111827;
  margin-bottom: 8px;
}

.feature-card p {
  font-size: 15px;
  color: #6B7280;
  margin: 0;
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

/* Responsive */
@media (max-width: 768px) {
  .page-title {
    font-size: 32px;
  }

  .page-subtitle {
    font-size: 16px;
  }

  .sub-locations-grid {
    grid-template-columns: 1fr;
  }

  .features-grid {
    grid-template-columns: 1fr;
  }
}
</style>
@endsection
