@extends('layouts.app')
@section('title', 'TutorHub Bangladesh | Find Best Home Tutors & Tuition Jobs')
@section('meta_description', 'Find home tutors in Dhaka for every class and subject, or find tuition jobs near you. TutorHub matches parents and tutors and shares contact details safely.')

@section('content')

<!-- HERO SECTION - Ultra Modern -->
<section class="hero-premium">
  <div class="hero-bg-pattern"></div>
  <div class="hero-glow hero-glow-1"></div>
  <div class="hero-glow hero-glow-2"></div>

  <div class="container-premium">
    <div class="hero-grid-premium">

      <!-- Left Content -->
      <div class="hero-content-premium">
        <!-- Top Badge -->
        <div class="hero-badge-premium">
          <span class="badge-pulse"></span>
          <img src="data:image/svg+xml,%3Csvg xmlns='http://www.w3.org/2000/svg' viewBox='0 0 60 60'%3E%3Ccircle cx='30' cy='30' r='28' fill='%23006A4E'/%3E%3Ccircle cx='30' cy='30' r='15' fill='%23F42A41'/%3E%3C/svg%3E" alt="BD" class="badge-flag">
          <span class="badge-text">Home tutors across Dhaka</span>
        </div>

        <!-- Main Heading with Gradient -->
        <h1 class="hero-title-premium">
          <span class="title-line-1">Find Your Perfect</span>
          <span class="title-highlight">Home Tutor</span>
          <span class="title-line-3">in 3 Minutes</span>
        </h1>

        <!-- Subtitle with Stats Inline -->
        <p class="hero-subtitle-premium">
          <strong>{{ number_format($stats['total_tutors']) }} tutors</strong> across Dhaka, for every class from Class 1 to HSC, O and A Level.
          <br>Describe what you need in English or Bangla, and TutorHub suggests the tutors who fit.
        </p>

        <!-- Premium Search Box -->
        <div class="search-box-premium">
          <form action="{{ route('tutors.index') }}" method="GET" class="search-form-premium">
            <div class="search-tabs-premium">
              <button type="button" class="search-tab active" data-tab="tutors">
                <svg class="tab-icon" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                  <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M16 7a4 4 0 11-8 0 4 4 0 018 0zM12 14a7 7 0 00-7 7h14a7 7 0 00-7-7z"/>
                </svg>
                Find Tutors
              </button>
              <button type="button" class="search-tab" data-tab="jobs">
                <svg class="tab-icon" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                  <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M21 13.255A23.931 23.931 0 0112 15c-3.183 0-6.22-.62-9-1.745M16 6V4a2 2 0 00-2-2h-4a2 2 0 00-2 2v2m4 6h.01M5 20h14a2 2 0 002-2V8a2 2 0 00-2-2H5a2 2 0 00-2 2v10a2 2 0 002 2z"/>
                </svg>
                Find Tuition Jobs
              </button>
            </div>

            <div class="search-inputs-premium">
              <div class="search-main-input">
                <svg class="input-icon" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                  <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M21 21l-6-6m2-5a7 7 0 11-14 0 7 7 0 0114 0z"/>
                </svg>
                <input
                  type="text"
                  name="q"
                  placeholder="e.g., Math tutor for Class 10, English Medium..."
                  class="search-input-premium"
                  autocomplete="off"
                >
              </div>

              <div class="search-filters-row">
                <div class="filter-group">
                  <svg class="filter-icon" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17.657 16.657L13.414 20.9a1.998 1.998 0 01-2.827 0l-4.244-4.243a8 8 0 1111.314 0z"/>
                  </svg>
                  <select name="area" class="filter-select">
                    <option value="">Select Area</option>
                    @foreach($areas as $area)
                      <option value="{{ $area->slug }}">{{ $area->name }}</option>
                    @endforeach
                  </select>
                </div>

                <div class="filter-group">
                  <svg class="filter-icon" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 6.253v13m0-13C10.832 5.477 9.246 5 7.5 5S4.168 5.477 3 6.253v13C4.168 18.477 5.754 18 7.5 18s3.332.477 4.5 1.253m0-13C13.168 5.477 14.754 5 16.5 5c1.747 0 3.332.477 4.5 1.253v13C19.832 18.477 18.247 18 16.5 18c-1.746 0-3.332.477-4.5 1.253"/>
                  </svg>
                  <select name="subject" class="filter-select">
                    <option value="">Select Subject</option>
                    @foreach($subjects as $subject)
                      <option value="{{ $subject->slug }}">{{ $subject->name }}</option>
                    @endforeach
                  </select>
                </div>

                <div class="filter-group">
                  <svg class="filter-icon" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 14l9-5-9-5-9 5 9 5z M12 14l6.16-3.422a12.083 12.083 0 01.665 6.479A11.952 11.952 0 0012 20.055a11.952 11.952 0 00-6.824-2.998 12.078 12.078 0 01.665-6.479L12 14z"/>
                  </svg>
                  <select name="level" class="filter-select">
                    <option value="">Select Level</option>
                    @foreach($levels as $level)
                      <option value="{{ $level->slug }}">{{ $level->name }}</option>
                    @endforeach
                  </select>
                </div>

                <button type="submit" class="search-button-premium">
                  <svg class="button-icon-premium" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M21 21l-6-6m2-5a7 7 0 11-14 0 7 7 0 0114 0z"/>
                  </svg>
                  <span>Search</span>
                </button>
              </div>
            </div>

            <!-- Popular Tags -->
            <div class="popular-tags-premium">
              <span class="tags-label">Popular:</span>
              <a href="{{ route('tutors.index', ['subject' => 'mathematics']) }}" class="tag-btn">Mathematics</a>
              <a href="{{ route('tutors.index', ['subject' => 'english']) }}" class="tag-btn">English</a>
              <a href="{{ route('tutors.index', ['subject' => 'physics']) }}" class="tag-btn">Physics</a>
              <a href="{{ route('tutors.index', ['subject' => 'chemistry']) }}" class="tag-btn">Chemistry</a>
              <a href="{{ route('tutors.index', ['subject' => 'bangla']) }}" class="tag-btn">Bangla</a>
            </div>
          </form>
        </div>

        <!-- Stats Grid - Modern -->
        <div class="stats-grid-premium">
          <div class="stat-card-premium">
            <div class="stat-icon-premium">
              <svg fill="none" stroke="currentColor" viewBox="0 0 24 24">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17 20h5v-2a3 3 0 00-5.356-1.857M17 20H7m10 0v-2c0-.656-.126-1.283-.356-1.857M7 20H2v-2a3 3 0 015.356-1.857M7 20v-2c0-.656.126-1.283.356-1.857m0 0a5.002 5.002 0 019.288 0M15 7a3 3 0 11-6 0 3 3 0 016 0zm6 3a2 2 0 11-4 0 2 2 0 014 0zM7 10a2 2 0 11-4 0 2 2 0 014 0z"/>
              </svg>
            </div>
            <div class="stat-content">
              <div class="stat-number">{{ number_format($stats['total_tutors']) }}</div>
              <div class="stat-label">Tutors</div>
            </div>
          </div>

          <div class="stat-card-premium">
            <div class="stat-icon-premium success">
              <svg fill="none" stroke="currentColor" viewBox="0 0 24 24">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M21 13.255A23.931 23.931 0 0112 15c-3.183 0-6.22-.62-9-1.745M16 6V4a2 2 0 00-2-2h-4a2 2 0 00-2 2v2m4 6h.01M5 20h14a2 2 0 002-2V8a2 2 0 00-2-2H5a2 2 0 00-2 2v10a2 2 0 002 2z"/>
              </svg>
            </div>
            <div class="stat-content">
              <div class="stat-number">{{ number_format($stats['open_offers']) }}+</div>
              <div class="stat-label">Active Jobs</div>
            </div>
          </div>

          <div class="stat-card-premium">
            <div class="stat-icon-premium warning">
              <svg fill="none" stroke="currentColor" viewBox="0 0 24 24">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M14.828 14.828a4 4 0 01-5.656 0M9 10h.01M15 10h.01M21 12a9 9 0 11-18 0 9 9 0 0118 0z"/>
              </svg>
            </div>
            <div class="stat-content">
              <div class="stat-number">{{ number_format($stats['total_parents']) }}+</div>
              <div class="stat-label">Happy Parents</div>
            </div>
          </div>

          <div class="stat-card-premium">
            <div class="stat-icon-premium gold">
              <svg fill="currentColor" viewBox="0 0 20 20">
                <path d="M9.049 2.927c.3-.921 1.603-.921 1.902 0l1.07 3.292a1 1 0 00.95.69h3.462c.969 0 1.371 1.24.588 1.81l-2.8 2.034a1 1 0 00-.364 1.118l1.07 3.292c.3.921-.755 1.688-1.54 1.118l-2.8-2.034a1 1 0 00-1.175 0l-2.8 2.034c-.784.57-1.838-.197-1.539-1.118l1.07-3.292a1 1 0 00-.364-1.118L2.98 8.72c-.783-.57-.38-1.81.588-1.81h3.461a1 1 0 00.951-.69l1.07-3.292z"/>
              </svg>
            </div>
            <div class="stat-content">
              <div class="stat-number">4.9/5</div>
              <div class="stat-label">Avg Rating</div>
            </div>
          </div>
        </div>
      </div>

      <!-- Right Illustration Card -->
      <div class="hero-card-premium">
        <div class="card-glow"></div>
        <div class="card-content-premium">
          <!-- 3D Illustration Area -->
          <div class="illustration-premium">
            <div class="illustration-circle circle-1"></div>
            <div class="illustration-circle circle-2"></div>
            <div class="illustration-circle circle-3"></div>

            <div class="illustration-main">
              <svg viewBox="0 0 200 200" fill="none" xmlns="http://www.w3.org/2000/svg" class="illustration-svg">
                <circle cx="100" cy="100" r="90" fill="url(#grad1)"/>
                <circle cx="100" cy="85" r="35" fill="white" opacity="0.9"/>
                <path d="M100 120C120 120 135 130 135 150H65C65 130 80 120 100 120Z" fill="white" opacity="0.9"/>
                <circle cx="90" cy="80" r="5" fill="#111"/>
                <circle cx="110" cy="80" r="5" fill="#111"/>
                <path d="M85 95Q100 105 115 95" stroke="#111" stroke-width="3" stroke-linecap="round" fill="none"/>
                <defs>
                  <linearGradient id="grad1" x1="0%" y1="0%" x2="100%" y2="100%">
                    <stop offset="0%" style="stop-color:#006A4E;stop-opacity:1" />
                    <stop offset="100%" style="stop-color:#00C878;stop-opacity:1" />
                  </linearGradient>
                </defs>
              </svg>

              <!-- Floating Elements -->
              <div class="float-element float-1">📚</div>
              <div class="float-element float-2">✏️</div>
              <div class="float-element float-3">🎓</div>
              <div class="float-element float-4">📖</div>
            </div>
          </div>

          <!-- CTA Buttons -->
          <div class="cta-section-premium">
            <h3 class="cta-title-premium">Get Started Today!</h3>
            <p class="cta-subtitle-premium">Register in just 2 minutes</p>

            <div class="cta-buttons-premium">
              <a href="{{ route('register.tutor') }}" class="cta-btn-premium tutor">
                <div class="btn-icon-wrapper">
                  <svg fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M21 13.255A23.931 23.931 0 0112 15c-3.183 0-6.22-.62-9-1.745M16 6V4a2 2 0 00-2-2h-4a2 2 0 00-2 2v2m4 6h.01M5 20h14a2 2 0 002-2V8a2 2 0 00-2-2H5a2 2 0 00-2 2v10a2 2 0 002 2z"/>
                  </svg>
                </div>
                <div class="btn-content">
                  <span class="btn-title">Register as Tutor</span>
                  <span class="btn-subtitle">Get tuition jobs</span>
                </div>
                <svg class="btn-arrow" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                  <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5l7 7-7 7"/>
                </svg>
              </a>

              <a href="{{ route('register.parent') }}" class="cta-btn-premium parent">
                <div class="btn-icon-wrapper">
                  <svg fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M21 21l-6-6m2-5a7 7 0 11-14 0 7 7 0 0114 0z"/>
                  </svg>
                </div>
                <div class="btn-content">
                  <span class="btn-title">Register as Parent</span>
                  <span class="btn-subtitle">Find perfect tutor</span>
                </div>
                <svg class="btn-arrow" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                  <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5l7 7-7 7"/>
                </svg>
              </a>
            </div>

            <!-- Trust Indicators -->
            <div class="trust-indicators-premium">
              <div class="trust-item">
                <svg class="trust-icon" fill="currentColor" viewBox="0 0 20 20">
                  <path fill-rule="evenodd" d="M2.166 4.999A11.954 11.954 0 0010 1.944 11.954 11.954 0 0017.834 5c.11.65.166 1.32.166 2.001 0 5.225-3.34 9.67-8 11.317C5.34 16.67 2 12.225 2 7c0-.682.057-1.35.166-2.001zm11.541 3.708a1 1 0 00-1.414-1.414L9 10.586 7.707 9.293a1 1 0 00-1.414 1.414l2 2a1 1 0 001.414 0l4-4z" clip-rule="evenodd"/>
                </svg>
                <span>Contacts shared safely</span>
              </div>
              <div class="trust-item">
                <svg class="trust-icon" fill="currentColor" viewBox="0 0 20 20">
                  <path fill-rule="evenodd" d="M6.267 3.455a3.066 3.066 0 001.745-.723 3.066 3.066 0 013.976 0 3.066 3.066 0 001.745.723 3.066 3.066 0 012.812 2.812c.051.643.304 1.254.723 1.745a3.066 3.066 0 010 3.976 3.066 3.066 0 00-.723 1.745 3.066 3.066 0 01-2.812 2.812 3.066 3.066 0 00-1.745.723 3.066 3.066 0 01-3.976 0 3.066 3.066 0 00-1.745-.723 3.066 3.066 0 01-2.812-2.812 3.066 3.066 0 00-.723-1.745 3.066 3.066 0 010-3.976 3.066 3.066 0 00.723-1.745 3.066 3.066 0 012.812-2.812zm7.44 5.252a1 1 0 00-1.414-1.414L9 10.586 7.707 9.293a1 1 0 00-1.414 1.414l2 2a1 1 0 001.414 0l4-4z" clip-rule="evenodd"/>
                </svg>
                <span>Verified</span>
              </div>
              <div class="trust-item">
                <svg class="trust-icon" fill="currentColor" viewBox="0 0 20 20">
                  <path fill-rule="evenodd" d="M11.3 1.046A1 1 0 0112 2v5h4a1 1 0 01.82 1.573l-7 10A1 1 0 018 18v-5H4a1 1 0 01-.82-1.573l7-10a1 1 0 011.12-.38z" clip-rule="evenodd"/>
                </svg>
                <span>Fast</span>
              </div>
            </div>
          </div>
        </div>
      </div>

    </div>
  </div>

  <!-- Wave Bottom -->
  <div class="wave-bottom">
    <svg viewBox="0 0 1440 100" fill="none" xmlns="http://www.w3.org/2000/svg" preserveAspectRatio="none">
      <path d="M0 0L60 8.3C120 16.7 240 33.3 360 41.7C480 50 600 50 720 45C840 40 960 30 1080 28.3C1200 26.7 1320 33.3 1380 36.7L1440 40V100H1380C1320 100 1200 100 1080 100C960 100 840 100 720 100C600 100 480 100 360 100C240 100 120 100 60 100H0V0Z" fill="white"/>
    </svg>
  </div>
