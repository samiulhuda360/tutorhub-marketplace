@extends('layouts.app')
@section('title', 'My Invitations | Tutor Dashboard')

@section('content')
<div class="tutor-invitations-page">
  <div class="container">

    <!-- Page Header -->
    <div class="page-header">
      <h1 class="page-title">My Invitations</h1>
      <p class="page-subtitle">Review invitations from parents</p>
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

    <!-- Filter Tabs -->
    <div class="filter-tabs">
      <button class="tab-btn active" data-filter="all">All</button>
      <button class="tab-btn" data-filter="pending">Pending</button>
      <button class="tab-btn" data-filter="accepted">Accepted</button>
      <button class="tab-btn" data-filter="with_admin">With Admin</button>
    </div>

    <!-- Invitations List -->
    @if($invitations->count() > 0)
      <div class="invitations-grid">
        @foreach($invitations as $invitation)
          <div class="invitation-card" data-status="{{ $invitation->status }}">

            <!-- Card Header -->
            <div class="card-header">
              <div class="parent-info">
                <div class="parent-avatar">
                  {{ strtoupper(substr($invitation->parent->name, 0, 2)) }}
                </div>
                <div>
                  <h3 class="parent-name">{{ $invitation->parent->name }}</h3>
                  <p class="parent-role">Parent</p>
                </div>
              </div>

              <!-- Status Badge -->
              <span class="status-badge status-{{ $invitation->status }}">
                {{ ucfirst(str_replace('_', ' ', $invitation->status)) }}
              </span>
            </div>

            <!-- Parent's Message -->
            <div class="message-box">
              <strong>Parent's Message:</strong>
              <p>{{ $invitation->message }}</p>
            </div>

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

            <!-- Your Response (if any) -->
            @if($invitation->tutor_response_message)
              <div class="your-response">
                <strong>Your Response:</strong>
                <p>{{ $invitation->tutor_response_message }}</p>
              </div>
            @endif

            <!-- Card Footer -->
            <div class="card-footer">
              <div class="meta-info">
                <span>Received {{ $invitation->created_at->diffForHumans() }}</span>
                @if($invitation->accepted_at)
                  <span class="accepted-time">Accepted {{ $invitation->accepted_at->diffForHumans() }}</span>
                @elseif($invitation->rejected_at)
                  <span class="rejected-time">Rejected {{ $invitation->rejected_at->diffForHumans() }}</span>
                @endif
              </div>

              <div class="actions">
                @if($invitation->status === 'pending')
                  <!-- Accept Button -->
                  <button class="btn-accept" onclick="showAcceptModal({{ $invitation->id }}, @js($invitation->parent->name))">
                    <svg width="16" height="16" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                      <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7"/>
                    </svg>
                    Accept
                  </button>

                  <!-- Reject Button -->
                  <button class="btn-reject" onclick="showRejectModal({{ $invitation->id }}, @js($invitation->parent->name))">
                    <svg width="16" height="16" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                      <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12"/>
                    </svg>
                    Reject
                  </button>
                @else
                  <!-- View Details -->
                  <a href="{{ route('tutor.invitations.show', $invitation) }}" class="btn-view">
                    View Details
                  </a>
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
        <h3>No Invitations Received Yet</h3>
        <p>Parents will send you invitations when they're interested in your profile.</p>
        <a href="{{ route('tutor.dashboard') }}" class="btn-primary">
          Back to Dashboard
        </a>
      </div>
    @endif

  </div>
</div>

<!-- Accept Modal -->
<div id="acceptModal" class="modal-overlay">
  <div class="modal-content">
    <h3 class="modal-title">Accept Invitation</h3>
    <p class="modal-text">Accept invitation from <strong id="acceptParentName"></strong>?</p>

    <form id="acceptForm" method="POST">
      @csrf
      <div class="form-group">
        <label>Optional Response Message</label>
        <textarea name="response_message" class="form-textarea" placeholder="Thank you for the invitation! I'm excited to work with you..." maxlength="500"></textarea>
      </div>

      <div class="modal-actions">
        <button type="button" class="btn-modal-cancel" onclick="closeAcceptModal()">Cancel</button>
        <button type="submit" class="btn-modal-submit">Accept Invitation</button>
      </div>
    </form>
  </div>
