@extends('layouts.app')
@section('title', 'Create Tutor Profile')

@section('content')
<section class="section">
  <div class="sectionTitle">
    <div>
      <h2>CREATE YOUR TUTOR PROFILE</h2>
      <div class="hint">Complete your profile to start applying for tuition offers</div>
    </div>
    <a class="btn" href="{{ route('tutor.dashboard') }}">Back to Dashboard</a>
  </div>

  @if($errors->any())
    <div class="card p" style="margin-top:12px;border-color:rgba(255,0,0,.35);background:rgba(255,0,0,.06);">
      <div style="font-weight:900;margin-bottom:6px;">Please fix the following errors:</div>
      <ul style="margin:0;padding-left:18px;">
        @foreach($errors->all() as $e)<li>{{ $e }}</li>@endforeach
      </ul>
    </div>
  @endif

  <form method="POST" action="{{ route('tutor.profile.store') }}" class="card p" style="margin-top:14px;max-width:900px;">
    @csrf

    <div class="stack" style="gap:20px;">
      
      <!-- Personal Information -->
      <div>
        <h3 style="margin-bottom:12px;">Personal Information</h3>
        
        <div class="grid cols-2" style="gap:12px;">
          <div>
            <label class="hint" style="display:block;margin-bottom:6px;">Phone Number *</label>
            <input class="input" name="phone" value="{{ old('phone') }}" placeholder="01712345678" required>
          </div>

          <div>
            <label class="hint" style="display:block;margin-bottom:6px;">Gender *</label>
            <select class="select" name="gender" required>
              <option value="">Select gender</option>
              <option value="male" {{ old('gender') == 'male' ? 'selected' : '' }}>Male</option>
              <option value="female" {{ old('gender') == 'female' ? 'selected' : '' }}>Female</option>
              <option value="other" {{ old('gender') == 'other' ? 'selected' : '' }}>Other</option>
            </select>
          </div>

          <div>
            <label class="hint" style="display:block;margin-bottom:6px;">Date of Birth</label>
            <input class="input" type="date" name="date_of_birth" value="{{ old('date_of_birth') }}">
          </div>

          <div>
            <label class="hint" style="display:block;margin-bottom:6px;">Years of Experience *</label>
            <input class="input" type="number" name="experience_years" value="{{ old('experience_years', 0) }}" min="0" max="50" required>
          </div>
        </div>
      </div>

      <!-- Education -->
      <div>
        <label class="hint" style="display:block;margin-bottom:6px;">Education / Current Institution *</label>
        <textarea class="input" name="education" rows="3" placeholder="e.g., BSc in Mathematics, Dhaka University" required>{{ old('education') }}</textarea>
      </div>

      <!-- Bio -->
      <div>
        <div class="spread" style="margin-bottom:6px;">
          <label class="hint">About Me / Teaching Philosophy</label>
          <button type="button" class="btn" data-ai-draft data-target="textarea[name=bio]"
                  data-url="{{ route('tutor.profile.bio-draft') }}">Write a first draft</button>
        </div>
        <textarea class="input" name="bio" rows="4" placeholder="Tell parents about your teaching style and approach...">{{ old('bio') }}</textarea>
        <div style="font-size:12px;color:var(--muted);margin-top:4px;" data-ai-status>Max 1000 characters. Fill in your subjects, classes and education first: the draft uses only those.</div>
      </div>

      <!-- Subjects -->
      <div>
        <label class="hint" style="display:block;margin-bottom:6px;">Subjects I Can Teach * (Select multiple)</label>
        <div class="grid cols-3" style="gap:10px;margin-top:10px;">
          @foreach($subjects as $subject)
            <label class="row" style="gap:8px;font-size:14px;">
              <input type="checkbox" name="subjects[]" value="{{ $subject->name }}" 
                {{ is_array(old('subjects')) && in_array($subject->name, old('subjects')) ? 'checked' : '' }}>
              {{ $subject->name }}
            </label>
          @endforeach
        </div>
      </div>

      <!-- Classes -->
      <div>
        <label class="hint" style="display:block;margin-bottom:6px;">Classes I Can Teach * (Select multiple)</label>
        <div class="grid cols-4" style="gap:10px;margin-top:10px;">
          @foreach(['Class 1', 'Class 2', 'Class 3', 'Class 4', 'Class 5', 'Class 6', 'Class 7', 'Class 8', 'Class 9', 'Class 10', 'SSC', 'HSC', 'O Level', 'A Level', 'University'] as $class)
            <label class="row" style="gap:8px;font-size:14px;">
              <input type="checkbox" name="classes[]" value="{{ $class }}"
                {{ is_array(old('classes')) && in_array($class, old('classes')) ? 'checked' : '' }}>
              {{ $class }}
            </label>
          @endforeach
        </div>
      </div>

      <!-- Areas -->
      <div>
        <label class="hint" style="display:block;margin-bottom:6px;">Areas Where I Can Teach * (Select multiple)</label>
        <div class="grid cols-3" style="gap:10px;margin-top:10px;">
          @foreach($areas as $area)
            <label class="row" style="gap:8px;font-size:14px;">
              <input type="checkbox" name="areas[]" value="{{ $area->id }}"
                {{ is_array(old('areas')) && in_array($area->id, old('areas')) ? 'checked' : '' }}>
              {{ $area->name }}
            </label>
          @endforeach
        </div>
      </div>

      <!-- Hourly Rate -->
      <div>
        <label class="hint" style="display:block;margin-bottom:6px;">Expected Salary (Monthly in Tk) *</label>
        <input class="input" type="number" name="hourly_rate" value="{{ old('hourly_rate') }}" min="0" step="100" placeholder="e.g., 8000" required style="max-width:300px;">
      </div>

      <!-- Availability -->
      <div>
        <label class="hint" style="display:block;margin-bottom:6px;">Availability (Days & Times)</label>
        <textarea class="input" name="availability" rows="3" placeholder="e.g., Saturday-Thursday, 5pm-8pm">{{ old('availability') }}</textarea>
      </div>

      <!-- Submit -->
      <div class="row" style="gap:10px;">
        <button class="btn primary" type="submit">Create Profile</button>
        <a class="btn ghost" href="{{ route('tutor.dashboard') }}">Cancel</a>
      </div>

    </div>
  </form>
</section>
@endsection