</section>

<!-- FEATURED TUTORS SECTION -->
@if($featuredTutors->count() > 0)
<section class="section-premium featured-tutors">
  <div class="container-premium">
    <div class="section-header-premium">
      <div class="section-header-content">
        <div class="section-badge">⭐ Featured</div>
        <h2 class="section-title-premium">Tutors on TutorHub</h2>
        <p class="section-subtitle-premium">A few of the tutors available now</p>
      </div>
      <a href="{{ route('tutors.index') }}" class="view-all-btn-premium">
        <span>View All Tutors</span>
        <svg class="btn-arrow-icon" fill="none" stroke="currentColor" viewBox="0 0 24 24">
          <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17 8l4 4m0 0l-4 4m4-4H3"/>
        </svg>
      </a>
    </div>

    <div class="tutors-grid-premium">
      @foreach($featuredTutors as $tutor)
        @php
          $profile = $tutor->tutorProfile;
        @endphp
        <div class="tutor-card-premium">
          <div class="tutor-card-header">
            <div class="tutor-avatar-premium">
              @if($profile && $profile->photo)
                <img src="{{ asset('storage/' . $profile->photo) }}" alt="{{ $tutor->name }}">
              @else
                <div class="avatar-placeholder-premium">
                  {{ strtoupper(substr($tutor->name, 0, 2)) }}
                </div>
              @endif
              @if($profile && $profile->is_verified)
                <div class="verified-badge-premium">
                  <svg fill="currentColor" viewBox="0 0 20 20">
                    <path fill-rule="evenodd" d="M6.267 3.455a3.066 3.066 0 001.745-.723 3.066 3.066 0 013.976 0 3.066 3.066 0 001.745.723 3.066 3.066 0 012.812 2.812c.051.643.304 1.254.723 1.745a3.066 3.066 0 010 3.976 3.066 3.066 0 00-.723 1.745 3.066 3.066 0 01-2.812 2.812 3.066 3.066 0 00-1.745.723 3.066 3.066 0 01-3.976 0 3.066 3.066 0 00-1.745-.723 3.066 3.066 0 01-2.812-2.812 3.066 3.066 0 00-.723-1.745 3.066 3.066 0 010-3.976 3.066 3.066 0 00.723-1.745 3.066 3.066 0 012.812-2.812zm7.44 5.252a1 1 0 00-1.414-1.414L9 10.586 7.707 9.293a1 1 0 00-1.414 1.414l2 2a1 1 0 001.414 0l4-4z" clip-rule="evenodd"/>
                  </svg>
                </div>
              @endif
            </div>
            <div class="tutor-rating-premium">
              <div class="rating-text">{{ $profile?->experience_years ?? 0 }} years teaching{{ $profile?->is_verified ? ' · Verified' : '' }}</div>
            </div>
          </div>

          <div class="tutor-info-premium">
            <h3 class="tutor-name-premium">{{ $tutor->name }}</h3>
            @if($profile)
              <p class="tutor-education-premium">{{ Str::limit($profile->education, 60) }}</p>
            @endif
          </div>

          @if($profile && $profile->subjects)
            <div class="tutor-subjects-premium">
              @foreach(array_slice((array)$profile->subjects, 0, 5) as $subject)
                <span class="subject-tag-premium">{{ $subject }}</span>
              @endforeach
              @if(count((array)$profile->subjects) > 5)
                <span class="subject-tag-premium more">+{{ count((array)$profile->subjects) - 5 }}</span>
              @endif
            </div>
          @endif

          @if($profile)
            <div class="tutor-stats-premium">
              <div class="stat-item-premium">
                <svg class="stat-icon-mini" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                  <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M21 13.255A23.931 23.931 0 0112 15c-3.183 0-6.22-.62-9-1.745M16 6V4a2 2 0 00-2-2h-4a2 2 0 00-2 2v2m4 6h.01M5 20h14a2 2 0 002-2V8a2 2 0 00-2-2H5a2 2 0 00-2 2v10a2 2 0 002 2z"/>
                </svg>
                <span>{{ $profile->experience_years }} Years</span>
              </div>
              <div class="stat-item-premium">
                <svg class="stat-icon-mini" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                  <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8c-1.657 0-3 .895-3 2s1.343 2 3 2 3 .895 3 2-1.343 2-3 2m0-8c1.11 0 2.08.402 2.599 1M12 8V7m0 1v8m0 0v1m0-1c-1.11 0-2.08-.402-2.599-1M21 12a9 9 0 11-18 0 9 9 0 0118 0z"/>
                </svg>
                <span>{{ number_format($profile->hourly_rate) }} Tk/month</span>
              </div>
            </div>
          @endif

          <a href="{{ route('tutors.show', $tutor->id) }}" class="view-profile-btn-premium">
            <span>View Profile</span>
            <svg class="btn-arrow-sm" fill="none" stroke="currentColor" viewBox="0 0 24 24">
              <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5l7 7-7 7"/>
            </svg>
          </a>
        </div>
      @endforeach
    </div>
  </div>
