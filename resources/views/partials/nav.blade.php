@php
  $areas = \App\Models\Area::whereNull('parent_id')->where('is_active', true)->orderBy('sort_order')->get();
  $dhakaSubAreas = \App\Models\Area::where('parent_id', function($query) {
    $query->select('id')->from('areas')->where('slug', 'dhaka')->limit(1);
  })->where('is_active', true)->orderBy('sort_order')->get();
  $subjects = \App\Models\Subject::where('is_active', true)->orderBy('sort_order')->get();
  $levels = \App\Models\Level::where('is_active', true)->orderBy('sort_order')->get();
@endphp

<header class="nav">
  <div class="container">
    <div class="navbar">
      <a class="brand" href="{{ route('home') }}">
        <div class="logo"><span>T</span></div>
        <div>
          <div class="brand-title">Tutor<span>Hub</span></div>
          <div class="brand-sub">Tuition made easy</div>
        </div>
      </a>

      <nav class="navlinks">
        <a href="{{ route('home') }}">Home</a>

        <!-- By Area Dropdown -->
        <div class="dropdown">
          <button class="dropdown-toggle">By Area ▼</button>
          <div class="dropdown-menu">
            <div class="dropdown-grid">
              @foreach($dhakaSubAreas as $area)
                <a href="{{ route('locations.sub', ['dhaka', $area->slug]) }}" class="dropdown-item">
                  {{ $area->name }}
                </a>
              @endforeach
            </div>
          </div>
        </div>

        <!-- By Subject Dropdown -->
        <div class="dropdown">
          <button class="dropdown-toggle">By Subject ▼</button>
          <div class="dropdown-menu">
            @foreach($subjects as $subject)
              <a href="{{ route('subjects.show', $subject->slug) }}" class="dropdown-item">
                <span class="item-icon">{{ $subject->icon }}</span>
                {{ $subject->name }}
              </a>
            @endforeach
          </div>
        </div>

        <!-- By Level Dropdown -->
        <div class="dropdown">
          <button class="dropdown-toggle">By Level ▼</button>
          <div class="dropdown-menu">
            @foreach($levels as $level)
              <a href="{{ route('levels.show', $level->slug) }}" class="dropdown-item">
                <span class="item-icon">{{ $level->icon }}</span>
                {{ $level->name }}
              </a>
            @endforeach
          </div>
        </div>

        <!-- By Type Dropdown -->
        <div class="dropdown">
          <button class="dropdown-toggle">By Type ▼</button>
          <div class="dropdown-menu">
            <a href="{{ route('types.show', 'home') }}" class="dropdown-item">
              <span class="item-icon">🏠</span>
              Home Tuition
            </a>
            <a href="{{ route('types.show', 'online') }}" class="dropdown-item">
              <span class="item-icon">💻</span>
              Online Tuition
            </a>
            <a href="{{ route('types.show', 'group') }}" class="dropdown-item">
              <span class="item-icon">👥</span>
              Group Tuition
            </a>
            <a href="{{ route('types.show', 'assignment') }}" class="dropdown-item">
              <span class="item-icon">📝</span>
              Assignment Help
            </a>
          </div>
        </div>

        <a href="{{ auth()->check() && auth()->user()->role === 'parent' ? route('parent.offers.create') : route('register.parent') }}">Post Job</a>
        <a href="{{ route('about') }}">About Us</a>
        <a href="{{ route('contact') }}">Contact</a>
      </nav>

      <div class="navright row" style="gap:8px;">
        @include('partials.nav-account')
      </div>

      <button class="btn mobileBtn" type="button" data-mobile-toggle>Menu</button>
    </div>

    <!-- Mobile Menu -->
    <div class="mobileMenu" data-mobile-menu data-open="0">
      <div class="card p">
        <div class="stack">
          <a class="btn" href="{{ route('home') }}">Home</a>

          <div class="mobile-dropdown">
            <button class="btn mobile-dropdown-toggle" onclick="toggleMobileDropdown(this)">
              By Area ▼
            </button>
            <div class="mobile-dropdown-content">
              @foreach($dhakaSubAreas as $area)
                <a href="{{ route('locations.sub', ['dhaka', $area->slug]) }}" class="mobile-dropdown-item">
                  {{ $area->name }}
                </a>
              @endforeach
            </div>
          </div>

          <div class="mobile-dropdown">
            <button class="btn mobile-dropdown-toggle" onclick="toggleMobileDropdown(this)">
              By Subject ▼
            </button>
            <div class="mobile-dropdown-content">
              @foreach($subjects as $subject)
                <a href="{{ route('subjects.show', $subject->slug) }}" class="mobile-dropdown-item">
                  {{ $subject->icon }} {{ $subject->name }}
                </a>
              @endforeach
            </div>
          </div>

          <div class="mobile-dropdown">
            <button class="btn mobile-dropdown-toggle" onclick="toggleMobileDropdown(this)">
              By Level ▼
            </button>
            <div class="mobile-dropdown-content">
              @foreach($levels as $level)
                <a href="{{ route('levels.show', $level->slug) }}" class="mobile-dropdown-item">
                  {{ $level->icon }} {{ $level->name }}
                </a>
              @endforeach
            </div>
          </div>

          <div class="mobile-dropdown">
            <button class="btn mobile-dropdown-toggle" onclick="toggleMobileDropdown(this)">
              By Type ▼
            </button>
            <div class="mobile-dropdown-content">
              <a href="{{ route('types.show', 'home') }}" class="mobile-dropdown-item">🏠 Home Tuition</a>
              <a href="{{ route('types.show', 'online') }}" class="mobile-dropdown-item">💻 Online Tuition</a>
              <a href="{{ route('types.show', 'group') }}" class="mobile-dropdown-item">👥 Group Tuition</a>
              <a href="{{ route('types.show', 'assignment') }}" class="mobile-dropdown-item">📝 Assignment Help</a>
            </div>
          </div>

          <a class="btn" href="{{ route('register.parent') }}">Post Job</a>
          <a class="btn" href="{{ route('about') }}">About Us</a>
          <a class="btn" href="{{ route('contact') }}">Contact</a>

          <div class="row">
            @include('partials.nav-account')
          </div>
        </div>
      </div>
    </div>

  </div>
