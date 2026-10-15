@php
  $topAreas = \App\Models\Area::where('parent_id', function($query) {
    $query->select('id')->from('areas')->where('slug', 'dhaka')->limit(1);
  })->where('is_active', true)->orderBy('sort_order')->limit(5)->get();

  $topSubjects = \App\Models\Subject::where('is_active', true)->orderBy('sort_order')->limit(5)->get();

  $topLevels = \App\Models\Level::where('is_active', true)->orderBy('sort_order')->limit(5)->get();
@endphp

<footer class="footer-premium">
  <div class="footer-main">
    <div class="container-premium">
      <div class="footer-grid">

        <!-- Company Info -->
        <div class="footer-column">
          <div class="footer-brand">
            <div class="footer-logo">
              <div class="logo"><span>T</span></div>
              <div>
                <div class="brand-title">Tutor<span>Hub</span></div>
                <div class="brand-sub">Tuition made easy</div>
              </div>
            </div>
            <p class="footer-description">
              Bangladesh's most trusted tutor marketplace connecting students with qualified tutors across all subjects and levels.
            </p>
            <div class="footer-social">
              <a href="#" class="social-link" aria-label="Facebook">
                <svg fill="currentColor" viewBox="0 0 24 24">
                  <path d="M24 12.073c0-6.627-5.373-12-12-12s-12 5.373-12 12c0 5.99 4.388 10.954 10.125 11.854v-8.385H7.078v-3.47h3.047V9.43c0-3.007 1.792-4.669 4.533-4.669 1.312 0 2.686.235 2.686.235v2.953H15.83c-1.491 0-1.956.925-1.956 1.874v2.25h3.328l-.532 3.47h-2.796v8.385C19.612 23.027 24 18.062 24 12.073z"/>
                </svg>
              </a>
              <a href="#" class="social-link" aria-label="Twitter">
                <svg fill="currentColor" viewBox="0 0 24 24">
                  <path d="M23.953 4.57a10 10 0 01-2.825.775 4.958 4.958 0 002.163-2.723c-.951.555-2.005.959-3.127 1.184a4.92 4.92 0 00-8.384 4.482C7.69 8.095 4.067 6.13 1.64 3.162a4.822 4.822 0 00-.666 2.475c0 1.71.87 3.213 2.188 4.096a4.904 4.904 0 01-2.228-.616v.06a4.923 4.923 0 003.946 4.827 4.996 4.996 0 01-2.212.085 4.936 4.936 0 004.604 3.417 9.867 9.867 0 01-6.102 2.105c-.39 0-.779-.023-1.17-.067a13.995 13.995 0 007.557 2.209c9.053 0 13.998-7.496 13.998-13.985 0-.21 0-.42-.015-.63A9.935 9.935 0 0024 4.59z"/>
                </svg>
              </a>
              <a href="#" class="social-link" aria-label="LinkedIn">
                <svg fill="currentColor" viewBox="0 0 24 24">
                  <path d="M20.447 20.452h-3.554v-5.569c0-1.328-.027-3.037-1.852-3.037-1.853 0-2.136 1.445-2.136 2.939v5.667H9.351V9h3.414v1.561h.046c.477-.9 1.637-1.85 3.37-1.85 3.601 0 4.267 2.37 4.267 5.455v6.286zM5.337 7.433c-1.144 0-2.063-.926-2.063-2.065 0-1.138.92-2.063 2.063-2.063 1.14 0 2.064.925 2.064 2.063 0 1.139-.925 2.065-2.064 2.065zm1.782 13.019H3.555V9h3.564v11.452zM22.225 0H1.771C.792 0 0 .774 0 1.729v20.542C0 23.227.792 24 1.771 24h20.451C23.2 24 24 23.227 24 22.271V1.729C24 .774 23.2 0 22.222 0h.003z"/>
                </svg>
              </a>
              <a href="#" class="social-link" aria-label="YouTube">
                <svg fill="currentColor" viewBox="0 0 24 24">
                  <path d="M23.498 6.186a3.016 3.016 0 0 0-2.122-2.136C19.505 3.545 12 3.545 12 3.545s-7.505 0-9.377.505A3.017 3.017 0 0 0 .502 6.186C0 8.07 0 12 0 12s0 3.93.502 5.814a3.016 3.016 0 0 0 2.122 2.136c1.871.505 9.376.505 9.376.505s7.505 0 9.377-.505a3.015 3.015 0 0 0 2.122-2.136C24 15.93 24 12 24 12s0-3.93-.502-5.814zM9.545 15.568V8.432L15.818 12l-6.273 3.568z"/>
                </svg>
              </a>
            </div>
          </div>
        </div>

        <!-- Top Locations -->
        <div class="footer-column">
          <h3 class="footer-heading">Top Locations</h3>
          <ul class="footer-links">
            @foreach($topAreas as $area)
              <li>
                <a href="{{ route('locations.sub', ['dhaka', $area->slug]) }}">
                  Tutors in {{ $area->name }}
                </a>
              </li>
            @endforeach
            <li>
              <a href="{{ route('locations.main', 'dhaka') }}" class="footer-link-highlight">
                View All Areas →
              </a>
            </li>
          </ul>
        </div>

        <!-- Top Subjects -->
        <div class="footer-column">
          <h3 class="footer-heading">Top Subjects</h3>
          <ul class="footer-links">
            @foreach($topSubjects as $subject)
              <li>
                <a href="{{ route('subjects.show', $subject->slug) }}">
                  {{ $subject->name }} Tutors
                </a>
              </li>
            @endforeach
            <li>
              <a href="{{ route('tutors.index') }}" class="footer-link-highlight">
                View All Subjects →
              </a>
            </li>
          </ul>
        </div>

        <!-- Top Levels -->
        <div class="footer-column">
          <h3 class="footer-heading">Top Levels</h3>
          <ul class="footer-links">
            @foreach($topLevels as $level)
              <li>
                <a href="{{ route('levels.show', $level->slug) }}">
                  {{ $level->name }} Tutors
                </a>
              </li>
            @endforeach
            <li>
              <a href="{{ route('tutors.index') }}" class="footer-link-highlight">
                View All Levels →
              </a>
            </li>
          </ul>
        </div>

        <!-- Quick Links -->
        <div class="footer-column">
          <h3 class="footer-heading">Quick Links</h3>
          <ul class="footer-links">
            <li><a href="{{ route('tutors.index') }}">Browse Tutors</a></li>
            <li><a href="{{ route('tuition_offers.index') }}">Tuition Jobs</a></li>
            <li><a href="{{ route('register.parent') }}">Post a Job</a></li>
            <li><a href="{{ route('register.tutor') }}">Become a Tutor</a></li>
            <li><a href="#">About Us</a></li>
            <li><a href="#">Contact Us</a></li>
            <li><a href="#">Privacy Policy</a></li>
            <li><a href="#">Terms of Service</a></li>
          </ul>
        </div>

      </div>
    </div>
  </div>

  <!-- Footer Bottom -->
  <div class="footer-bottom">
    <div class="container-premium">
      <div class="footer-bottom-content">
        <p class="footer-copyright">
          © {{ date('Y') }} TutorHub Bangladesh. All rights reserved.
        </p>
        <div class="footer-bottom-links">
          <a href="#">Privacy</a>
          <span class="footer-divider">•</span>
          <a href="#">Terms</a>
          <span class="footer-divider">•</span>
          <a href="#">Sitemap</a>
        </div>
      </div>
    </div>
  </div>