</section>
@endif

<!-- RECENT TUITION JOBS -->
@if($recentOffers->count() > 0)
<section class="section-premium recent-jobs">
  <div class="container-premium">
    <div class="section-header-premium">
      <div class="section-header-content">
        <div class="section-badge">🔥 New</div>
        <h2 class="section-title-premium">Latest Tuition Jobs</h2>
        <p class="section-subtitle-premium">Fresh opportunities posted by parents</p>
      </div>
      <a href="{{ route('tuition_offers.index') }}" class="view-all-btn-premium">
        <span>View All Jobs</span>
        <svg class="btn-arrow-icon" fill="none" stroke="currentColor" viewBox="0 0 24 24">
          <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17 8l4 4m0 0l-4 4m4-4H3"/>
        </svg>
      </a>
    </div>

    <div class="jobs-grid-premium">
      @foreach($recentOffers->take(4) as $offer)
        <div class="job-card-premium">
          <div class="job-card-header">
            <div class="job-badges">
              <span class="job-type-badge">{{ strtoupper($offer->tuition_type) }}</span>
              @if($offer->category)
                <span class="job-category-badge">{{ $offer->category->name }}</span>
              @endif
            </div>
            <span class="job-time">{{ $offer->posted_at->diffForHumans() }}</span>
          </div>

          <h3 class="job-title-premium">{{ Str::limit($offer->title, 65) }}</h3>

          <div class="job-details-grid">
            <div class="job-detail-item">
              <svg class="detail-icon-mini" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17.657 16.657L13.414 20.9a1.998 1.998 0 01-2.827 0l-4.244-4.243a8 8 0 1111.314 0z"/>
              </svg>
              <span>{{ $offer->area->name ?? 'N/A' }}</span>
            </div>
            <div class="job-detail-item">
              <svg class="detail-icon-mini" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 6.253v13m0-13C10.832 5.477 9.246 5 7.5 5S4.168 5.477 3 6.253v13C4.168 18.477 5.754 18 7.5 18s3.332.477 4.5 1.253m0-13C13.168 5.477 14.754 5 16.5 5c1.747 0 3.332.477 4.5 1.253v13C19.832 18.477 18.247 18 16.5 18c-1.746 0-3.332.477-4.5 1.253"/>
              </svg>
              <span>Class {{ $offer->student_class }}</span>
            </div>
            <div class="job-detail-item">
              <svg class="detail-icon-mini" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M8 7V3m8 4V3m-9 8h10M5 21h14a2 2 0 002-2V7a2 2 0 00-2-2H5a2 2 0 00-2 2v12a2 2 0 002 2z"/>
              </svg>
              <span>{{ $offer->days_per_week }} days/week</span>
            </div>
            <div class="job-detail-item salary">
              <svg class="detail-icon-mini" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8c-1.657 0-3 .895-3 2s1.343 2 3 2 3 .895 3 2-1.343 2-3 2m0-8c1.11 0 2.08.402 2.599 1M12 8V7m0 1v8m0 0v1m0-1c-1.11 0-2.08-.402-2.599-1M21 12a9 9 0 11-18 0 9 9 0 0118 0z"/>
              </svg>
              <span>{{ number_format($offer->salary) }} Tk</span>
            </div>
          </div>

          @if($offer->subjects)
            <div class="job-subjects-premium">
              <strong>Subjects:</strong>
              {{ is_array($offer->subjects) ? implode(', ', array_slice($offer->subjects, 0, 3)) : $offer->subjects }}
              @if(is_array($offer->subjects) && count($offer->subjects) > 3)
                <span class="more-subjects">+{{ count($offer->subjects) - 3 }} more</span>
              @endif
            </div>
          @endif

          <div class="job-actions-premium">
            <a href="{{ route('tuition_offers.show', $offer->id) }}" class="job-details-btn">View Details</a>
            @auth
              @if(auth()->user()->role === 'tutor')
                <a href="{{ route('tutor.offers.show', $offer->id) }}" class="job-apply-btn">Apply Now</a>
              @endif
            @else
              <a href="{{ route('login') }}" class="job-apply-btn">Login to Apply</a>
            @endauth
          </div>
        </div>
      @endforeach
    </div>

    <div class="section-cta-center">
      <a href="{{ route('tuition_offers.index') }}" class="cta-large-btn-premium">
        <span>Browse All {{ $stats['open_offers'] }}+ Available Jobs</span>
        <svg class="btn-arrow-icon" fill="none" stroke="currentColor" viewBox="0 0 24 24">
          <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17 8l4 4m0 0l-4 4m4-4H3"/>
        </svg>
      </a>
    </div>
  </div>
</section>
@endif

<!-- HOW IT WORKS SECTION -->
<section class="section-premium how-it-works">
  <div class="container-premium">
    <div class="section-header-center-premium">
      <div class="section-badge">📖 Guide</div>
      <h2 class="section-title-premium">How It Works</h2>
      <p class="section-subtitle-premium">Simple steps to find your perfect tutor or teaching opportunity</p>
    </div>

    <div class="how-it-works-grid">
      <!-- For Parents -->
      <div class="how-card-premium parent-how">
        <div class="how-card-icon">
          <svg fill="none" stroke="currentColor" viewBox="0 0 24 24">
            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17 20h5v-2a3 3 0 00-5.356-1.857M17 20H7m10 0v-2c0-.656-.126-1.283-.356-1.857M7 20H2v-2a3 3 0 015.356-1.857M7 20v-2c0-.656.126-1.283.356-1.857m0 0a5.002 5.002 0 019.288 0M15 7a3 3 0 11-6 0 3 3 0 016 0zm6 3a2 2 0 11-4 0 2 2 0 014 0zM7 10a2 2 0 11-4 0 2 2 0 014 0z"/>
          </svg>
        </div>
        <h3 class="how-card-title">For Parents</h3>

        <div class="steps-list-premium">
          <div class="step-premium">
            <div class="step-number-premium">1</div>
            <div class="step-content-premium">
              <h4>Post Your Requirement</h4>
              <p>Describe your child's class, subjects, and location preferences</p>
            </div>
          </div>

          <div class="step-premium">
            <div class="step-number-premium">2</div>
            <div class="step-content-premium">
              <h4>Review Applications</h4>
              <p>Qualified tutors will apply with their complete profiles</p>
            </div>
          </div>

          <div class="step-premium">
            <div class="step-number-premium">3</div>
            <div class="step-content-premium">
              <h4>Hire & Start Classes</h4>
              <p>Accept the best tutor and begin learning immediately</p>
            </div>
          </div>
        </div>

        <a href="{{ route('register.parent') }}" class="how-card-btn parent-btn">
          Register as Parent
          <svg class="btn-arrow-sm" fill="none" stroke="currentColor" viewBox="0 0 24 24">
            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5l7 7-7 7"/>
          </svg>
        </a>
      </div>

      <!-- For Tutors -->
      <div class="how-card-premium tutor-how">
        <div class="how-card-icon">
          <svg fill="none" stroke="currentColor" viewBox="0 0 24 24">
            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M21 13.255A23.931 23.931 0 0112 15c-3.183 0-6.22-.62-9-1.745M16 6V4a2 2 0 00-2-2h-4a2 2 0 00-2 2v2m4 6h.01M5 20h14a2 2 0 002-2V8a2 2 0 00-2-2H5a2 2 0 00-2 2v10a2 2 0 002 2z"/>
          </svg>
        </div>
        <h3 class="how-card-title">For Tutors</h3>

        <div class="steps-list-premium">
          <div class="step-premium">
            <div class="step-number-premium">1</div>
            <div class="step-content-premium">
              <h4>Create Your Profile</h4>
              <p>Add your education, subjects, experience, and rates</p>
            </div>
          </div>

          <div class="step-premium">
            <div class="step-number-premium">2</div>
            <div class="step-content-premium">
              <h4>Browse & Apply</h4>
              <p>Search tuition jobs matching your skills and location</p>
            </div>
          </div>

          <div class="step-premium">
            <div class="step-number-premium">3</div>
            <div class="step-content-premium">
              <h4>Get Hired</h4>
              <p>Parents will review and hire you for their requirements</p>
            </div>
          </div>
        </div>

        <a href="{{ route('register.tutor') }}" class="how-card-btn tutor-btn">
          Register as Tutor
          <svg class="btn-arrow-sm" fill="none" stroke="currentColor" viewBox="0 0 24 24">
            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5l7 7-7 7"/>
          </svg>
        </a>
      </div>
    </div>
  </div>
</section>