</header>

<style>
/* Dropdown Styles */
.dropdown {
  position: relative;
  display: inline-block;
}

.dropdown-toggle {
  background: none;
  border: none;
  color: inherit;
  font-size: inherit;
  font-family: inherit;
  cursor: pointer;
  padding: 0;
  font-weight: 500;
  transition: color 0.2s;
}

.dropdown-toggle:hover {
  color: #006A4E;
}

.dropdown-menu {
  display: none;
  position: absolute;
  top: 100%;
  left: 0;
  background: white;
  border-radius: 12px;
  box-shadow: 0 8px 24px rgba(0, 0, 0, 0.15);
  padding: 16px;
  min-width: 220px;
  z-index: 1000;
  margin-top: 8px;
}

.dropdown:hover .dropdown-menu {
  display: block;
}

.dropdown-grid {
  display: grid;
  grid-template-columns: repeat(3, 1fr);
  gap: 8px;
  max-width: 600px;
}

.dropdown-item {
  display: flex;
  align-items: center;
  gap: 8px;
  padding: 10px 14px;
  border-radius: 8px;
  text-decoration: none;
  color: #374151;
  font-size: 14px;
  transition: all 0.2s;
  white-space: nowrap;
}

.dropdown-item:hover {
  background: #F0FDF4;
  color: #006A4E;
}

.item-icon {
  font-size: 18px;
}

/* Mobile Dropdown */
.mobile-dropdown {
  width: 100%;
}

.mobile-dropdown-toggle {
  width: 100%;
  text-align: left;
  background: none;
  border: none;
  font-size: inherit;
  font-family: inherit;
  cursor: pointer;
  padding: 12px 16px;
}

.mobile-dropdown-content {
  display: none;
  padding-left: 16px;
}

.mobile-dropdown-content.active {
  display: block;
}

.mobile-dropdown-item {
  display: block;
  padding: 10px 16px;
  text-decoration: none;
  color: #374151;
  font-size: 14px;
  transition: all 0.2s;
}

.mobile-dropdown-item:hover {
  background: #F0FDF4;
  color: #006A4E;
  border-radius: 8px;
}

@media (max-width: 768px) {
  .navlinks {
    display: none;
  }
}
</style>

<script>
function toggleMobileDropdown(button) {
  const content = button.nextElementSibling;
  const isActive = content.classList.contains('active');

  // Close all other dropdowns
  document.querySelectorAll('.mobile-dropdown-content').forEach(el => {
    el.classList.remove('active');
  });

  // Toggle current dropdown
  if (!isActive) {
    content.classList.add('active');
    button.textContent = button.textContent.replace('▼', '▲');
  } else {
    button.textContent = button.textContent.replace('▲', '▼');
  }
}
</script>
