@extends('layouts.app')
@section('title', 'Manage Subjects - Admin')

@section('content')
<section class="admin-page">
  <div class="container">

    <!-- Header -->
    <div class="page-header">
      <div>
        <h1 class="page-title">Manage Subjects</h1>
        <p class="page-subtitle">Manage all subjects available on the platform</p>
      </div>
      <div class="header-actions">
        <a href="{{ route('admin.dashboard') }}" class="btn btn-secondary">← Back to Dashboard</a>
        <a href="{{ route('admin.subjects.create') }}" class="btn btn-primary">+ Add New Subject</a>
      </div>
    </div>

    <!-- Success Message -->
    @if(session('success'))
      <div class="alert alert-success">
        {{ session('success') }}
      </div>
    @endif

    <!-- Subjects Table -->
    <div class="content-card">
      <div class="table-responsive">
        <table class="data-table">
          <thead>
            <tr>
              <th>Icon</th>
              <th>Name</th>
              <th>Slug</th>
              <th>Sort Order</th>
              <th>Status</th>
              <th>Actions</th>
            </tr>
          </thead>
          <tbody>
            @forelse($subjects as $subject)
              <tr>
                <td class="icon-cell">{{ $subject->icon }}</td>
                <td class="font-weight-bold">{{ $subject->name }}</td>
                <td class="text-muted">{{ $subject->slug }}</td>
                <td>{{ $subject->sort_order ?? '-' }}</td>
                <td>
                  @if($subject->is_active)
                    <span class="badge badge-success">Active</span>
                  @else
                    <span class="badge badge-secondary">Inactive</span>
                  @endif
                </td>
                <td class="actions-cell">
                  <a href="{{ route('admin.subjects.edit', $subject) }}" class="btn-icon" title="Edit">
                    ✏️
                  </a>
                  <form action="{{ route('admin.subjects.destroy', $subject) }}" method="POST" style="display: inline;" onsubmit="return confirm('Are you sure you want to delete this subject?');">
                    @csrf
                    @method('DELETE')
                    <button type="submit" class="btn-icon" title="Delete">
                      🗑️
                    </button>
                  </form>
                </td>
              </tr>
            @empty
              <tr>
                <td colspan="6" class="text-center text-muted">No subjects found. Create your first subject!</td>
              </tr>
            @endforelse
          </tbody>
        </table>
      </div>

      <!-- Pagination -->
      @if($subjects->hasPages())
        <div class="pagination-wrapper">
          {{ $subjects->links() }}
        </div>
      @endif
    </div>

  </div>
</section>

<style>
.admin-page {
  background: #F9FAFB;
  min-height: 100vh;
  padding: 40px 20px;
}

.container {
  max-width: 1400px;
  margin: 0 auto;
}

/* Page Header */
.page-header {
  display: flex;
  justify-content: space-between;
  align-items: center;
  margin-bottom: 32px;
}

.page-title {
  font-size: 32px;
  font-weight: 900;
  color: #111827;
  margin-bottom: 4px;
}

.page-subtitle {
  color: #6B7280;
  font-size: 16px;
}

.header-actions {
  display: flex;
  gap: 12px;
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
  display: inline-block;
}

.btn-primary {
  background: #006A4E;
  color: white;
}

.btn-primary:hover {
  background: #004A35;
}

.btn-secondary {
  background: #E5E7EB;
  color: #374151;
}

.btn-secondary:hover {
  background: #D1D5DB;
}

.btn-icon {
  background: none;
  border: none;
  font-size: 18px;
  cursor: pointer;
  padding: 4px 8px;
  transition: transform 0.2s;
}

.btn-icon:hover {
  transform: scale(1.2);
}

/* Alert */
.alert {
  padding: 16px 20px;
  border-radius: 8px;
  margin-bottom: 24px;
  font-weight: 500;
}

.alert-success {
  background: #D1FAE5;
  color: #065F46;
  border: 1px solid #A7F3D0;
}

/* Content Card */
.content-card {
  background: white;
  border-radius: 12px;
  padding: 24px;
  box-shadow: 0 1px 3px rgba(0, 0, 0, 0.1);
}

/* Table */
.table-responsive {
  overflow-x: auto;
}

.data-table {
  width: 100%;
  border-collapse: collapse;
}

.data-table thead {
  background: #F9FAFB;
}

.data-table th {
  padding: 12px 16px;
  text-align: left;
  font-weight: 600;
  color: #374151;
  font-size: 14px;
  border-bottom: 2px solid #E5E7EB;
}

.data-table td {
  padding: 16px;
  border-bottom: 1px solid #E5E7EB;
  color: #111827;
  font-size: 14px;
}

.data-table tbody tr:hover {
  background: #F9FAFB;
}

.icon-cell {
  font-size: 24px;
  text-align: center;
}

.font-weight-bold {
  font-weight: 600;
}

.text-muted {
  color: #6B7280;
}

.text-center {
  text-align: center;
}

.actions-cell {
  white-space: nowrap;
}

/* Badge */
.badge {
  padding: 4px 12px;
  border-radius: 12px;
  font-size: 12px;
  font-weight: 600;
  display: inline-block;
}

.badge-success {
  background: #D1FAE5;
  color: #065F46;
}

.badge-secondary {
  background: #E5E7EB;
  color: #374151;
}

/* Pagination */
.pagination-wrapper {
  margin-top: 24px;
  display: flex;
  justify-content: center;
}

/* Responsive */
@media (max-width: 768px) {
  .page-header {
    flex-direction: column;
    align-items: flex-start;
    gap: 16px;
  }

  .header-actions {
    width: 100%;
    flex-direction: column;
  }

  .btn {
    width: 100%;
    text-align: center;
  }
}
</style>
@endsection