</footer>

<style>
/* Footer Premium Styles */
.footer-premium {
  background: linear-gradient(135deg, #1a1a1a 0%, #2d2d2d 100%);
  color: #e5e5e5;
  margin-top: 80px;
}

.footer-main {
  padding: 60px 20px 40px;
}

.footer-grid {
  display: grid;
  grid-template-columns: 2fr 1fr 1fr 1fr 1fr;
  gap: 48px;
  max-width: 1600px;
  margin: 0 auto;
}

/* Footer Brand */
.footer-brand {
  max-width: 320px;
}

.footer-logo {
  display: flex;
  align-items: center;
  gap: 12px;
  margin-bottom: 20px;
}

.footer-logo .logo {
  width: 48px;
  height: 48px;
  background: linear-gradient(135deg, #006A4E, #00C878);
  border-radius: 12px;
  display: flex;
  align-items: center;
  justify-content: center;
  font-size: 24px;
  font-weight: 900;
  color: white;
  box-shadow: 0 4px 12px rgba(0, 106, 78, 0.3);
}

.footer-logo .brand-title {
  font-size: 22px;
  font-weight: 800;
  color: white;
}

.footer-logo .brand-title span {
  color: #00C878;
}

.footer-logo .brand-sub {
  font-size: 12px;
  color: #9ca3af;
  margin-top: -2px;
}

.footer-description {
  color: #9ca3af;
  line-height: 1.6;
  margin-bottom: 24px;
  font-size: 14px;
}

/* Social Links */
.footer-social {
  display: flex;
  gap: 12px;
}

.social-link {
  width: 40px;
  height: 40px;
  border-radius: 10px;
  background: rgba(255, 255, 255, 0.05);
  display: flex;
  align-items: center;
  justify-content: center;
  transition: all 0.3s;
  color: #9ca3af;
}

.social-link:hover {
  background: #006A4E;
  color: white;
  transform: translateY(-2px);
}

.social-link svg {
  width: 20px;
  height: 20px;
}

/* Footer Columns */
.footer-column {
  display: flex;
  flex-direction: column;
}

.footer-heading {
  font-size: 16px;
  font-weight: 700;
  color: white;
  margin-bottom: 20px;
  text-transform: uppercase;
  letter-spacing: 0.5px;
}

.footer-links {
  list-style: none;
  padding: 0;
  margin: 0;
  display: flex;
  flex-direction: column;
  gap: 12px;
}

.footer-links li a {
  color: #9ca3af;
  text-decoration: none;
  font-size: 14px;
  transition: all 0.2s;
  display: inline-block;
}

.footer-links li a:hover {
  color: #00C878;
  padding-left: 4px;
}

.footer-link-highlight {
  color: #00C878 !important;
  font-weight: 600;
}

/* Footer Bottom */
.footer-bottom {
  border-top: 1px solid rgba(255, 255, 255, 0.1);
  padding: 24px 20px;
}

.footer-bottom-content {
  max-width: 1600px;
  margin: 0 auto;
  display: flex;
  justify-content: space-between;
  align-items: center;
}

.footer-copyright {
  color: #6b7280;
  font-size: 14px;
  margin: 0;
}

.footer-bottom-links {
  display: flex;
  align-items: center;
  gap: 16px;
}

.footer-bottom-links a {
  color: #9ca3af;
  text-decoration: none;
  font-size: 14px;
  transition: color 0.2s;
}

.footer-bottom-links a:hover {
  color: #00C878;
}

.footer-divider {
  color: #4b5563;
}

/* Responsive */
@media (max-width: 1200px) {
  .footer-grid {
    grid-template-columns: 2fr 1fr 1fr;
    gap: 40px;
  }

  .footer-column:nth-child(4),
  .footer-column:nth-child(5) {
    grid-column: span 1;
  }
}

@media (max-width: 768px) {
  .footer-main {
    padding: 40px 20px;
  }

  .footer-grid {
    grid-template-columns: 1fr;
    gap: 32px;
  }

  .footer-brand {
    max-width: 100%;
  }

  .footer-bottom-content {
    flex-direction: column;
    gap: 16px;
    text-align: center;
  }
}
</style>