<!-- SUBJECTS SECTION -->
@if($subjects->count() > 0)
<section class="section-premium categories-section">
  <div class="container-premium">
    <div class="section-header-center-premium">
      <div class="section-badge">📚 Subjects</div>
      <h2 class="section-title-premium">Browse Tutors by Subject</h2>
      <p class="section-subtitle-premium">Find expert tutors for any subject you need</p>
    </div>

    <div class="categories-grid-premium">
      @foreach($subjects as $subject)
        <a href="{{ route('subjects.show', $subject->slug) }}" class="category-card-premium">
          <div class="category-icon-premium">{{ $subject->icon }}</div>
          <div class="category-name-premium">{{ $subject->name }}</div>
          <div class="category-arrow">
            <svg fill="none" stroke="currentColor" viewBox="0 0 24 24">
              <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5l7 7-7 7"/>
            </svg>
          </div>
        </a>
      @endforeach
    </div>
  </div>
</section>
@endif

<!-- LEVELS SECTION -->
@if($levels->count() > 0)
<section class="section-premium categories-section">
  <div class="container-premium">
    <div class="section-header-center-premium">
      <div class="section-badge">🎓 Education Levels</div>
      <h2 class="section-title-premium">Find Tutors by Level</h2>
      <p class="section-subtitle-premium">From primary to university - tutors for all education levels</p>
    </div>

    <div class="categories-grid-premium">
      @foreach($levels as $level)
        <a href="{{ route('levels.show', $level->slug) }}" class="category-card-premium">
          <div class="category-icon-premium">{{ $level->icon }}</div>
          <div class="category-name-premium">{{ $level->name }}</div>
          <div class="category-arrow">
            <svg fill="none" stroke="currentColor" viewBox="0 0 24 24">
              <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5l7 7-7 7"/>
            </svg>
          </div>
        </a>
      @endforeach
    </div>
  </div>
</section>
@endif

<!-- AREAS SECTION -->
@if($areas->count() > 0)
<section class="section-premium categories-section">
  <div class="container-premium">
    <div class="section-header-center-premium">
      <div class="section-badge">📍 Locations</div>
      <h2 class="section-title-premium">Browse Tutors by Area</h2>
      <p class="section-subtitle-premium">Find qualified tutors in your locality</p>
    </div>

    <div class="categories-grid-premium">
      @foreach($areas as $area)
        @if($area->isSubLocation())
          <a href="{{ route('locations.sub', [$area->parent->slug, $area->slug]) }}" class="category-card-premium">
            <div class="category-icon-premium">📍</div>
            <div class="category-name-premium">{{ $area->name }}</div>
            <div class="category-arrow">
              <svg fill="none" stroke="currentColor" viewBox="0 0 24 24">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5l7 7-7 7"/>
              </svg>
            </div>
          </a>
        @else
          <a href="{{ route('locations.main', $area->slug) }}" class="category-card-premium">
            <div class="category-icon-premium">📍</div>
            <div class="category-name-premium">{{ $area->name }}</div>
            <div class="category-arrow">
              <svg fill="none" stroke="currentColor" viewBox="0 0 24 24">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5l7 7-7 7"/>
              </svg>
            </div>
          </a>
        @endif
      @endforeach
    </div>
  </div>
</section>
@endif

<!-- TESTIMONIALS SECTION -->
<section class="section-premium testimonials-section">
  <div class="container-premium">
    <div class="section-header-center-premium">
      <div class="section-badge">How it works</div>
      <h2 class="section-title-premium">From request to first class</h2>
      <p class="section-subtitle-premium">Parents post for free. Tutors apply to jobs that fit them. TutorHub confirms every match.</p>
    </div>

    <div class="testimonials-grid-premium">
      @foreach([
        ['1', 'Post what you need', 'Write it the way you would tell a friend, in English or Bangla. TutorHub fills in the class, subjects, area, days and salary for you to check.'],
        ['2', 'Choose your tutor', 'See the tutors who fit, with the reasons: subjects, class, area, budget and experience. Invite them, or accept tutors who apply.'],
        ['3', 'Start tuition', 'TutorHub confirms the match with the tutor, then shares contact details. Your phone number and address stay private until then.'],
      ] as $step)
        <div class="testimonial-card-premium">
          <div class="quote-mark-premium">{{ $step[0] }}</div>
          <div class="author-name-premium" style="margin-bottom:8px;">{{ $step[1] }}</div>
          <p class="testimonial-text-premium">{{ $step[2] }}</p>
        </div>
      @endforeach
    </div>
  </div>
</section>

<!-- FINAL CTA SECTION -->
<section class="final-cta-premium">
  <div class="cta-bg-pattern"></div>
  <div class="cta-glow cta-glow-1"></div>
  <div class="cta-glow cta-glow-2"></div>

  <div class="container-premium">
    <div class="final-cta-content">
      <h2 class="final-cta-title">Ready to Get Started?</h2>
      <p class="final-cta-subtitle">Parents post tuition jobs for free. Tutors find students near them.</p>

      <div class="final-cta-buttons">
        <a href="{{ route('register.tutor') }}" class="final-cta-btn primary">
          <svg class="final-btn-icon" fill="none" stroke="currentColor" viewBox="0 0 24 24">
            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M21 13.255A23.931 23.931 0 0112 15c-3.183 0-6.22-.62-9-1.745M16 6V4a2 2 0 00-2-2h-4a2 2 0 00-2 2v2m4 6h.01M5 20h14a2 2 0 002-2V8a2 2 0 00-2-2H5a2 2 0 00-2 2v10a2 2 0 002 2z"/>
          </svg>
          <span>Register as Tutor</span>
        </a>
        <a href="{{ route('register.parent') }}" class="final-cta-btn secondary">
          <svg class="final-btn-icon" fill="none" stroke="currentColor" viewBox="0 0 24 24">
            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M21 21l-6-6m2-5a7 7 0 11-14 0 7 7 0 0114 0z"/>
          </svg>
          <span>Find a Tutor</span>
        </a>
      </div>

      <div class="final-trust-badges">
        <div class="trust-badge-item">
          <svg fill="currentColor" viewBox="0 0 20 20">
            <path fill-rule="evenodd" d="M2.166 4.999A11.954 11.954 0 0010 1.944 11.954 11.954 0 0017.834 5c.11.65.166 1.32.166 2.001 0 5.225-3.34 9.67-8 11.317C5.34 16.67 2 12.225 2 7c0-.682.057-1.35.166-2.001zm11.541 3.708a1 1 0 00-1.414-1.414L9 10.586 7.707 9.293a1 1 0 00-1.414 1.414l2 2a1 1 0 001.414 0l4-4z" clip-rule="evenodd"/>
          </svg>
          <span>Contact details shared through TutorHub</span>
        </div>
        <div class="trust-badge-item">
          <svg fill="currentColor" viewBox="0 0 20 20">
            <path fill-rule="evenodd" d="M6.267 3.455a3.066 3.066 0 001.745-.723 3.066 3.066 0 013.976 0 3.066 3.066 0 001.745.723 3.066 3.066 0 012.812 2.812c.051.643.304 1.254.723 1.745a3.066 3.066 0 010 3.976 3.066 3.066 0 00-.723 1.745 3.066 3.066 0 01-2.812 2.812 3.066 3.066 0 00-1.745.723 3.066 3.066 0 01-3.976 0 3.066 3.066 0 00-1.745-.723 3.066 3.066 0 01-2.812-2.812 3.066 3.066 0 00-.723-1.745 3.066 3.066 0 010-3.976 3.066 3.066 0 00.723-1.745 3.066 3.066 0 012.812-2.812zm7.44 5.252a1 1 0 00-1.414-1.414L9 10.586 7.707 9.293a1 1 0 00-1.414 1.414l2 2a1 1 0 001.414 0l4-4z" clip-rule="evenodd"/>
          </svg>
          <span>Verification badges</span>
        </div>
        <div class="trust-badge-item">
          <svg fill="currentColor" viewBox="0 0 20 20">
            <path fill-rule="evenodd" d="M11.3 1.046A1 1 0 0112 2v5h4a1 1 0 01.82 1.573l-7 10A1 1 0 018 18v-5H4a1 1 0 01-.82-1.573l7-10a1 1 0 011.12-.38z" clip-rule="evenodd"/>
          </svg>
          <span>Fast Response</span>
        </div>
      </div>
    </div>
  </div>
</section>

<style>
/* I'll include the complete CSS with all sections in the next part due to length */
@import url('https://fonts.googleapis.com/css2?family=Inter:wght@400;500;600;700;800;900&display=swap');

:root {
  --bd-green: #006A4E;
  --bd-green-dark: #004A35;
  --bd-red: #F42A41;
  --success: #00C878;
  --success-dark: #00A868;
  --warning: #F59E0B;
  --gold: #FFD700;
  --white: #FFFFFF;
  --gray-50: #F9FAFB;
  --gray-100: #F3F4F6;
  --gray-200: #E5E7EB;
  --gray-300: #D1D5DB;
  --gray-400: #9CA3AF;
  --gray-500: #6B7280;
  --gray-600: #4B5563;
  --gray-700: #374151;
  --gray-800: #1F2937;
  --gray-900: #111827;
}

* {
  margin: 0;
  padding: 0;
  box-sizing: border-box;
}

body {
  font-family: 'Inter', -apple-system, BlinkMacSystemFont, 'Segoe UI', sans-serif;
  -webkit-font-smoothing: antialiased;
  -moz-osx-font-smoothing: grayscale;
}

/* Hero Section - Premium */
.hero-premium {
  position: relative;
  background: linear-gradient(135deg, var(--bd-green) 0%, var(--bd-green-dark) 50%, var(--bd-red) 100%);
  min-height: 100vh;
  display: flex;
  align-items: center;
  overflow: hidden;
  padding: 120px 0 160px;
}

