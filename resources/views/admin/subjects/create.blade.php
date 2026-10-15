@extends('layouts.app')
@section('title', 'Add New Subject - Admin')

@section('content')
<section class="admin-page">
  <div class="container">

    <!-- Header -->
    <div class="page-header">
      <div>
        <h1 class="page-title">Add New Subject</h1>
        <p class="page-subtitle">Create a new subject for the platform</p>
      </div>
      <a href="{{ route('admin.subjects.index') }}" class="btn btn-secondary">← Back to Subjects</a>
    </div>

    <!-- Form Card -->
    <div class="form-card">
      <form action="{{ route('admin.subjects.store') }}" method="POST">
        @csrf

        <div class="form-group">
          <label for="name" class="form-label">Subject Name *</label>
          <input
            type="text"
            id="name"
            name="name"
            class="form-control @error('name') is-invalid @enderror"
            value="{{ old('name') }}"
            placeholder="e.g., Mathematics"
            required
          >
          @error('name')
            <div class="invalid-feedback">{{ $message }}</div>
          @enderror
        </div>

        <div class="form-group">
          <label for="icon" class="form-label">Icon (Emoji) *</label>
          <input
            type="text"
            id="icon"
            name="icon"
            class="form-control @error('icon') is-invalid @enderror"
            value="{{ old('icon') }}"
            placeholder="📐"
            maxlength="10"
            required
          >
          <small class="form-hint">Use a single emoji to represent this subject</small>
          @error('icon')
            <div class="invalid-feedback">{{ $message }}</div>
          @enderror
        </div>

        <div class="form-group">
          <label for="sort_order" class="form-label">Sort Order</label>
          <input
            type="number"
            id="sort_order"
            name="sort_order"
            class="form-control @error('sort_order') is-invalid @enderror"
            value="{{ old('sort_order', 0) }}"
            min="0"
            placeholder="0"
          >
          <small class="form-hint">Lower numbers appear first (0 = highest priority)</small>
          @error('sort_order')
            <div class="invalid-feedback">{{ $message }}</div>
          @enderror
        </div>

        <div class="form-group">
          <label class="checkbox-label">
            <input
              type="checkbox"
              name="is_active"
              value="1"
              {{ old('is_active', true) ? 'checked' : '' }}
            >
            <span>Active (visible on the website)</span>
          </label>
        </div>

        <div class="form-actions">
          <button type="submit" class="btn btn-primary">Create Subject</button>
          <a href="{{ route('admin.subjects.index') }}" class="btn btn-secondary">Cancel</a>
        </div>
      </form>
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
  max-width: 800px;
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

/* Form Card */
.form-card {
  background: white;
  border-radius: 12px;
  padding: 32px;
  box-shadow: 0 1px 3px rgba(0, 0, 0, 0.1);
}

/* Form */
.form-group {
  margin-bottom: 24px;
}

.form-label {
  display: block;
  font-weight: 600;
  color: #374151;
  margin-bottom: 8px;
  font-size: 14px;
}

.form-control {
  width: 100%;
  padding: 12px 16px;
  border: 2px solid #E5E7EB;
  border-radius: 8px;
  font-size: 14px;
  transition: all 0.2s;
}

.form-control:focus {
  outline: none;
  border-color: #006A4E;
  box-shadow: 0 0 0 3px rgba(0, 106, 78, 0.1);
}

.form-control.is-invalid {
  border-color: #DC2626;
}

.form-hint {
  display: block;
  margin-top: 6px;
  font-size: 13px;
  color: #6B7280;
}

.invalid-feedback {
  display: block;
  margin-top: 6px;
  font-size: 13px;
  color: #DC2626;
  font-weight: 500;
}

.checkbox-label {
  display: flex;
  align-items: center;
  gap: 8px;
  cursor: pointer;
  font-size: 14px;
  color: #374151;
}

.checkbox-label input[type="checkbox"] {
  width: 18px;
  height: 18px;
  cursor: pointer;
}

/* Form Actions */
.form-actions {
  display: flex;
  gap: 12px;
  margin-top: 32px;
  padding-top: 24px;
  border-top: 1px solid #E5E7EB;
}

/* Buttons */
.btn {
  padding: 12px 24px;
  border-radius: 8px;
  font-weight: 600;
  border: none;
  cursor: pointer;
  text-decoration: none;
  transition: all 0.2s;
  display: inline-block;
  font-size: 14px;
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

/* Responsive */
@media (max-width: 768px) {
  .page-header {
    flex-direction: column;
    align-items: flex-start;
    gap: 16px;
  }

  .form-actions {
    flex-direction: column;
  }

  .btn {
    width: 100%;
    text-align: center;
  }
}
</style>
@endsection
