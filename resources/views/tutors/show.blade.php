@extends('layouts.app')
@section('title', $tutor->name . ' - Tutor Profile | TutorHub Bangladesh')
@section('meta_description', 'View ' . $tutor->name . '\'s profile. ' . ($tutor->tutorProfile->bio ?? 'Professional tutor on TutorHub Bangladesh'))

@section('content')

<div class="tutor-profile-page">
  <div class="container-tutor-profile">

    <!-- Back Button -->
    <div class="back-nav">
      <a href="{{ route('tutors.index') }}" class="back-link">
        <svg width="20" height="20" fill="none" stroke="currentColor" viewBox="0 0 24 24">
          <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 19l-7-7 7-7"/>
        </svg>
        Back to Tutors
      </a>
    </div>

    <div class="profile-grid">

      <!-- Left Column: Profile Card -->
      <div class="profile-card-section">
        <div class="profile-card">

          <!-- Avatar with Verified Badge -->
          <div class="avatar-section">
            @if($tutor->tutorProfile->profile_photo)
              <img src="{{ asset('storage/' . $tutor->tutorProfile->profile_photo) }}" alt="{{ $tutor->name }}" class="tutor-avatar-large">
            @else
              <div class="tutor-avatar-large avatar-placeholder">
                {{ strtoupper(substr($tutor->name, 0, 2)) }}
              </div>
            @endif
            <div class="verified-badge-large">
              <svg width="24" height="24" fill="currentColor" viewBox="0 0 20 20">
                <path fill-rule="evenodd" d="M6.267 3.455a3.066 3.066 0 001.745-.723 3.066 3.066 0 013.976 0 3.066 3.066 0 001.745.723 3.066 3.066 0 012.812 2.812c.051.643.304 1.254.723 1.745a3.066 3.066 0 010 3.976 3.066 3.066 0 00-.723 1.745 3.066 3.066 0 01-2.812 2.812 3.066 3.066 0 00-1.745.723 3.066 3.066 0 01-3.976 0 3.066 3.066 0 00-1.745-.723 3.066 3.066 0 01-2.812-2.812 3.066 3.066 0 00-.723-1.745 3.066 3.066 0 010-3.976 3.066 3.066 0 00.723-1.745 3.066 3.066 0 012.812-2.812zm7.44 5.252a1 1 0 00-1.414-1.414L9 10.586 7.707 9.293a1 1 0 00-1.414 1.414l2 2a1 1 0 001.414 0l4-4z" clip-rule="evenodd"/>
              </svg>
            </div>
          </div>

          <!-- Name & Rating -->
          <h1 class="tutor-name-large">{{ $tutor->name }}</h1>
          <div class="tutor-code-display">ID: {{ $tutor->tutor_code }}</div>

          <div class="rating-section">
            <span class="rating-text">{{ $tutor->tutorProfile->is_verified ? '✔ Profile verified by TutorHub' : 'Profile not yet verified' }}</span>
          </div>

          <!-- Quick Stats -->
          <div class="quick-stats-grid">
            <div class="stat-item">
              <div class="stat-icon">💼</div>
              <div class="stat-value">{{ $tutor->tutorProfile->experience_years ?? 0 }} years</div>
              <div class="stat-label">Experience</div>
            </div>
            <div class="stat-item">
              <div class="stat-icon">📚</div>
              <div class="stat-value">{{ count((array) $tutor->tutorProfile->subjects) }}</div>
              <div class="stat-label">Subjects</div>
            </div>
            <div class="stat-item">
              <div class="stat-icon">🎓</div>
              <div class="stat-value">{{ count((array) $tutor->tutorProfile->classes) }}</div>
              <div class="stat-label">Classes</div>
            </div>
          </div>

          <!-- Contact Button (Only for logged-in parents) -->
          @auth
            @if(auth()->user()->role === 'parent')
              <button class="invite-btn-primary" onclick="openInvitationModal()">
                <svg width="20" height="20" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                  <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 8l7.89 5.26a2 2 0 002.22 0L21 8M5 19h14a2 2 0 002-2V7a2 2 0 00-2-2H5a2 2 0 00-2 2v10a2 2 0 002 2z"/>
                </svg>
                Invite This Tutor
              </button>
            @endif
          @else
            <a href="{{ route('login') }}" class="invite-btn-secondary">
              <svg width="20" height="20" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M11 16l-4-4m0 0l4-4m-4 4h14m-5 4v1a3 3 0 01-3 3H6a3 3 0 01-3-3V7a3 3 0 013-3h7a3 3 0 013 3v1"/>
              </svg>
              Login to Contact
            </a>
          @endauth

        </div>
      </div>

      <!-- Right Column: Details -->
      <div class="profile-details-section">

        <!-- Bio Section -->
        @if($tutor->tutorProfile->bio)
          <div class="detail-card">
            <h3 class="detail-title">
              <svg class="detail-icon" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M16 7a4 4 0 11-8 0 4 4 0 018 0zM12 14a7 7 0 00-7 7h14a7 7 0 00-7-7z"/>
              </svg>
              About Me
            </h3>
            <p class="bio-text">{{ $tutor->tutorProfile->bio }}</p>
          </div>
        @endif

        <!-- Subjects -->
        <div class="detail-card">
          <h3 class="detail-title">
            <svg class="detail-icon" fill="none" stroke="currentColor" viewBox="0 0 24 24">
              <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 6.253v13m0-13C10.832 5.477 9.246 5 7.5 5S4.168 5.477 3 6.253v13C4.168 18.477 5.754 18 7.5 18s3.332.477 4.5 1.253m0-13C13.168 5.477 14.754 5 16.5 5c1.747 0 3.332.477 4.5 1.253v13C19.832 18.477 18.247 18 16.5 18c-1.746 0-3.332.477-4.5 1.253"/>
            </svg>
            Subjects I Teach
          </h3>
          <div class="subjects-tags">
            @forelse($tutor->tutorProfile->subjects ?? [] as $subject)
              <span class="subject-tag">{{ $subject }}</span>
            @empty
              <p class="empty-text">No subjects listed</p>
            @endforelse
          </div>
        </div>

        <!-- Education -->
        @if($tutor->tutorProfile->education)
          <div class="detail-card">
            <h3 class="detail-title">
              <svg class="detail-icon" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                <path d="M12 14l9-5-9-5-9 5 9 5z"/>
                <path d="M12 14l6.16-3.422a12.083 12.083 0 01.665 6.479A11.952 11.952 0 0012 20.055a11.952 11.952 0 00-6.824-2.998 12.078 12.078 0 01.665-6.479L12 14z"/>
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 14l9-5-9-5-9 5 9 5zm0 0l6.16-3.422a12.083 12.083 0 01.665 6.479A11.952 11.952 0 0012 20.055a11.952 11.952 0 00-6.824-2.998 12.078 12.078 0 01.665-6.479L12 14zm-4 6v-7.5l4-2.222"/>
              </svg>
              Education
            </h3>
            <p class="detail-text">{{ $tutor->tutorProfile->education }}</p>
          </div>
        @endif

        <!-- Experience -->
        <div class="detail-card">
          <h3 class="detail-title">
            <svg class="detail-icon" fill="none" stroke="currentColor" viewBox="0 0 24 24">
              <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M21 13.255A23.931 23.931 0 0112 15c-3.183 0-6.22-.62-9-1.745M16 6V4a2 2 0 00-2-2h-4a2 2 0 00-2 2v2m4 6h.01M5 20h14a2 2 0 002-2V8a2 2 0 00-2-2H5a2 2 0 00-2 2v10a2 2 0 002 2z"/>
            </svg>
            Experience
          </h3>
          <div class="experience-info">
            <div class="info-row">
              <span class="info-label">Years:</span>
              <span class="info-value">{{ $tutor->tutorProfile->experience_years ?? 0 }} years</span>
            </div>
            <div class="info-row">
              <span class="info-label">Classes:</span>
              <span class="info-value">{{ implode(', ', (array) $tutor->tutorProfile->classes) }}</span>
            </div>
            @if($tutor->tutorProfile->teaching_style)
              <div class="info-row">
                <span class="info-label">Teaching Style:</span>
                <span class="info-value">{{ $tutor->tutorProfile->teaching_style }}</span>
              </div>
            @endif
          </div>
        </div>

        <!-- Pricing -->
        <div class="detail-card highlight-card">
          <h3 class="detail-title">
            <svg class="detail-icon" fill="none" stroke="currentColor" viewBox="0 0 24 24">
              <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8c-1.657 0-3 .895-3 2s1.343 2 3 2 3 .895 3 2-1.343 2-3 2m0-8c1.11 0 2.08.402 2.599 1M12 8V7m0 1v8m0 0v1m0-1c-1.11 0-2.08-.402-2.599-1M21 12a9 9 0 11-18 0 9 9 0 0118 0z"/>
            </svg>
            Tuition Rate
          </h3>
          <div class="pricing-display">
            <div class="price-range">
              <span class="price-amount">৳{{ number_format($tutor->tutorProfile->hourly_rate ?? 0) }}</span>
            </div>
            <div class="price-period">per month</div>
          </div>
        </div>

        <!-- Service Areas -->
        @if(count($areaNames) > 0)
          <div class="detail-card">
            <h3 class="detail-title">
              <svg class="detail-icon" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17.657 16.657L13.414 20.9a1.998 1.998 0 01-2.827 0l-4.244-4.243a8 8 0 1111.314 0z"/>
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 11a3 3 0 11-6 0 3 3 0 016 0z"/>
              </svg>
              Available Areas
            </h3>
            <div class="areas-list">
              @foreach($areaNames as $areaName)
                <span class="area-badge">📍 {{ $areaName }}</span>
              @endforeach
            </div>
          </div>
        @endif

        <!-- Availability -->
        @if($tutor->tutorProfile->availability)
          <div class="detail-card">
            <h3 class="detail-title">
              <svg class="detail-icon" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8v4l3 3m6-3a9 9 0 11-18 0 9 9 0 0118 0z"/>
              </svg>
              Availability
            </h3>
            <p class="detail-text">{{ $tutor->tutorProfile->availability }}</p>
          </div>
        @endif

      </div>
    </div>

  </div>
