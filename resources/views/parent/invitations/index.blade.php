@extends('layouts.app')
@section('title', 'My Invitations | Parent Dashboard')

@section('content')
<div class="parent-invitations-page">
  <div class="container">

    <!-- Page Header -->
    <div class="page-header">
      <h1 class="page-title">My Invitations</h1>
      <p class="page-subtitle">Manage invitations sent to tutors</p>
    </div>

    <!-- Success/Error Messages -->
    @if(session('success'))
      <div class="alert alert-success">
        {{ session('success') }}
      </div>
    @endif

    @if(session('error'))
      <div class="alert alert-error">
        {{ session('error') }}
      </div>
    @endif

    <!-- Invitations List -->
    @if($invitations->count() > 0)
      <div class="invitations-grid">
        @foreach($invitations as $invitation)
          <div class="invitation-card">

            <!-- Card Header -->
            <div class="card-header">
              <div class="tutor-info">
                @if($invitation->tutor->tutorProfile && $invitation->tutor->tutorProfile->profile_photo)
                  <img src="{{ asset('storage/' . $invitation->tutor->tutorProfile->profile_photo) }}" alt="{{ $invitation->tutor->name }}" class="tutor-avatar">
                @else
                  <div class="tutor-avatar avatar-placeholder">
                    {{ strtoupper(substr($invitation->tutor->name, 0, 2)) }}
                  </div>
                @endif
                <div>
                  <h3 class="tutor-name">{{ $invitation->tutor->name }}</h3>
                  <p class="tutor-code">ID: {{ $invitation->tutor->tutor_code }}</p>
                </div>
              </div>

              <!-- Status Badge -->
              <span class="status-badge status-{{ $invitation->status }}">
                {{ ucfirst(str_replace('_', ' ', $invitation->status)) }}
              </span>
            </div>

            <!-- Message Preview -->
            <div class="message-preview">
              <strong>Your Message:</strong>
              <p>{{ Str::limit($invitation->message, 150) }}</p>
            </div>

            <!-- Tutor Response (if any) -->
            @if($invitation->tutor_response_message)
              <div class="tutor-response">
                <strong>Tutor's Response:</strong>
                <p>{{ $invitation->tutor_response_message }}</p>
              </div>
            @endif

            <!-- Linked Job (if any) -->
            @if($invitation->tuitionOffer)
              <div class="linked-job">
                <svg width="16" height="16" fill="currentColor" viewBox="0 0 20 20">
                  <path d="M9 2a1 1 0 000 2h2a1 1 0 100-2H9z"/>
                  <path fill-rule="evenodd" d="M4 5a2 2 0 012-2 3 3 0 003 3h2a3 3 0 003-3 2 2 0 012 2v11a2 2 0 01-2 2H6a2 2 0 01-2-2V5zm3 4a1 1 0 000 2h.01a1 1 0 100-2H7zm3 0a1 1 0 000 2h3a1 1 0 100-2h-3zm-3 4a1 1 0 100 2h.01a1 1 0 100-2H7zm3 0a1 1 0 100 2h3a1 1 0 100-2h-3z" clip-rule="evenodd"/>
                </svg>
                Related to job: <a href="{{ route('tuition_offers.show', $invitation->tuitionOffer->id) }}">{{ $invitation->tuitionOffer->title }}</a>
              </div>
            @endif

            <!-- Card Footer -->
            <div class="card-footer">
              <div class="meta-info">
                <span>Sent {{ $invitation->created_at->diffForHumans() }}</span>
                @if($invitation->accepted_at)
                  <span class="accepted-time">Accepted {{ $invitation->accepted_at->diffForHumans() }}</span>
                @elseif($invitation->rejected_at)
                  <span class="rejected-time">Rejected {{ $invitation->rejected_at->diffForHumans() }}</span>
                @endif
              </div>

              <div class="actions">
                <!-- View Details -->
                <a href="{{ route('parent.invitations.show', $invitation) }}" class="btn-view">
                  View Details
                </a>

                <!-- Cancel (only for pending) -->
                @if($invitation->status === 'pending')
                  <form action="{{ route('parent.invitations.destroy', $invitation) }}" method="POST" style="display: inline;" onsubmit="return confirm('Are you sure you want to cancel this invitation?')">
                    @csrf
                    @method('DELETE')
                    <button type="submit" class="btn-cancel">
                      Cancel
                    </button>
                  </form>
                @endif
              </div>
            </div>

          </div>
        @endforeach
      </div>

      <!-- Pagination -->
      <div class="pagination-wrapper">
        {{ $invitations->links() }}
      </div>

    @else
      <!-- Empty State -->
      <div class="empty-state">
        <svg width="64" height="64" fill="none" stroke="currentColor" viewBox="0 0 24 24">
          <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 8l7.89 5.26a2 2 0 002.22 0L21 8M5 19h14a2 2 0 002-2V7a2 2 0 00-2-2H5a2 2 0 00-2 2v10a2 2 0 002 2z"/>
        </svg>
        <h3>No Invitations Sent Yet</h3>
        <p>Browse tutors and send invitations to get started.</p>
        <a href="{{ route('tutors.index') }}" class="btn-primary">
          Browse Tutors
        </a>
      </div>
    @endif

  </div>
</div>

<style>
.parent-invitations-page {
  padding: 40px 20px;
  background: #F9FAFB;
  min-height: 100vh;
}

.container {
  max-width: 1200px;
  margin: 0 auto;
}

.page-header {
  margin-bottom: 32px;
}