</div>

<!-- Reject Modal -->
<div id="rejectModal" class="modal-overlay">
  <div class="modal-content">
    <h3 class="modal-title">Reject Invitation</h3>
    <p class="modal-text">Reject invitation from <strong id="rejectParentName"></strong>?</p>

    <form id="rejectForm" method="POST">
      @csrf
      <div class="form-group">
        <label>Optional Response Message</label>
        <textarea name="response_message" class="form-textarea" placeholder="Thank you for considering me, but I'm not available at this time..." maxlength="500"></textarea>
      </div>

      <div class="modal-actions">
        <button type="button" class="btn-modal-cancel" onclick="closeRejectModal()">Cancel</button>
        <button type="submit" class="btn-modal-submit btn-reject-submit">Reject Invitation</button>
      </div>
    </form>
  </div>
</div>

<style>
.tutor-invitations-page {
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

/* Filter Tabs */
.filter-tabs {
  display: flex;
  gap: 8px;
  margin-bottom: 24px;
  flex-wrap: wrap;
}

.tab-btn {
  padding: 10px 20px;
  background: white;
  border: 2px solid #E5E7EB;
  border-radius: 8px;
  font-size: 14px;
  font-weight: 600;
  color: #6B7280;
  cursor: pointer;
  transition: all 0.2s;
}

.tab-btn:hover {
  border-color: #006A4E;
  color: #006A4E;
}

.tab-btn.active {
  background: #006A4E;
  border-color: #006A4E;
  color: white;
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

.parent-info {
  display: flex;
  align-items: center;
  gap: 12px;
}

.parent-avatar {
  width: 56px;
  height: 56px;
  border-radius: 12px;
  background: linear-gradient(135deg, #3B82F6, #60A5FA);
  color: white;
  display: flex;
  align-items: center;
  justify-content: center;
  font-size: 20px;
  font-weight: 700;
}

.parent-name {
  font-size: 18px;
  font-weight: 700;
  color: #111827;
  margin: 0;
}

.parent-role {
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

/* Message Box */
.message-box {
  margin-bottom: 16px;
  padding: 16px;
  background: #EFF6FF;
  border-radius: 12px;
  border-left: 4px solid #3B82F6;
}

.message-box strong {
  display: block;
  margin-bottom: 8px;
  color: #1E40AF;
  font-size: 14px;
}

.message-box p {
  color: #1E3A8A;
  font-size: 15px;
  line-height: 1.6;
  margin: 0;
}

/* Your Response */
.your-response {
  margin-bottom: 16px;
  padding: 16px;
  background: #F0FDF4;
  border-radius: 12px;
  border-left: 4px solid #10B981;
}

.your-response strong {
  display: block;
  margin-bottom: 8px;
  color: #047857;
  font-size: 14px;
}

.your-response p {
  color: #065F46;
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

.btn-accept,
.btn-reject,
.btn-view {
  display: flex;
  align-items: center;
  gap: 6px;
  padding: 10px 20px;
  border-radius: 8px;
  font-size: 14px;
  font-weight: 600;
  cursor: pointer;
  transition: all 0.2s;
  text-decoration: none;
  border: none;
}

.btn-accept {
  background: #10B981;
  color: white;
}

.btn-accept:hover {
  background: #059669;
  transform: translateY(-1px);
}

.btn-reject {
  background: #FEE2E2;
  color: #DC2626;
}

.btn-reject:hover {
  background: #FECACA;
  transform: translateY(-1px);
}

.btn-view {
  background: #006A4E;
  color: white;
}

.btn-view:hover {
  background: #004A35;
  transform: translateY(-1px);
}

/* Modal */
.modal-overlay {
  display: none;
  position: fixed;
  top: 0;
  left: 0;
  right: 0;
  bottom: 0;
  background: rgba(0, 0, 0, 0.5);
  z-index: 9999;
  align-items: center;
  justify-content: center;
}

.modal-overlay.active {
  display: flex;
}

.modal-content {
  background: white;
  border-radius: 16px;
  padding: 32px;
  max-width: 500px;
  width: 90%;
}

.modal-title {
  font-size: 24px;
  font-weight: 800;
  color: #111827;
  margin-bottom: 12px;
}

.modal-text {
  font-size: 16px;
  color: #6B7280;
  margin-bottom: 24px;
}

.form-group {
  margin-bottom: 20px;
}

.form-group label {
  display: block;
  font-size: 14px;
  font-weight: 600;
  color: #374151;
  margin-bottom: 8px;
}

.form-textarea {
  width: 100%;
  padding: 12px;
  border: 2px solid #E5E7EB;
  border-radius: 8px;
  font-size: 15px;
  font-family: inherit;
  resize: vertical;
  min-height: 100px;
}

.form-textarea:focus {
  outline: none;
  border-color: #006A4E;
}

.modal-actions {
  display: flex;
  gap: 12px;
  justify-content: flex-end;
}

.btn-modal-cancel,
.btn-modal-submit {
  padding: 12px 24px;
  border-radius: 8px;
  font-size: 15px;
  font-weight: 600;
  cursor: pointer;
  border: none;
  transition: all 0.2s;
}

.btn-modal-cancel {
  background: #F3F4F6;
  color: #374151;
}

.btn-modal-cancel:hover {
  background: #E5E7EB;
}

.btn-modal-submit {
  background: #10B981;
  color: white;
}

.btn-modal-submit:hover {
  background: #059669;
}

.btn-reject-submit {
  background: #DC2626;
}

.btn-reject-submit:hover {
  background: #B91C1C;
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

  .btn-accept,
  .btn-reject,
  .btn-view {
    flex: 1;
    justify-content: center;
  }
}
</style>

<script>
function showAcceptModal(invitationId, parentName) {
  document.getElementById('acceptModal').classList.add('active');
  document.getElementById('acceptParentName').textContent = parentName;
  document.getElementById('acceptForm').action = `/tutor/invitations/${invitationId}/accept`;
  document.body.style.overflow = 'hidden';
}

function closeAcceptModal() {
  document.getElementById('acceptModal').classList.remove('active');
  document.getElementById('acceptForm').reset();
  document.body.style.overflow = 'auto';
}

function showRejectModal(invitationId, parentName) {
  document.getElementById('rejectModal').classList.add('active');
  document.getElementById('rejectParentName').textContent = parentName;
  document.getElementById('rejectForm').action = `/tutor/invitations/${invitationId}/reject`;
  document.body.style.overflow = 'hidden';
}

function closeRejectModal() {
  document.getElementById('rejectModal').classList.remove('active');
  document.getElementById('rejectForm').reset();
  document.body.style.overflow = 'auto';
}

// Filter functionality
const tabBtns = document.querySelectorAll('.tab-btn');
const cards = document.querySelectorAll('.invitation-card');

tabBtns.forEach(btn => {
  btn.addEventListener('click', () => {
    // Update active tab
    tabBtns.forEach(b => b.classList.remove('active'));
    btn.classList.add('active');

    // Filter cards
    const filter = btn.dataset.filter;
    cards.forEach(card => {
      if (filter === 'all' || card.dataset.status === filter) {
        card.style.display = 'block';
      } else {
        card.style.display = 'none';
      }
    });
  });
});

// Close modals with Escape key
document.addEventListener('keydown', function(event) {
  if (event.key === 'Escape') {
    closeAcceptModal();
    closeRejectModal();
  }
});
</script>
@endsection
