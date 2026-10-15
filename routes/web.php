<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\HomeController;
use App\Http\Controllers\TutorController;
use App\Http\Controllers\PublicOfferController;
use App\Http\Controllers\Admin\AuthController as AdminAuth;
use App\Http\Controllers\Admin\DashboardController;
use App\Http\Controllers\Admin\CategoryController;
use App\Http\Controllers\Admin\AreaController;
use App\Http\Controllers\Admin\MatchController;
use App\Http\Controllers\AuthController;

use App\Http\Controllers\Tutor\DashboardController as TutorDashboardController;
use App\Http\Controllers\Tutor\ProfileController as TutorProfileController;
use App\Http\Controllers\Tutor\TutorOfferController;
use App\Http\Controllers\Tutor\TutorApplicationController;
use App\Http\Controllers\Tutor\InvitationController as TutorInvitationController;

use App\Http\Controllers\Parent\DashboardController as ParentDashboardController;
use App\Http\Controllers\Parent\TuitionOfferController;
use App\Http\Controllers\Parent\ParentApplicationController;
use App\Http\Controllers\Parent\InvitationController as ParentInvitationController;
use App\Http\Controllers\PublicLocationController;
use App\Http\Controllers\PublicSubjectController;
use App\Http\Controllers\PublicLevelController;
use App\Http\Controllers\PublicTypeController;
use App\Http\Controllers\Admin\AiUsageController;
use App\Http\Controllers\Admin\ReviewController;
use App\Http\Controllers\Parent\OfferDraftController;
use App\Http\Controllers\Tutor\WritingController;
use App\Models\Level;
use App\Models\Subject;

// Public Routes
Route::get('/', [HomeController::class, 'index'])->name('home');

/** Tutors */
Route::get('/tutors', [TutorController::class, 'index'])->name('tutors.index');

/** Browse by Subject, Level, Type */
Route::get('/subject/{slug}', [PublicSubjectController::class, 'show'])->name('subjects.show');
Route::get('/level/{slug}', [PublicLevelController::class, 'show'])->name('levels.show');
Route::get('/type/{type}', [PublicTypeController::class, 'show'])->name('types.show');

/** Location-based Pages (SEO) */
Route::get('/location/{mainArea}', [PublicLocationController::class, 'showMainArea'])->name('locations.main');
Route::get('/location/{mainArea}/{subArea}', [PublicLocationController::class, 'showSubLocation'])->name('locations.sub');

/** Individual Tutor Profile */
Route::get('/tutors/{code}', [TutorController::class, 'show'])->name('tutors.show');

/** Categories and Areas */
Route::get('/categories', fn () => view('categories.index', [
    'subjects' => Subject::active()->get(),
    'levels' => Level::active()->get(),
]))->name('categories.index');
Route::get('/categories/{slug}', fn ($slug) => view('categories.show', compact('slug')))->name('categories.show');

Route::get('/areas', fn () => view('areas.index'))->name('areas.index');
Route::get('/areas/{slug}', fn ($slug) => view('areas.show', compact('slug')))->name('areas.show');

/** Tuition Offers */
Route::get('/tuition-offers', [PublicOfferController::class, 'index'])->name('tuition_offers.index');
Route::get('/tuition-offers/{id}', [PublicOfferController::class, 'show'])->name('tuition_offers.show');

/** Blog */
Route::get('/blog', fn () => view('blog.index'))->name('blog.index');
Route::get('/blog/{slug}', fn ($slug) => view('blog.show', compact('slug')))->name('blog.show');

/** Static */
Route::get('/about', fn () => view('static.about'))->name('about');
Route::get('/contact', fn () => view('static.contact'))->name('contact');

// Auth pages
Route::get('/login', [AuthController::class, 'showLogin'])->name('login');
Route::get('/register', [AuthController::class, 'showRegisterSelector'])->name('register');
Route::get('/register/tutor', [AuthController::class, 'showRegisterTutor'])->name('register.tutor');
Route::get('/register/parent', [AuthController::class, 'showRegisterParent'])->name('register.parent');

// Auth actions
Route::post('/login', [AuthController::class, 'login'])->name('login.submit');
Route::post('/register/tutor', [AuthController::class, 'registerTutor'])->name('register.tutor.submit');
Route::post('/register/parent', [AuthController::class, 'registerParent'])->name('register.parent.submit');
Route::post('/logout', [AuthController::class, 'logout'])->name('logout');

