@extends('layouts.app')
@section('title', 'Post Tuition Offer')

@section('content')
<section class="section">
  <div class="sectionTitle">
    <div>
      <h2>POST NEW TUITION OFFER</h2>
      <div class="hint">Fill in the details to find the perfect tutor</div>
    </div>
    <a class="btn" href="{{ route('parent.dashboard') }}">Back to Dashboard</a>
  </div>

  @if($errors->any())
    <div class="card p" style="margin-top:12px;border-color:rgba(255,0,0,.35);background:rgba(255,0,0,.06);">
      <div style="font-weight:900;margin-bottom:6px;">Please fix the following errors:</div>
      <ul style="margin:0;padding-left:18px;">
        @foreach($errors->all() as $e)<li>{{ $e }}</li>@endforeach
      </ul>
    </div>
  @endif

  <div class="card p ai-panel stack" style="margin-top:14px;max-width:900px;gap:10px;"
       data-ai-intake data-url="{{ route('parent.offers.draft') }}" data-form="form[data-offer-form]">
    <div class="spread">
      <h3>Describe what you need</h3>
      <span class="ai-tag">{{ app(\App\Ai\LlmClient::class)->isLive() ? 'AI assistant' : 'Smart fill' }}</span>
    </div>
    <div class="hint">Write it the way you would tell a friend, in English or Bangla. We fill in the form below; you check it and post.</div>
    <textarea class="input" maxlength="1500" placeholder="e.g. Class 8 er meyer jonno female math tutor lagbe, Mirpur, sopthahe 3 din, 8000 taka"></textarea>
    <div class="row" style="gap:12px;">
      <button class="btn primary" type="button" data-ai-run>Fill the form for me</button>
      <span class="small" data-ai-status></span>
    </div>
  </div>

  <form method="POST" action="{{ route('parent.offers.store') }}" class="card p" data-offer-form style="margin-top:14px;max-width:900px;">
    @csrf

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
                <option value="{{ $cat->id }}" {{ old('category_id') == $cat->id ? 'selected' : '' }}>
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
                <option value="{{ $area->id }}" {{ old('area_id') == $area->id ? 'selected' : '' }}>
                  {{ $area->name }}
                </option>
              @endforeach
            </select>
          </div>
        </div>

        <div style="margin-top:12px;">
          <label class="hint" style="display:block;margin-bottom:6px;">Title *</label>
          <input class="input" name="title" value="{{ old('title') }}" placeholder="e.g., Need Math & Physics Tutor for Class 10" required>
        </div>

        <div style="margin-top:12px;">
          <label class="hint" style="display:block;margin-bottom:6px;">Description *</label>
          <textarea class="input" name="description" rows="4" placeholder="Describe your requirements in detail..." required>{{ old('description') }}</textarea>
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
              @foreach(\App\Support\TuitionCatalog::CLASSES as $class)
                <option value="{{ $class }}" {{ old('student_class') == $class ? 'selected' : '' }}>{{ $class }}</option>
              @endforeach
            </select>
          </div>

          <div>
            <label class="hint" style="display:block;margin-bottom:6px;">Tutor Gender Preference *</label>
            <select class="select" name="tutor_gender_preference" required>
              <option value="any" {{ old('tutor_gender_preference') == 'any' ? 'selected' : '' }}>Any</option>
              <option value="male" {{ old('tutor_gender_preference') == 'male' ? 'selected' : '' }}>Male Only</option>
              <option value="female" {{ old('tutor_gender_preference') == 'female' ? 'selected' : '' }}>Female Only</option>
            </select>
          </div>
        </div>
      </div>

      <!-- Subjects -->
      <div>
        <label class="hint" style="display:block;margin-bottom:6px;">Subjects Needed * (Select multiple)</label>
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

      <!-- Tuition Details -->
      <div>
        <h3 style="margin-bottom:12px;">Tuition Details</h3>
        
        <div class="grid cols-3" style="gap:12px;">
          <div>
            <label class="hint" style="display:block;margin-bottom:6px;">Tuition Type *</label>
            <select class="select" name="tuition_type" required>
              <option value="home" {{ old('tuition_type') == 'home' ? 'selected' : '' }}>Home Tuition</option>
              <option value="online" {{ old('tuition_type') == 'online' ? 'selected' : '' }}>Online Tuition</option>
              <option value="center" {{ old('tuition_type') == 'center' ? 'selected' : '' }}>Coaching Center</option>
            </select>
          </div>

          <div>
            <label class="hint" style="display:block;margin-bottom:6px;">Days Per Week *</label>
            <input class="input" type="number" name="days_per_week" value="{{ old('days_per_week', 3) }}" min="1" max="7" required>
          </div>

          <div>
            <label class="hint" style="display:block;margin-bottom:6px;">Monthly Salary (Tk) *</label>
            <input class="input" type="number" name="salary" value="{{ old('salary') }}" min="0" step="100" placeholder="e.g., 8000" required>
          </div>
        </div>

        <div style="margin-top:12px;">
          <label class="hint" style="display:block;margin-bottom:6px;">Location / Address *</label>
          <input class="input" name="location" value="{{ old('location') }}" placeholder="e.g., House 12, Road 5, Block A, Mirpur 10" required>
        </div>
      </div>

      <!-- Submit -->
      <div class="row" style="gap:10px;">
        <button class="btn primary" type="submit">Post Offer</button>
        <a class="btn ghost" href="{{ route('parent.dashboard') }}">Cancel</a>
      </div>

    </div>
  </form>
</section>
@endsection