.hero-bg-pattern {
  position: absolute;
  inset: 0;
  background-image:
    radial-gradient(circle at 20% 20%, rgba(255, 215, 0, 0.08) 0%, transparent 50%),
    radial-gradient(circle at 80% 80%, rgba(0, 200, 120, 0.08) 0%, transparent 50%),
    radial-gradient(circle at 40% 60%, rgba(244, 42, 65, 0.05) 0%, transparent 40%);
  opacity: 0.6;
}

.hero-glow {
  position: absolute;
  width: 600px;
  height: 600px;
  border-radius: 50%;
  filter: blur(100px);
  opacity: 0.15;
  animation: pulse-glow 8s ease-in-out infinite;
}

.hero-glow-1 {
  background: var(--gold);
  top: -100px;
  left: -100px;
}

.hero-glow-2 {
  background: var(--success);
  bottom: -100px;
  right: -100px;
  animation-delay: 4s;
}

@keyframes pulse-glow {
  0%, 100% {
    transform: scale(1);
    opacity: 0.15;
  }
  50% {
    transform: scale(1.1);
    opacity: 0.25;
  }
}

.container-premium {
  max-width: 1600px;
  margin: 0 auto;
  padding: 0 24px;
  position: relative;
  z-index: 1;
}

.hero-grid-premium {
  display: grid;
  grid-template-columns: 1.2fr 1fr;
  gap: 80px;
  align-items: center;
}

@media (max-width: 1200px) {
  .hero-grid-premium {
    grid-template-columns: 1fr;
    gap: 60px;
  }
}

/* Hero Content */
.hero-content-premium {
  animation: slideInUp 1s ease-out;
}

@keyframes slideInUp {
  from {
    opacity: 0;
    transform: translateY(40px);
  }
  to {
    opacity: 1;
    transform: translateY(0);
  }
}

.hero-badge-premium {
  display: inline-flex;
  align-items: center;
  gap: 12px;
  padding: 12px 24px;
  background: rgba(255, 255, 255, 0.1);
  backdrop-filter: blur(20px);
  border-radius: 100px;
  border: 1px solid rgba(255, 255, 255, 0.2);
  margin-bottom: 32px;
  position: relative;
  box-shadow: 0 8px 32px rgba(0, 0, 0, 0.1);
}

.badge-pulse {
  position: absolute;
  left: 12px;
  width: 8px;
  height: 8px;
  background: var(--gold);
  border-radius: 50%;
  animation: pulse 2s ease-in-out infinite;
}

@keyframes pulse {
  0%, 100% {
    opacity: 1;
    transform: scale(1);
  }
  50% {
    opacity: 0.5;
    transform: scale(1.3);
  }
}

.badge-flag {
  width: 24px;
  height: 24px;
}

.badge-text {
  color: white;
  font-size: 14px;
  font-weight: 700;
  letter-spacing: -0.02em;
}

.hero-title-premium {
  display: flex;
  flex-direction: column;
  font-size: 72px;
  font-weight: 900;
  line-height: 1.1;
  margin-bottom: 24px;
  letter-spacing: -0.03em;
}

.title-line-1,
.title-line-3 {
  color: white;
  text-shadow: 0 2px 40px rgba(0, 0, 0, 0.3);
}