</div>

<style>
.tutor-profile-page {
  background: #F9FAFB;
  min-height: 100vh;
  padding: 40px 0 80px;
}

.container-tutor-profile {
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

.profile-grid {
  display: grid;
  grid-template-columns: 380px 1fr;
  gap: 32px;
}

@media (max-width: 968px) {
  .profile-grid {
    grid-template-columns: 1fr;
  }
}

/* Profile Card */
.profile-card {
  background: white;
  border-radius: 20px;
  padding: 40px;
  box-shadow: 0 4px 20px rgba(0, 0, 0, 0.08);
  position: sticky;
  top: 24px;
}

.avatar-section {
  position: relative;
  width: 160px;
  height: 160px;
  margin: 0 auto 24px;
}

.tutor-avatar-large {
  width: 160px;
  height: 160px;
  border-radius: 50%;
  object-fit: cover;
  border: 4px solid #E5E7EB;
}

.avatar-placeholder {
  display: flex;
  align-items: center;
  justify-content: center;
  font-size: 48px;
  font-weight: 800;
  background: linear-gradient(135deg, #006A4E, #00C878);
  color: white;
}

.verified-badge-large {
  position: absolute;
  bottom: 8px;
  right: 8px;
  width: 48px;
  height: 48px;
  background: #00C878;
  border-radius: 50%;
  display: flex;
  align-items: center;
  justify-content: center;
  border: 4px solid white;
  box-shadow: 0 2px 12px rgba(0, 200, 120, 0.4);
}

.verified-badge-large svg {
  color: white;
}

.tutor-name-large {
  font-size: 28px;
  font-weight: 800;
  color: #111827;
  text-align: center;
  margin-bottom: 8px;
}

.tutor-code-display {
  text-align: center;
  color: #6B7280;
  font-size: 14px;
  font-weight: 600;
  margin-bottom: 16px;
}

.rating-section {
  text-align: center;
  margin-bottom: 32px;
}

.stars-large {
  font-size: 24px;
  margin-bottom: 8px;
}

.rating-text {
  color: #6B7280;
  font-size: 14px;
  font-weight: 600;
}

.quick-stats-grid {
  display: grid;
  grid-template-columns: repeat(3, 1fr);
  gap: 16px;
  margin-bottom: 32px;
  padding: 24px 0;
  border-top: 2px solid #F3F4F6;
  border-bottom: 2px solid #F3F4F6;
}

.stat-item {
  text-align: center;
}

.stat-icon {
  font-size: 32px;
  margin-bottom: 8px;
}

.stat-value {
  font-size: 18px;
  font-weight: 800;
  color: #111827;
  margin-bottom: 4px;
}

.stat-label {
  font-size: 12px;
  color: #6B7280;
  font-weight: 600;
}

.invite-btn-primary,
.invite-btn-secondary {
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

.invite-btn-primary {
  background: #006A4E;
  color: white;
}

.invite-btn-primary:hover {
  background: #004A35;
  transform: translateY(-2px);
  box-shadow: 0 8px 20px rgba(0, 106, 78, 0.3);
}

.invite-btn-secondary {
  background: #F3F4F6;
  color: #374151;
}

.invite-btn-secondary:hover {
  background: #E5E7EB;
}

/* Detail Cards */
.detail-card {
  background: white;
  border-radius: 16px;
  padding: 32px;
  margin-bottom: 24px;
  box-shadow: 0 2px 12px rgba(0, 0, 0, 0.06);
}

.highlight-card {
  background: linear-gradient(135deg, #f0fdf4 0%, #dcfce7 100%);
  border: 2px solid #00C878;
}

.detail-title {
  display: flex;
  align-items: center;
  gap: 12px;
  font-size: 20px;
  font-weight: 800;
  color: #111827;
  margin-bottom: 20px;
}

.detail-icon {
  width: 24px;
  height: 24px;
  color: #006A4E;
}

.bio-text,
.detail-text {
  color: #4B5563;
  font-size: 15px;
  line-height: 1.7;
}

.subjects-tags {
  display: flex;
  flex-wrap: wrap;
  gap: 12px;
}

.subject-tag {
  padding: 10px 18px;
  background: #E0F2FE;
  color: #0369A1;
  border-radius: 8px;
  font-size: 14px;
  font-weight: 600;
}

.experience-info,
.info-row {
  display: flex;
  justify-content: space-between;
  padding: 12px 0;
  border-bottom: 1px solid #F3F4F6;
}

.info-row:last-child {
  border-bottom: none;
}

.info-label {
  color: #6B7280;
  font-weight: 600;
}

.info-value {
  color: #111827;
  font-weight: 700;
}

.pricing-display {
  text-align: center;
  padding: 24px;
  background: white;
  border-radius: 12px;
}

.price-range {
  display: flex;
  align-items: center;
  justify-content: center;
  gap: 12px;
  margin-bottom: 8px;
}

.price-amount {
  font-size: 32px;
  font-weight: 900;
  color: #006A4E;
}

.price-separator {
  font-size: 24px;
  color: #9CA3AF;
}

.price-period {
  color: #6B7280;
  font-size: 14px;
  font-weight: 600;
}

.areas-list {
  display: flex;
  flex-wrap: wrap;
  gap: 12px;
}

.area-badge {
  padding: 10px 16px;
  background: #FEF3C7;
  color: #92400E;
  border-radius: 8px;
  font-size: 14px;
  font-weight: 600;
}

.empty-text {
  color: #9CA3AF;
  font-style: italic;
}

/* Invitation Modal Styles */
.invitation-modal-overlay {
  display: none;
  position: fixed;
  top: 0;
  left: 0;
  right: 0;
  bottom: 0;
  background: rgba(0, 0, 0, 0.5);
  z-index: 9998;
  animation: fadeIn 0.2s ease;
}

.invitation-modal-overlay.active {
  display: flex;
  align-items: center;
  justify-content: center;
}

.invitation-modal {
  background: white;
  border-radius: 20px;
  padding: 32px;
  max-width: 600px;
  width: 90%;
  max-height: 90vh;
  overflow-y: auto;
  box-shadow: 0 20px 50px rgba(0, 0, 0, 0.3);
  animation: slideUp 0.3s ease;
  position: relative;
  z-index: 9999;
}

@keyframes fadeIn {
  from { opacity: 0; }
  to { opacity: 1; }
}

@keyframes slideUp {
  from {
    transform: translateY(50px);
    opacity: 0;
  }
  to {
    transform: translateY(0);
    opacity: 1;
  }
}

.modal-header {
  display: flex;
  justify-content: space-between;
  align-items: center;
  margin-bottom: 24px;
  padding-bottom: 16px;
  border-bottom: 2px solid #E5E7EB;
}

.modal-title {
  font-size: 24px;
  font-weight: 800;
  color: #111827;
}

.modal-close {
  background: none;
  border: none;
  font-size: 28px;
  color: #6B7280;
  cursor: pointer;
  padding: 0;
  width: 32px;
  height: 32px;
  display: flex;
  align-items: center;
  justify-content: center;
  border-radius: 50%;
  transition: all 0.2s;
}

.modal-close:hover {
  background: #F3F4F6;
  color: #111827;
}

.modal-body {
  margin-bottom: 24px;
}

.form-group {
  margin-bottom: 20px;
}

.form-label {
  display: block;
  font-size: 15px;
  font-weight: 600;
  color: #374151;
  margin-bottom: 8px;
}

.form-textarea {
  width: 100%;
  padding: 14px;
  border: 2px solid #E5E7EB;
  border-radius: 12px;
  font-size: 15px;
  font-family: inherit;
  resize: vertical;
  min-height: 120px;
  transition: all 0.2s;
}

.form-textarea:focus {
  outline: none;
  border-color: #006A4E;
  box-shadow: 0 0 0 3px rgba(0, 106, 78, 0.1);
}

.char-count {
  text-align: right;
  font-size: 13px;
  color: #9CA3AF;
  margin-top: 6px;
}

.modal-footer {
  display: flex;
  gap: 12px;
  justify-content: flex-end;
}

.btn-modal {
  padding: 14px 28px;
  border-radius: 12px;
  font-size: 15px;
  font-weight: 600;
  cursor: pointer;
  transition: all 0.2s;
  border: none;
}

.btn-modal-cancel {
  background: #F3F4F6;
  color: #374151;
}

.btn-modal-cancel:hover {
  background: #E5E7EB;
}

.btn-modal-submit {
  background: #006A4E;
  color: white;
}

.btn-modal-submit:hover {
  background: #004A35;
  transform: translateY(-1px);
  box-shadow: 0 4px 12px rgba(0, 106, 78, 0.3);
}

.btn-modal-submit:disabled {
  background: #9CA3AF;
  cursor: not-allowed;
  transform: none;
}
</style>

<!-- Invitation Modal -->
@auth
  @if(auth()->user()->role === 'parent')
    <div id="invitationModal" class="invitation-modal-overlay" onclick="closeModalOnOverlay(event)">
      <div class="invitation-modal">
        <div class="modal-header">
          <h3 class="modal-title">Invite {{ $tutor->name }}</h3>
          <button type="button" class="modal-close" onclick="closeInvitationModal()">&times;</button>
        </div>

        <form action="{{ route('parent.invitations.store') }}" method="POST" id="invitationForm">
          @csrf
          <input type="hidden" name="tutor_id" value="{{ $tutor->id }}">

          <div class="modal-body">
            <div class="form-group">
              <label for="message" class="form-label">
                Your Message <span style="color: #DC2626;">*</span>
              </label>
              <textarea
                name="message"
                id="message"
                class="form-textarea"
                placeholder="Introduce yourself and explain why you're interested in this tutor. Include details about your requirements, schedule preferences, and any specific needs..."
                required
                minlength="10"
                maxlength="1000"
                oninput="updateCharCount()"
              ></textarea>
              <div class="char-count">
                <span id="charCount">0</span> / 1000 characters
              </div>
            </div>
          </div>

          <div class="modal-footer">
            <button type="button" class="btn-modal btn-modal-cancel" onclick="closeInvitationModal()">
              Cancel
            </button>
            <button type="submit" class="btn-modal btn-modal-submit" id="submitBtn">
              Send Invitation
            </button>
          </div>
        </form>
      </div>
    </div>
  @endif
@endauth

<script>
function openInvitationModal() {
  document.getElementById('invitationModal').classList.add('active');
  document.body.style.overflow = 'hidden';
}

function closeInvitationModal() {
  document.getElementById('invitationModal').classList.remove('active');
  document.body.style.overflow = 'auto';
  document.getElementById('invitationForm').reset();
  updateCharCount();
}

function closeModalOnOverlay(event) {
  if (event.target.id === 'invitationModal') {
    closeInvitationModal();
  }
}

function updateCharCount() {
  const textarea = document.getElementById('message');
  const charCount = document.getElementById('charCount');
  const submitBtn = document.getElementById('submitBtn');

  if (textarea && charCount) {
    const length = textarea.value.length;
    charCount.textContent = length;

    // Disable submit if less than 10 characters
    if (submitBtn) {
      submitBtn.disabled = length < 10;
    }
  }
}

// Close modal with Escape key
document.addEventListener('keydown', function(event) {
  if (event.key === 'Escape') {
    const modal = document.getElementById('invitationModal');
    if (modal && modal.classList.contains('active')) {
      closeInvitationModal();
    }
  }
});
</script>

@endsection
