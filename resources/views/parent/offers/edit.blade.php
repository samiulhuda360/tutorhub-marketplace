@extends('layouts.app')
@section('title', 'Edit Tuition Offer')

@section('content')
<section class="section">
  <div class="sectionTitle">
    <div>
      <h2>EDIT TUITION OFFER</h2>
      <div class="hint">Update your tuition offer details</div>
    </div>
    <a class="btn" href="{{ route('parent.offers.show', $offer->id) }}">Back to Offer</a>
  </div>

  @if($errors->any())
    <div class="card p" style="margin-top:12px;border-color:rgba(255,0,0,.35);background:rgba(255,0,0,.06);">
      <div style="font-weight:900;margin-bottom:6px;">Please fix the following errors:</div>
      <ul style="margin:0;padding-left:18px;">
        @foreach($errors->all() as $e)<li>{{ $e }}</li>@endforeach
      </ul>
    </div>
  @endif

  <form method="POST" action="{{ route('parent.offers.update', $offer->id) }}" class="card p" style="margin-top:14px;max-width:900px;">
    @csrf
    @method('PUT')

    <div class="stack" style="gap:20px;">

      <!-- Basic Information -->
      <div>
        <h3 style="margin-bottom:12px;">Basic Information</h3>

        <div class="grid cols-2" style="gap:12px;">
          <div>
            <label class="hint" style="display:block;margin-bottom:6px;">Category *</label>
            <select class="select" name="category_id" required>
              <option value="">Select category</option>
              @foreach($categories as $cat)
                <option value="{{ $cat->id }}" {{ old('category_id', $offer->category_id) == $cat->id ? 'selected' : '' }}>
                  {{ $cat->name }}
                </option>
              @endforeach
            </select>
          </div>

          <div>
            <label class="hint" style="display:block;margin-bottom:6px;">Area *</label>
            <select class="select" name="area_id" required>
              <option value="">Select area</option>
              @foreach($areas as $area)
                <option value="{{ $area->id }}" {{ old('area_id', $offer->area_id) == $area->id ? 'selected' : '' }}>
                  {{ $area->name }}
                </option>
              @endforeach
            </select>
          </div>
        </div>

        <div style="margin-top:12px;">
          <label class="hint" style="display:block;margin-bottom:6px;">Title *</label>
          <input class="input" name="title" value="{{ old('title', $offer->title) }}" placeholder="e.g., Need Math & Physics Tutor for Class 10" required>
        </div>

        <div style="margin-top:12px;">
          <label class="hint" style="display:block;margin-bottom:6px;">Description *</label>
          <textarea class="input" name="description" rows="4" placeholder="Describe your requirements in detail..." required>{{ old('description', $offer->description) }}</textarea>
        </div>
      </div>

      <!-- Student Details -->
      <div>
        <h3 style="margin-bottom:12px;">Student Details</h3>

        <div class="grid cols-2" style="gap:12px;">
          <div>
            <label class="hint" style="display:block;margin-bottom:6px;">Student Class *</label>
            <select class="select" name="student_class" required>
              <option value="">Select class</option>
              @foreach(['Class 1', 'Class 2', 'Class 3', 'Class 4', 'Class 5', 'Class 6', 'Class 7', 'Class 8', 'Class 9', 'Class 10', 'SSC', 'HSC', 'O Level', 'A Level', 'University'] as $class)
                <option value="{{ $class }}" {{ old('student_class', $offer->student_class) == $class ? 'selected' : '' }}>{{ $class }}</option>
              @endforeach
            </select>
          </div>

          <div>
            <label class="hint" style="display:block;margin-bottom:6px;">Tutor Gender Preference *</label>
            <select class="select" name="tutor_gender_preference" required>
              <option value="any" {{ old('tutor_gender_preference', $offer->tutor_gender_preference) == 'any' ? 'selected' : '' }}>Any</option>
              <option value="male" {{ old('tutor_gender_preference', $offer->tutor_gender_preference) == 'male' ? 'selected' : '' }}>Male Only</option>
              <option value="female" {{ old('tutor_gender_preference', $offer->tutor_gender_preference) == 'female' ? 'selected' : '' }}>Female Only</option>
            </select>
          </div>
        </div>
      </div>

      <!-- Subjects -->
      <div>
        <label class="hint" style="display:block;margin-bottom:6px;">Subjects Needed * (Select multiple)</label>
        <div class="grid cols-3" style="gap:10px;margin-top:10px;">
          @php
            $selectedSubjects = old('subjects', $offer->subjects ?? []);
            if (!is_array($selectedSubjects)) {
              $selectedSubjects = [];
            }
          @endphp
          @foreach($subjects as $subject)
            <label class="row" style="gap:8px;font-size:14px;">
              <input type="checkbox" name="subjects[]" value="{{ $subject->name }}"
                {{ in_array($subject->name, $selectedSubjects) ? 'checked' : '' }}>
              {{ $subject->name }}
            </label>
          @endforeach
        </div>
      </div>

      <!-- Tuition Details -->
      <div>
        <h3 style="margin-bottom:12px;">Tuition Details</h3>

        <div class="grid cols-3" style="gap:12px;">
          <div>
            <label class="hint" style="display:block;margin-bottom:6px;">Tuition Type *</label>
            <select class="select" name="tuition_type" required>
              <option value="home" {{ old('tuition_type', $offer->tuition_type) == 'home' ? 'selected' : '' }}>Home Tuition</option>
              <option value="online" {{ old('tuition_type', $offer->tuition_type) == 'online' ? 'selected' : '' }}>Online Tuition</option>
              <option value="center" {{ old('tuition_type', $offer->tuition_type) == 'center' ? 'selected' : '' }}>Coaching Center</option>
            </select>
          </div>

          <div>
            <label class="hint" style="display:block;margin-bottom:6px;">Days Per Week *</label>
            <input class="input" type="number" name="days_per_week" value="{{ old('days_per_week', $offer->days_per_week) }}" min="1" max="7" required>
          </div>

          <div>
            <label class="hint" style="display:block;margin-bottom:6px;">Monthly Salary (Tk) *</label>
            <input class="input" type="number" name="salary" value="{{ old('salary', $offer->salary) }}" min="0" step="500" placeholder="e.g., 8000" required>
          </div>
        </div>

        <div style="margin-top:12px;">
          <label class="hint" style="display:block;margin-bottom:6px;">Location / Address *</label>
          <input class="input" name="location" value="{{ old('location', $offer->location) }}" placeholder="e.g., House 12, Road 5, Block A, Mirpur 10" required>
        </div>
      </div>

      <!-- Offer Status -->
      <div>
        <h3 style="margin-bottom:12px;">Offer Status</h3>
        <div>
          <label class="hint" style="display:block;margin-bottom:6px;">Status *</label>
          <select class="select" name="status" required>
            <option value="open" {{ old('status', $offer->status) == 'open' ? 'selected' : '' }}>Open (Accepting Applications)</option>
            <option value="filled" {{ old('status', $offer->status) == 'filled' ? 'selected' : '' }}>Filled (Position Filled)</option>
            <option value="closed" {{ old('status', $offer->status) == 'closed' ? 'selected' : '' }}>Closed (No Longer Available)</option>
          </select>
        </div>
      </div>

      <!-- Submit -->
      <div class="row" style="gap:10px;">
        <button class="btn primary" type="submit">Save Changes</button>
        <a class="btn ghost" href="{{ route('parent.offers.show', $offer->id) }}">Cancel</a>
      </div>

    </div>
  </form>
</section>
@endsection