.title-highlight {
  background: linear-gradient(120deg, var(--gold) 0%, #FFA500 100%);
  -webkit-background-clip: text;
  -webkit-text-fill-color: transparent;
  background-clip: text;
  text-shadow: 0 0 80px rgba(255, 215, 0, 0.5);
  animation: shimmer 3s ease-in-out infinite;
}

@keyframes shimmer {
  0%, 100% {
    filter: brightness(1);
  }
  50% {
    filter: brightness(1.2);
  }
}

.hero-subtitle-premium {
  font-size: 20px;
  line-height: 1.7;
  color: rgba(255, 255, 255, 0.95);
  margin-bottom: 48px;
  max-width: 650px;
}

.hero-subtitle-premium strong {
  color: white;
  font-weight: 800;
}

/* Premium Search Box - Complete styles included */
.search-box-premium {
  background: white;
  border-radius: 24px;
  padding: 8px;
  box-shadow:
    0 0 0 1px rgba(0, 0, 0, 0.05),
    0 20px 60px -10px rgba(0, 0, 0, 0.3),
    0 0 100px rgba(255, 215, 0, 0.1);
  margin-bottom: 40px;
  max-width: 100%;
}

.search-form-premium {
  display: flex;
  flex-direction: column;
  gap: 8px;
}

.search-tabs-premium {
  display: flex;
  gap: 8px;
  padding: 4px;
  background: var(--gray-100);
  border-radius: 16px;
}

.search-tab {
  flex: 1;
  display: flex;
  align-items: center;
  justify-content: center;
  gap: 8px;
  padding: 12px 20px;
  background: transparent;
  border: none;
  border-radius: 12px;
  font-size: 15px;
  font-weight: 700;
  color: var(--gray-600);
  cursor: pointer;
  transition: all 0.3s cubic-bezier(0.4, 0, 0.2, 1);
}

.search-tab.active {
  background: white;
  color: var(--bd-green);
  box-shadow: 0 1px 3px rgba(0, 0, 0, 0.1);
}

.tab-icon {
  width: 20px;
  height: 20px;
}

.search-inputs-premium {
  display: flex;
  flex-direction: column;
  gap: 12px;
  padding: 12px;
}

.search-main-input {
  position: relative;
  width: 100%;
}

.input-icon {
  position: absolute;
  left: 20px;
  top: 50%;
  transform: translateY(-50%);
  width: 24px;
  height: 24px;
  color: var(--gray-400);
  pointer-events: none;
}

.search-input-premium {
  width: 100%;
  padding: 18px 20px 18px 56px;
  border: 2px solid var(--gray-200);
  border-radius: 16px;
  font-size: 16px;
  font-weight: 500;
  color: var(--gray-900);
  transition: all 0.3s ease;
}

.search-input-premium:focus {
  outline: none;
  border-color: var(--bd-green);
  box-shadow: 0 0 0 4px rgba(0, 106, 78, 0.1);
}

.search-input-premium::placeholder {
  color: var(--gray-400);
}

.search-filters-row {
  display: grid;
  grid-template-columns: 1fr 1fr auto;
  gap: 12px;
}

@media (max-width: 768px) {
  .search-filters-row {
    grid-template-columns: 1fr;
  }
}

.filter-group {
  position: relative;
}

.filter-icon {
  position: absolute;
  left: 16px;
  top: 50%;
  transform: translateY(-50%);
  width: 20px;
  height: 20px;
  color: var(--gray-400);
  pointer-events: none;
  z-index: 1;
}

.filter-select {
  width: 100%;
  padding: 14px 16px 14px 48px;
  border: 2px solid var(--gray-200);
  border-radius: 12px;
  font-size: 15px;
  font-weight: 600;
  color: var(--gray-700);
  background: white;
  cursor: pointer;
  transition: all 0.3s ease;
  appearance: none;
  background-image: url("data:image/svg+xml,%3Csvg xmlns='http://www.w3.org/2000/svg' fill='none' viewBox='0 0 24 24' stroke='%236B7280'%3E%3Cpath stroke-linecap='round' stroke-linejoin='round' stroke-width='2' d='M19 9l-7 7-7-7'%3E%3C/path%3E%3C/svg%3E");
  background-repeat: no-repeat;
  background-position: right 12px center;
  background-size: 20px;
  padding-right: 40px;
}

.filter-select:focus {
  outline: none;
  border-color: var(--bd-green);
  box-shadow: 0 0 0 4px rgba(0, 106, 78, 0.1);
}

.search-button-premium {
  display: flex;
  align-items: center;
  justify-content: center;
  gap: 10px;
  padding: 14px 32px;
  background: linear-gradient(135deg, var(--bd-green) 0%, var(--success) 100%);
  border: none;
  border-radius: 12px;
  font-size: 16px;
  font-weight: 800;
  color: white;
  cursor: pointer;
  white-space: nowrap;
  box-shadow: 0 4px 16px rgba(0, 106, 78, 0.3);
  transition: all 0.3s cubic-bezier(0.4, 0, 0.2, 1);
}

.search-button-premium:hover {
  transform: translateY(-2px);
  box-shadow: 0 8px 24px rgba(0, 106, 78, 0.4);
}

.search-button-premium:active {
  transform: translateY(0);
}

.button-icon-premium {
  width: 20px;
  height: 20px;
}

/* Popular Tags */
.popular-tags-premium {
  display: flex;
  align-items: center;
  gap: 8px;
  flex-wrap: wrap;
  padding: 16px 12px 8px;
}

.tags-label {
  font-size: 13px;
  font-weight: 700;
  color: var(--gray-500);
}

.tag-btn {
  padding: 8px 16px;
  background: var(--gray-100);
  border-radius: 20px;
  font-size: 13px;
  font-weight: 700;
  color: var(--gray-700);
  text-decoration: none;
  transition: all 0.2s ease;
}

.tag-btn:hover {
  background: var(--bd-green);
  color: white;
  transform: translateY(-2px);
}

/* Stats Grid Premium */
.stats-grid-premium {
  display: grid;
  grid-template-columns: repeat(4, 1fr);
  gap: 16px;
}

@media (max-width: 768px) {
  .stats-grid-premium {
    grid-template-columns: repeat(2, 1fr);
  }
}

.stat-card-premium {
  display: flex;
  align-items: center;
  gap: 16px;
  padding: 20px;
  background: rgba(255, 255, 255, 0.1);
  backdrop-filter: blur(20px);
  border-radius: 16px;
  border: 1px solid rgba(255, 255, 255, 0.2);
  transition: all 0.3s ease;
}

.stat-card-premium:hover {
  background: rgba(255, 255, 255, 0.15);
  transform: translateY(-4px);
}

.stat-icon-premium {
  width: 48px;
  height: 48px;
  display: flex;
  align-items: center;
  justify-content: center;
  background: rgba(255, 255, 255, 0.2);
  border-radius: 12px;
  flex-shrink: 0;
}

.stat-icon-premium svg {
  width: 24px;
  height: 24px;
  color: white;
}

.stat-icon-premium.success {
  background: rgba(0, 200, 120, 0.3);
}

.stat-icon-premium.warning {
  background: rgba(245, 158, 11, 0.3);
}

.stat-icon-premium.gold {
  background: rgba(255, 215, 0, 0.3);
}

.stat-content {
  flex: 1;
}

.stat-number {
  font-size: 24px;
  font-weight: 900;
  color: white;
  line-height: 1;
  margin-bottom: 4px;
  text-shadow: 0 2px 10px rgba(0, 0, 0, 0.2);
}

.stat-label {
  font-size: 12px;
  font-weight: 600;
  color: rgba(255, 255, 255, 0.9);
}

/* Hero Card Premium */
.hero-card-premium {
  position: relative;
  animation: slideInRight 1s ease-out 0.3s both;
}

@keyframes slideInRight {
  from {
    opacity: 0;
    transform: translateX(40px);
  }
  to {
    opacity: 1;
    transform: translateX(0);
  }
}

.card-glow {
  position: absolute;
  inset: -20px;
  background: linear-gradient(135deg, var(--gold) 0%, var(--success) 100%);
  border-radius: 32px;
  filter: blur(40px);
  opacity: 0.2;
  animation: pulse-card 4s ease-in-out infinite;
}

@keyframes pulse-card {
  0%, 100% {
    opacity: 0.2;
  }
  50% {
    opacity: 0.3;
  }
}

.card-content-premium {
  position: relative;
  background: rgba(255, 255, 255, 0.1);
  backdrop-filter: blur(40px);
  border-radius: 32px;
  padding: 48px;
  border: 2px solid rgba(255, 255, 255, 0.2);
  box-shadow:
    0 20px 60px rgba(0, 0, 0, 0.3),
    inset 0 1px 0 rgba(255, 255, 255, 0.1);
}

/* Illustration Premium */
.illustration-premium {
  position: relative;
  height: 300px;
  margin-bottom: 40px;
}

.illustration-circle {
  position: absolute;
  border-radius: 50%;
  animation: float-circle 6s ease-in-out infinite;
}

.circle-1 {
  width: 80px;
  height: 80px;
  background: linear-gradient(135deg, var(--gold) 0%, #FFA500 100%);
  top: 20px;
  left: 20px;
  opacity: 0.2;
}

.circle-2 {
  width: 120px;
  height: 120px;
  background: linear-gradient(135deg, var(--success) 0%, var(--bd-green) 100%);
  bottom: 40px;
  right: 40px;
  opacity: 0.2;
  animation-delay: 2s;
}

.circle-3 {
  width: 60px;
  height: 60px;
  background: linear-gradient(135deg, var(--bd-red) 0%, #FF6B6B 100%);
  top: 50%;
  right: 20px;
  opacity: 0.2;
  animation-delay: 4s;
}

@keyframes float-circle {
  0%, 100% {
    transform: translate(0, 0);
  }
  33% {
    transform: translate(10px, -10px);
  }
  66% {
    transform: translate(-10px, 10px);
  }
}

.illustration-main {
  position: absolute;
  top: 50%;
  left: 50%;
  transform: translate(-50%, -50%);
  width: 200px;
  height: 200px;
}

.illustration-svg {
  width: 100%;
  height: 100%;
  filter: drop-shadow(0 20px 40px rgba(0, 0, 0, 0.3));
}

.float-element {
  position: absolute;
  font-size: 32px;
  animation: float 4s ease-in-out infinite;
}

.float-1 {
  top: 20px;
  left: 40px;
  animation-delay: 0s;
}

.float-2 {
  top: 40px;
  right: 40px;
  animation-delay: 1s;
}

.float-3 {
  bottom: 60px;
  left: 20px;
  animation-delay: 2s;
}

.float-4 {
  bottom: 40px;
  right: 60px;
  animation-delay: 3s;
}

@keyframes float {
  0%, 100% {
    transform: translateY(0) rotate(0deg);
  }
  25% {
    transform: translateY(-15px) rotate(5deg);
  }
  75% {
    transform: translateY(15px) rotate(-5deg);
  }
}

/* CTA Section Premium */
.cta-section-premium {
  text-align: center;
}

.cta-title-premium {
  font-size: 32px;
  font-weight: 900;
  color: white;
  margin-bottom: 8px;
  text-shadow: 0 2px 20px rgba(0, 0, 0, 0.3);
}

.cta-subtitle-premium {
  font-size: 16px;
  color: rgba(255, 255, 255, 0.9);
  margin-bottom: 32px;
}

.cta-buttons-premium {
  display: flex;
  flex-direction: column;
  gap: 16px;
  margin-bottom: 32px;
}

.cta-btn-premium {
  display: flex;
  align-items: center;
  gap: 16px;
  padding: 20px 24px;
  background: rgba(255, 255, 255, 0.95);
  border-radius: 16px;
  text-decoration: none;
  transition: all 0.3s cubic-bezier(0.4, 0, 0.2, 1);
  box-shadow: 0 4px 16px rgba(0, 0, 0, 0.1);
  position: relative;
  overflow: hidden;
}

.cta-btn-premium::before {
  content: '';
  position: absolute;
  inset: 0;
  background: linear-gradient(135deg, var(--bd-green) 0%, var(--success) 100%);
  opacity: 0;
  transition: opacity 0.3s ease;
}

.cta-btn-premium.tutor::before {
  background: linear-gradient(135deg, var(--bd-green) 0%, var(--success) 100%);
}

.cta-btn-premium.parent::before {
  background: linear-gradient(135deg, var(--success) 0%, var(--bd-green) 100%);
}

.cta-btn-premium:hover {
  transform: translateY(-4px);
  box-shadow: 0 12px 32px rgba(0, 0, 0, 0.2);
}

.cta-btn-premium:hover::before {
  opacity: 1;
}

.cta-btn-premium:hover .btn-icon-wrapper,
.cta-btn-premium:hover .btn-content,
.cta-btn-premium:hover .btn-arrow {
  position: relative;
  z-index: 1;
  color: white;
}

.btn-icon-wrapper {
  width: 48px;
  height: 48px;
  display: flex;
  align-items: center;
  justify-content: center;
  background: linear-gradient(135deg, var(--bd-green) 0%, var(--success) 100%);
  border-radius: 12px;
  flex-shrink: 0;
  transition: all 0.3s ease;
}

.cta-btn-premium.tutor .btn-icon-wrapper {
  background: linear-gradient(135deg, var(--bd-green) 0%, var(--success) 100%);
}

.cta-btn-premium.parent .btn-icon-wrapper {
  background: linear-gradient(135deg, var(--success) 0%, var(--bd-green) 100%);
}

.cta-btn-premium:hover .btn-icon-wrapper {
  background: rgba(255, 255, 255, 0.2);
}

.btn-icon-wrapper svg {
  width: 24px;
  height: 24px;
  color: white;
}

.btn-content {
  flex: 1;
  text-align: left;
  transition: color 0.3s ease;
}

.btn-title {
  display: block;
  font-size: 16px;
  font-weight: 800;
  color: var(--gray-900);
  margin-bottom: 2px;
  transition: color 0.3s ease;
}

.btn-subtitle {
  display: block;
  font-size: 13px;
  font-weight: 600;
  color: var(--gray-600);
  transition: color 0.3s ease;
}

.cta-btn-premium:hover .btn-subtitle {
  color: rgba(255, 255, 255, 0.9);
}

.btn-arrow {
  width: 24px;
  height: 24px;
  color: var(--gray-400);
  flex-shrink: 0;
  transition: all 0.3s ease;
}

.cta-btn-premium:hover .btn-arrow {
  transform: translateX(4px);
}

/* Trust Indicators */
.trust-indicators-premium {
  display: flex;
  justify-content: center;
  gap: 24px;
  padding: 16px;
  background: rgba(255, 255, 255, 0.1);
  border-radius: 12px;
  border: 1px solid rgba(255, 255, 255, 0.1);
}

.trust-item {
  display: flex;
  align-items: center;
  gap: 8px;
  color: white;
  font-size: 13px;
  font-weight: 700;
}

.trust-icon {
  width: 20px;
  height: 20px;
  color: var(--gold);
}

/* Wave Bottom */
.wave-bottom {
  position: absolute;
  bottom: 0;
  left: 0;
  right: 0;
  width: 100%;
  line-height: 0;
}

.wave-bottom svg {
  width: 100%;
  height: auto;
  display: block;
}

/* Section Styles */
.section-premium {
  padding: 100px 0;
}

.featured-tutors {
  background: white;
}

.recent-jobs {
  background: var(--gray-50);
}

.how-it-works {
  background: white;
}

.categories-section {
  background: var(--gray-50);
}

.testimonials-section {
  background: white;
}

/* Section Headers */
.section-header-premium {
  display: flex;
  justify-content: space-between;
  align-items: flex-end;
  margin-bottom: 60px;
}

@media (max-width: 768px) {
  .section-header-premium {
    flex-direction: column;
    align-items: flex-start;
    gap: 24px;
  }
}

.section-header-content {
  flex: 1;
}

.section-badge {
  display: inline-block;
  padding: 6px 16px;
  background: var(--gray-100);
  color: var(--gray-700);
  font-size: 13px;
  font-weight: 700;
  border-radius: 20px;
  margin-bottom: 16px;
}

.section-title-premium {
  font-size: 48px;
  font-weight: 900;
  color: var(--gray-900);
  margin-bottom: 12px;
  letter-spacing: -0.02em;
}

.section-subtitle-premium {
  font-size: 18px;
  color: var(--gray-600);
  max-width: 600px;
}

.section-header-center-premium {
  text-align: center;
  margin-bottom: 60px;
  max-width: 700px;
  margin-left: auto;
  margin-right: auto;
}

.view-all-btn-premium {
  display: inline-flex;
  align-items: center;
  gap: 10px;
  padding: 14px 28px;
  background: var(--bd-green);
  color: white;
  font-size: 15px;
  font-weight: 700;
  text-decoration: none;
  border-radius: 12px;
  transition: all 0.3s ease;
}

.view-all-btn-premium:hover {
  background: var(--bd-green-dark);
  transform: translateX(4px);
}

.btn-arrow-icon {
  width: 20px;
  height: 20px;
}

/* Tutor Cards Premium */
.tutors-grid-premium {
  display: grid;
  grid-template-columns: repeat(3, 1fr);
  gap: 32px;
}

@media (max-width: 1024px) {
  .tutors-grid-premium {
    grid-template-columns: repeat(2, 1fr);
  }
}

@media (max-width: 640px) {
  .tutors-grid-premium {
    grid-template-columns: 1fr;
  }
}

.tutor-card-premium {
  background: white;
  border-radius: 20px;
  padding: 28px;
  border: 2px solid var(--gray-200);
  transition: all 0.4s cubic-bezier(0.4, 0, 0.2, 1);
}

.tutor-card-premium:hover {
  transform: translateY(-8px);
  border-color: var(--bd-green);
  box-shadow: 0 24px 48px rgba(0, 106, 78, 0.12);
}

.tutor-card-header {
  text-align: center;
  margin-bottom: 24px;
}

.tutor-avatar-premium {
  position: relative;
  width: 120px;
  height: 120px;
  margin: 0 auto 20px;
}

.tutor-avatar-premium img {
  width: 100%;
  height: 100%;
  border-radius: 50%;
  object-fit: cover;
  border: 4px solid var(--gray-100);
}

.avatar-placeholder-premium {
  width: 100%;
  height: 100%;
  border-radius: 50%;
  background: linear-gradient(135deg, var(--bd-green) 0%, var(--success) 100%);
  display: flex;
  align-items: center;
  justify-content: center;
  font-size: 40px;
  font-weight: 900;
  color: white;
  border: 4px solid var(--gray-100);
}

.verified-badge-premium {
  position: absolute;
  bottom: 0;
  right: 0;
  width: 36px;
  height: 36px;
  background: var(--success);
  border-radius: 50%;
  display: flex;
  align-items: center;
  justify-content: center;
  border: 4px solid white;
  box-shadow: 0 2px 8px rgba(0, 200, 120, 0.3);
}

.verified-badge-premium svg {
  width: 20px;
  height: 20px;
  color: white;
}

.tutor-rating-premium {
  margin-bottom: 16px;
}

.stars {
  font-size: 16px;
  margin-bottom: 4px;
}

.rating-text {
  font-size: 13px;
  color: var(--gray-600);
  font-weight: 600;
}

.tutor-info-premium {
  margin-bottom: 20px;
}

.tutor-name-premium {
  font-size: 22px;
  font-weight: 800;
  color: var(--gray-900);
  margin-bottom: 6px;
}

.tutor-education-premium {
  font-size: 14px;
  color: var(--gray-600);
  line-height: 1.5;
}

.tutor-subjects-premium {
  display: flex;
  flex-wrap: wrap;
  gap: 8px;
  margin-bottom: 20px;
}

.subject-tag-premium {
  padding: 6px 14px;
  background: var(--gray-100);
  border-radius: 20px;
  font-size: 12px;
  font-weight: 700;
  color: var(--gray-700);
}

.subject-tag-premium.more {
  background: var(--bd-green);
  color: white;
}

.tutor-stats-premium {
  display: flex;
  gap: 16px;
  padding: 16px;
  background: var(--gray-50);
  border-radius: 12px;
  margin-bottom: 20px;
}

.stat-item-premium {
  flex: 1;
  display: flex;
  align-items: center;
  gap: 8px;
  font-size: 13px;
  font-weight: 600;
  color: var(--gray-700);
}

.stat-icon-mini {
  width: 18px;
  height: 18px;
  color: var(--gray-500);
  flex-shrink: 0;
}

.view-profile-btn-premium {
  display: flex;
  align-items: center;
  justify-content: center;
  gap: 8px;
  width: 100%;
  padding: 14px;
  background: var(--bd-green);
  color: white;
  font-size: 15px;
  font-weight: 700;
  text-decoration: none;
  border-radius: 12px;
  transition: all 0.3s ease;
}

.view-profile-btn-premium:hover {
  background: var(--bd-green-dark);
  transform: translateY(-2px);
}

.btn-arrow-sm {
  width: 18px;
  height: 18px;
}

/* Job Cards Premium */
.jobs-grid-premium {
  display: grid;
  grid-template-columns: repeat(2, 1fr);
  gap: 32px;
}

@media (max-width: 768px) {
  .jobs-grid-premium {
    grid-template-columns: 1fr;
  }
}

.job-card-premium {
  background: white;
  border-radius: 20px;
  padding: 28px;
  border: 2px solid var(--gray-200);
  border-left: 4px solid var(--bd-green);
  transition: all 0.4s cubic-bezier(0.4, 0, 0.2, 1);
}

.job-card-premium:hover {
  transform: translateY(-4px);
  box-shadow: 0 16px 32px rgba(0, 0, 0, 0.1);
}

.job-card-header {
  display: flex;
  justify-content: space-between;
  align-items: center;
  margin-bottom: 16px;
}

.job-badges {
  display: flex;
  gap: 8px;
}

.job-type-badge {
  padding: 4px 12px;
  background: rgba(0, 106, 78, 0.1);
  color: var(--bd-green);
  font-size: 11px;
  font-weight: 800;
  border-radius: 12px;
  text-transform: uppercase;
  letter-spacing: 0.5px;
}

.job-category-badge {
  padding: 4px 12px;
  background: var(--gray-100);
  color: var(--gray-700);
  font-size: 11px;
  font-weight: 700;
  border-radius: 12px;
}

.job-time {
  font-size: 12px;
  color: var(--gray-500);
  font-weight: 600;
}

.job-title-premium {
  font-size: 20px;
  font-weight: 800;
  color: var(--gray-900);
  margin-bottom: 20px;
  line-height: 1.4;
}

.job-details-grid {
  display: grid;
  grid-template-columns: repeat(2, 1fr);
  gap: 12px;
  margin-bottom: 20px;
}

.job-detail-item {
  display: flex;
  align-items: center;
  gap: 8px;
  font-size: 14px;
  font-weight: 600;
  color: var(--gray-700);
}

.detail-icon-mini {
  width: 18px;
  height: 18px;
  color: var(--gray-500);
  flex-shrink: 0;
}

.job-detail-item.salary {
  color: var(--bd-green);
}

.job-detail-item.salary .detail-icon-mini {
  color: var(--bd-green);
}

.job-subjects-premium {
  font-size: 14px;
  color: var(--gray-600);
  line-height: 1.6;
  margin-bottom: 20px;
  padding: 12px;
  background: var(--gray-50);
  border-radius: 8px;
}

.job-subjects-premium strong {
  color: var(--gray-900);
  font-weight: 700;
}

.more-subjects {
  color: var(--bd-green);
  font-weight: 700;
}

.job-actions-premium {
  display: flex;
  gap: 12px;
}

.job-details-btn,
.job-apply-btn {
  flex: 1;
  padding: 12px;
  border-radius: 10px;
  font-size: 14px;
  font-weight: 700;
  text-align: center;
  text-decoration: none;
  transition: all 0.3s ease;
}

.job-details-btn {
  background: var(--gray-100);
  color: var(--gray-700);
}

.job-details-btn:hover {
  background: var(--gray-200);
}

.job-apply-btn {
  background: var(--bd-green);
  color: white;
}

.job-apply-btn:hover {
  background: var(--bd-green-dark);
  transform: translateY(-2px);
}

.section-cta-center {
  text-align: center;
  margin-top: 60px;
}

.cta-large-btn-premium {
  display: inline-flex;
  align-items: center;
  gap: 12px;
  padding: 18px 40px;
  background: var(--bd-green);
  color: white;
  font-size: 16px;
  font-weight: 800;
  text-decoration: none;
  border-radius: 16px;
  box-shadow: 0 4px 16px rgba(0, 106, 78, 0.3);
  transition: all 0.3s ease;
}

.cta-large-btn-premium:hover {
  background: var(--bd-green-dark);
  transform: translateY(-4px);
  box-shadow: 0 8px 24px rgba(0, 106, 78, 0.4);
}

/* How It Works */
.how-it-works-grid {
  display: grid;
  grid-template-columns: repeat(2, 1fr);
  gap: 40px;
  max-width: 1200px;
  margin: 0 auto;
  align-items: start;
}

@media (max-width: 968px) {
  .how-it-works-grid {
    grid-template-columns: 1fr;
  }
}

.how-card-premium {
  background: white;
  border-radius: 24px;
  padding: 40px;
  border: 2px solid var(--gray-200);
  transition: all 0.4s ease;
}

.how-card-premium:hover {
  transform: translateY(-8px);
  box-shadow: 0 20px 40px rgba(0, 0, 0, 0.1);
}

.parent-how {
  border-top: 4px solid var(--bd-green);
}

.tutor-how {
  border-top: 4px solid var(--success);
}

.how-card-icon {
  width: 64px;
  height: 64px;
  background: linear-gradient(135deg, var(--bd-green) 0%, var(--success) 100%);
  border-radius: 16px;
  display: flex;
  align-items: center;
  justify-content: center;
  margin-bottom: 24px;
}

.tutor-how .how-card-icon {
  background: linear-gradient(135deg, var(--success) 0%, var(--bd-green) 100%);
}

.how-card-icon svg {
  width: 32px;
  height: 32px;
  color: white;
}

.how-card-title {
  font-size: 28px;
  font-weight: 900;
  color: var(--gray-900);
  margin-bottom: 32px;
}

.steps-list-premium {
  display: flex;
  flex-direction: column;
  gap: 24px;
  margin-bottom: 32px;
}

.step-premium {
  display: flex;
  gap: 16px;
}

.step-number-premium {
  width: 48px;
  height: 48px;
  flex-shrink: 0;
  background: var(--gray-100);
  border-radius: 50%;
  display: flex;
  align-items: center;
  justify-content: center;
  font-size: 20px;
  font-weight: 900;
  color: var(--bd-green);
}

.tutor-how .step-number-premium {
  color: var(--success);
}

.step-content-premium {
  flex: 1;
}

.step-content-premium h4 {
  font-size: 17px;
  font-weight: 800;
  color: var(--gray-900);
  margin-bottom: 6px;
}

.step-content-premium p {
  font-size: 14px;
  color: var(--gray-600);
  line-height: 1.6;
}

.how-card-btn {
  display: inline-flex;
  align-items: center;
  gap: 10px;
  width: 100%;
  padding: 16px 24px;
  justify-content: center;
  border-radius: 12px;
  font-size: 16px;
  font-weight: 800;
  text-decoration: none;
  transition: all 0.3s ease;
}

.parent-btn {
  background: var(--bd-green);
  color: white;
}

.parent-btn:hover {
  background: var(--bd-green-dark);
  transform: translateY(-2px);
}

.tutor-btn {
  background: var(--success);
  color: white;
}

.tutor-btn:hover {
  background: var(--success-dark);
  transform: translateY(-2px);
}

/* Categories */
.categories-grid-premium {
  display: grid;
  grid-template-columns: repeat(4, 1fr);
  gap: 24px;
}

@media (max-width: 968px) {
  .categories-grid-premium {
    grid-template-columns: repeat(3, 1fr);
  }
}

@media (max-width: 640px) {
  .categories-grid-premium {
    grid-template-columns: repeat(2, 1fr);
  }
}

.category-card-premium {
  background: white;
  padding: 32px 24px;
  border-radius: 20px;
  border: 2px solid var(--gray-200);
  text-align: center;
  text-decoration: none;
  transition: all 0.4s ease;
  position: relative;
  overflow: hidden;
}

.category-card-premium:hover {
  transform: translateY(-8px);
  border-color: var(--bd-green);
  box-shadow: 0 16px 32px rgba(0, 106, 78, 0.1);
}

.category-icon-premium {
  font-size: 64px;
  margin-bottom: 16px;
  transition: transform 0.3s ease;
}

.category-card-premium:hover .category-icon-premium {
  transform: scale(1.1);
}

.category-name-premium {
  font-size: 17px;
  font-weight: 800;
  color: var(--gray-900);
  margin-bottom: 8px;
}

.category-arrow {
  width: 24px;
  height: 24px;
  margin: 0 auto;
  color: var(--gray-400);
  transition: all 0.3s ease;
}

.category-card-premium:hover .category-arrow {
  color: var(--bd-green);
  transform: translateX(4px);
}

/* Testimonials */
.testimonials-grid-premium {
  display: grid;
  grid-template-columns: repeat(3, 1fr);
  gap: 32px;
}

@media (max-width: 968px) {
  .testimonials-grid-premium {
    grid-template-columns: 1fr;
  }
}

.testimonial-card-premium {
  background: white;
  padding: 36px;
  border-radius: 20px;
  border: 2px solid var(--gray-200);
  transition: all 0.4s ease;
}

.testimonial-card-premium:hover {
  transform: translateY(-4px);
  border-color: var(--bd-green);
  box-shadow: 0 16px 32px rgba(0, 0, 0, 0.08);
}

.quote-mark-premium {
  font-size: 72px;
  color: var(--gray-200);
  line-height: 1;
  margin-bottom: 20px;
}

.testimonial-text-premium {
  font-size: 15px;
  line-height: 1.8;
  color: var(--gray-700);
  margin-bottom: 24px;
}

.testimonial-footer {
  display: flex;
  align-items: center;
  gap: 16px;
  padding-top: 24px;
  border-top: 1px solid var(--gray-200);
  margin-bottom: 16px;
}

.testimonial-avatar-premium {
  width: 56px;
  height: 56px;
  background: linear-gradient(135deg, var(--bd-green) 0%, var(--success) 100%);
  border-radius: 50%;
  display: flex;
  align-items: center;
  justify-content: center;
  font-size: 20px;
  font-weight: 900;
  color: white;
  flex-shrink: 0;
}

.testimonial-author-info {
  flex: 1;
}

.author-name-premium {
  font-size: 16px;
  font-weight: 800;
  color: var(--gray-900);
  margin-bottom: 4px;
}

.author-title-premium {
  font-size: 13px;
  color: var(--gray-600);
}

.testimonial-rating {
  font-size: 16px;
}

/* Final CTA Section */
.final-cta-premium {
  position: relative;
  background: linear-gradient(135deg, var(--bd-green) 0%, var(--bd-green-dark) 50%, var(--bd-red) 100%);
  padding: 120px 32px;
  overflow: hidden;
}

.cta-bg-pattern {
  position: absolute;
  inset: 0;
  background-image:
    radial-gradient(circle at 20% 50%, rgba(255, 215, 0, 0.1) 0%, transparent 50%),
    radial-gradient(circle at 80% 20%, rgba(255, 255, 255, 0.05) 0%, transparent 40%);
}

.cta-glow {
  position: absolute;
  width: 500px;
  height: 500px;
  border-radius: 50%;
  filter: blur(100px);
  opacity: 0.2;
  animation: pulse-glow 8s ease-in-out infinite;
}

.cta-glow-1 {
  background: var(--gold);
  top: -100px;
  left: -100px;
}

.cta-glow-2 {
  background: var(--success);
  bottom: -100px;
  right: -100px;
  animation-delay: 4s;
}

.final-cta-content {
  position: relative;
  z-index: 1;
  max-width: 900px;
  margin: 0 auto;
  text-align: center;
}

.final-cta-title {
  font-size: 56px;
  font-weight: 900;
  color: white;
  margin-bottom: 20px;
  text-shadow: 0 2px 40px rgba(0, 0, 0, 0.3);
  letter-spacing: -0.02em;
}

.final-cta-subtitle {
  font-size: 20px;
  color: rgba(255, 255, 255, 0.95);
  margin-bottom: 48px;
  line-height: 1.6;
}

.final-cta-buttons {
  display: flex;
  justify-content: center;
  gap: 16px;
  margin-bottom: 48px;
  flex-wrap: wrap;
}

.final-cta-btn {
  display: inline-flex;
  align-items: center;
  gap: 12px;
  padding: 18px 36px;
  border-radius: 14px;
  font-size: 17px;
  font-weight: 800;
  text-decoration: none;
  transition: all 0.4s cubic-bezier(0.4, 0, 0.2, 1);
}

.final-cta-btn.primary {
  background: white;
  color: var(--bd-green);
  box-shadow: 0 8px 32px rgba(255, 255, 255, 0.3);
}

.final-cta-btn.primary:hover {
  transform: translateY(-4px);
  box-shadow: 0 16px 48px rgba(255, 255, 255, 0.4);
}

.final-cta-btn.secondary {
  background: rgba(255, 255, 255, 0.15);
  color: white;
  border: 2px solid white;
  backdrop-filter: blur(10px);
}

.final-cta-btn.secondary:hover {
  background: white;
  color: var(--bd-green);
}

.final-btn-icon {
  width: 22px;
  height: 22px;
}

.final-trust-badges {
  display: flex;
  justify-content: center;
  gap: 40px;
  flex-wrap: wrap;
}

.trust-badge-item {
  display: flex;
  align-items: center;
  gap: 10px;
  color: white;
  font-size: 15px;
  font-weight: 700;
}

.trust-badge-item svg {
  width: 24px;
  height: 24px;
  color: var(--gold);
}

/* Responsive Typography */
@media (max-width: 1024px) {
  .hero-title-premium {
    font-size: 56px;
  }

  .section-title-premium {
    font-size: 40px;
  }
}

@media (max-width: 768px) {
  .hero-premium {
    padding: 80px 0 120px;
  }

  .container-premium {
    padding: 0 20px;
  }

  .hero-title-premium {
    font-size: 40px;
  }

  .hero-subtitle-premium {
    font-size: 16px;
  }

  .section-title-premium {
    font-size: 32px;
  }

  .card-content-premium {
    padding: 32px 24px;
  }

  .stats-grid-premium {
    gap: 12px;
  }

  .stat-card-premium {
    padding: 16px 12px;
  }

  .stat-icon-premium {
    width: 40px;
    height: 40px;
  }

  .stat-icon-premium svg {
    width: 20px;
    height: 20px;
  }

  .stat-number {
    font-size: 20px;
  }

  .stat-label {
    font-size: 11px;
  }

  .final-cta-title {
    font-size: 36px;
  }

  .final-cta-subtitle {
    font-size: 16px;
  }
}

@media (max-width: 640px) {
  .hero-title-premium {
    font-size: 32px;
  }

  .cta-title-premium {
    font-size: 24px;
  }

  .section-title-premium {
    font-size: 28px;
  }

  .final-cta-title {
    font-size: 28px;
  }
}
</style>

@endsection