// Admin Routes
Route::prefix('admin')->name('admin.')->group(function () {
    // Admin auth
    Route::get('/login', [AdminAuth::class, 'showLogin'])->name('login');
    Route::post('/login', [AdminAuth::class, 'login'])->name('login.submit');
    Route::post('/logout', [AdminAuth::class, 'logout'])->name('logout');

    // Protected admin area
    Route::middleware(['web', 'auth', 'role:admin'])->group(function () {
        Route::get('/', [DashboardController::class, 'index'])->name('dashboard');
        Route::get('/categories', [CategoryController::class, 'index'])->name('categories.index');
        Route::get('/categories/create', [CategoryController::class, 'create'])->name('categories.create');
        Route::post('/categories', [CategoryController::class, 'store'])->name('categories.store');
        Route::get('/categories/{category}/edit', [CategoryController::class, 'edit'])->name('categories.edit');
        Route::put('/categories/{category}', [CategoryController::class, 'update'])->name('categories.update');
        Route::delete('/categories/{category}', [CategoryController::class, 'destroy'])->name('categories.destroy');

        // Area Crud
        Route::get('/areas', [AreaController::class, 'index'])->name('areas.index');
        Route::get('/areas/create', [AreaController::class, 'create'])->name('areas.create');
        Route::post('/areas', [AreaController::class, 'store'])->name('areas.store');
        Route::get('/areas/{area}/edit', [AreaController::class, 'edit'])->name('areas.edit');
        Route::put('/areas/{area}', [AreaController::class, 'update'])->name('areas.update');
        Route::delete('/areas/{area}', [AreaController::class, 'destroy'])->name('areas.destroy');

        // Match Management
        Route::get('/matches', [MatchController::class, 'index'])->name('matches.index');
        Route::prefix('/matches/{type}/{id}')->whereIn('type', ['application', 'invitation'])->whereNumber('id')->group(function () {
            Route::get('/', [MatchController::class, 'show'])->name('matches.show');
            Route::post('/mark-contacted', [MatchController::class, 'markAsContacted'])->name('matches.mark-contacted');
            Route::post('/mark-negotiating', [MatchController::class, 'markAsNegotiating'])->name('matches.mark-negotiating');
            Route::post('/record-commission', [MatchController::class, 'recordCommission'])->name('matches.record-commission');
            Route::post('/share-contact', [MatchController::class, 'shareContactDetails'])->name('matches.share-contact');
            Route::post('/update-notes', [MatchController::class, 'updateNotes'])->name('matches.update-notes');
        });

        // Subjects (the levels list is managed in the seeders)
        Route::resource('subjects', \App\Http\Controllers\Admin\SubjectController::class)->except('show');

        // AI usage and profile review
        Route::get('/ai', AiUsageController::class)->name('ai');
        Route::get('/review', [ReviewController::class, 'index'])->name('review');
        Route::post('/tutors/{profile}/verify', [ReviewController::class, 'verify'])->name('tutors.verify');
    });
});

// Parent Routes
Route::middleware(['web', 'auth', 'role:parent'])->prefix('parent')->name('parent.')->group(function () {
    Route::get('/dashboard', [ParentDashboardController::class, 'index'])->name('dashboard');
    
    // Tuition Offers Management
    Route::get('/offers', [TuitionOfferController::class, 'index'])->name('offers.index');
    Route::get('/offers/create', [TuitionOfferController::class, 'create'])->name('offers.create');
    Route::post('/offers', [TuitionOfferController::class, 'store'])->name('offers.store');
    Route::post('/offers/draft', OfferDraftController::class)->middleware('throttle:ai')->name('offers.draft');
    Route::get('/offers/{offer}', [TuitionOfferController::class, 'show'])->name('offers.show');
    Route::get('/offers/{offer}/edit', [TuitionOfferController::class, 'edit'])->name('offers.edit');
    Route::put('/offers/{offer}', [TuitionOfferController::class, 'update'])->name('offers.update');
    Route::delete('/offers/{offer}', [TuitionOfferController::class, 'destroy'])->name('offers.destroy');
    
    // Applications Management
    Route::get('/applications', [ParentApplicationController::class, 'index'])->name('applications.index');
    Route::get('/applications/{application}', [ParentApplicationController::class, 'show'])->name('applications.show');
    Route::post('/applications/{application}/accept', [ParentApplicationController::class, 'accept'])->name('applications.accept');
    Route::post('/applications/{application}/reject', [ParentApplicationController::class, 'reject'])->name('applications.reject');

    // Invitations Management
    Route::get('/invitations', [ParentInvitationController::class, 'index'])->name('invitations.index');
    Route::post('/invitations', [ParentInvitationController::class, 'store'])->name('invitations.store');
    Route::get('/invitations/{invitation}', [ParentInvitationController::class, 'show'])->name('invitations.show');
    Route::delete('/invitations/{invitation}', [ParentInvitationController::class, 'destroy'])->name('invitations.destroy');
});

// Tutor Routes
Route::middleware(['web', 'auth', 'role:tutor'])->prefix('tutor')->name('tutor.')->group(function () {
    Route::get('/dashboard', [TutorDashboardController::class, 'index'])->name('dashboard');
    
    // Profile Management
    Route::get('/profile/create', [TutorProfileController::class, 'create'])->name('profile.create');
    Route::post('/profile', [TutorProfileController::class, 'store'])->name('profile.store');
    Route::get('/profile/edit', [TutorProfileController::class, 'edit'])->name('profile.edit');
    Route::put('/profile', [TutorProfileController::class, 'update'])->name('profile.update');
    Route::post('/profile/bio-draft', [WritingController::class, 'bio'])->middleware('throttle:ai')->name('profile.bio-draft');
    
    // Browse Tuition Offers
    Route::get('/offers', [TutorOfferController::class, 'index'])->name('offers.index');
    Route::get('/offers/{offer}', [TutorOfferController::class, 'show'])->name('offers.show');
    Route::post('/offers/{offer}/apply', [TutorOfferController::class, 'apply'])->name('offers.apply');
    Route::post('/offers/{offer}/cover-letter', [WritingController::class, 'coverLetter'])->middleware('throttle:ai')->name('offers.cover-letter');
    
    // My Applications
    Route::get('/applications', [TutorApplicationController::class, 'index'])->name('applications.index');
    Route::get('/applications/{application}', [TutorApplicationController::class, 'show'])->name('applications.show');

    // Invitations Management
    Route::get('/invitations', [TutorInvitationController::class, 'index'])->name('invitations.index');
    Route::get('/invitations/{invitation}', [TutorInvitationController::class, 'show'])->name('invitations.show');
    Route::post('/invitations/{invitation}/accept', [TutorInvitationController::class, 'accept'])->name('invitations.accept');
    Route::post('/invitations/{invitation}/reject', [TutorInvitationController::class, 'reject'])->name('invitations.reject');
});