.page-title {
  font-size: 32px;
  font-weight: 900;
  color: #111827;
  margin-bottom: 8px;
}

.page-subtitle {
  font-size: 16px;
  color: #6B7280;
}

/* Alerts */
.alert {
  padding: 16px 20px;
  border-radius: 12px;
  margin-bottom: 24px;
  font-weight: 500;
}

.alert-success {
  background: #D1FAE5;
  color: #065F46;
  border: 1px solid #10B981;
}

.alert-error {
  background: #FEE2E2;
  color: #991B1B;
  border: 1px solid #DC2626;
}

/* Invitations Grid */
.invitations-grid {
  display: grid;
  gap: 24px;
}

.invitation-card {
  background: white;
  border-radius: 16px;
  padding: 24px;
  box-shadow: 0 1px 3px rgba(0, 0, 0, 0.1);
  transition: all 0.2s;
}

.invitation-card:hover {
  box-shadow: 0 4px 12px rgba(0, 0, 0, 0.15);
}

.card-header {
  display: flex;
  justify-content: space-between;
  align-items: center;
  margin-bottom: 20px;
  padding-bottom: 16px;
  border-bottom: 2px solid #F3F4F6;
}

.tutor-info {
  display: flex;
  align-items: center;
  gap: 12px;
}

.tutor-avatar {
  width: 56px;
  height: 56px;
  border-radius: 12px;
  object-fit: cover;
}

.avatar-placeholder {
  background: linear-gradient(135deg, #006A4E, #00C878);
  color: white;
  display: flex;
  align-items: center;
  justify-content: center;
  font-size: 20px;
  font-weight: 700;
}

.tutor-name {
  font-size: 18px;
  font-weight: 700;
  color: #111827;
  margin: 0;
}

.tutor-code {
  font-size: 13px;
  color: #6B7280;
  margin: 4px 0 0 0;
}

/* Status Badges */
.status-badge {
  padding: 8px 16px;
  border-radius: 20px;
  font-size: 13px;
  font-weight: 600;
  text-transform: capitalize;
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

.status-with_admin,
.status-contacted,
.status-negotiating {
  background: #DBEAFE;
  color: #1E40AF;
}

.status-commission_paid,
.status-completed {
  background: #D1FAE5;
  color: #047857;
}

/* Message Preview */
.message-preview {
  margin-bottom: 16px;
  padding: 16px;
  background: #F9FAFB;
  border-radius: 12px;
  border-left: 4px solid #006A4E;
}

.message-preview strong {
  display: block;
  margin-bottom: 8px;
  color: #374151;
  font-size: 14px;
}

.message-preview p {
  color: #6B7280;
  font-size: 15px;
  line-height: 1.6;
  margin: 0;
}

/* Tutor Response */
.tutor-response {
  margin-bottom: 16px;
  padding: 16px;
  background: #EFF6FF;
  border-radius: 12px;
  border-left: 4px solid #3B82F6;
}

.tutor-response strong {
  display: block;
  margin-bottom: 8px;
  color: #1E40AF;
  font-size: 14px;
}

.tutor-response p {
  color: #1E3A8A;
  font-size: 15px;
  line-height: 1.6;
  margin: 0;
}

/* Linked Job */
.linked-job {
  display: flex;
  align-items: center;
  gap: 8px;
  padding: 12px;
  background: #F3F4F6;
  border-radius: 8px;
  font-size: 14px;
  color: #4B5563;
  margin-bottom: 16px;
}

.linked-job svg {
  flex-shrink: 0;
}

.linked-job a {
  color: #006A4E;
  font-weight: 600;
  text-decoration: none;
}

.linked-job a:hover {
  text-decoration: underline;
}

/* Card Footer */
.card-footer {
  display: flex;
  justify-content: space-between;
  align-items: center;
  flex-wrap: wrap;
  gap: 12px;
}

.meta-info {
  display: flex;
  flex-direction: column;
  gap: 4px;
  font-size: 13px;
  color: #9CA3AF;
}

.accepted-time {
  color: #059669;
  font-weight: 500;
}

.rejected-time {
  color: #DC2626;
  font-weight: 500;
}

.actions {
  display: flex;
  gap: 8px;
}

.btn-view,
.btn-cancel {
  padding: 10px 20px;
  border-radius: 8px;
  font-size: 14px;
  font-weight: 600;
  cursor: pointer;
  transition: all 0.2s;
  text-decoration: none;
  border: none;
}

.btn-view {
  background: #006A4E;
  color: white;
}

.btn-view:hover {
  background: #004A35;
  transform: translateY(-1px);
}

.btn-cancel {
  background: #FEE2E2;
  color: #DC2626;
}

.btn-cancel:hover {
  background: #FEE2E2;
  transform: translateY(-1px);
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

.btn-primary {
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

.btn-primary:hover {
  background: #004A35;
  transform: translateY(-2px);
  box-shadow: 0 4px 12px rgba(0, 106, 78, 0.3);
}

/* Pagination */
.pagination-wrapper {
  margin-top: 32px;
  display: flex;
  justify-content: center;
}

/* Responsive */
@media (max-width: 768px) {
  .card-header {
    flex-direction: column;
    align-items: flex-start;
    gap: 12px;
  }

  .card-footer {
    flex-direction: column;
    align-items: flex-start;
  }

  .actions {
    width: 100%;
  }

  .btn-view,
  .btn-cancel {
    flex: 1;
  }
}
</style>
@endsection
