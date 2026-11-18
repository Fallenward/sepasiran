<?php

use App\Http\Controllers\AboutController;
use App\Http\Controllers\Admin\AdminProductController;
use App\Http\Controllers\Admin\AdminWalletController;
use App\Http\Controllers\Admin\BlogController;
use App\Http\Controllers\Admin\CoachController;
use App\Http\Controllers\Admin\CourseController;
use App\Http\Controllers\Admin\DashboardController;
use App\Http\Controllers\Admin\DogController;
use App\Http\Controllers\Admin\PermissionController;
use App\Http\Controllers\Admin\RoleController;
use App\Http\Controllers\Admin\TransactionController;
use App\Http\Controllers\Auth\AuthenticatedSessionController;
use App\Http\Controllers\Auth\OtpController;
use App\Http\Controllers\BlogRatingController;
use App\Http\Controllers\BookingController;
use App\Http\Controllers\BreedController;
use App\Http\Controllers\BreederController;
use App\Http\Controllers\CartController;
use App\Http\Controllers\CategoryController;
use App\Http\Controllers\ClinicController;
use App\Http\Controllers\ClinicInsuranceController;
use App\Http\Controllers\DiscountController;
use App\Http\Controllers\DogSupplyController;
use App\Http\Controllers\Frontend\HomeController;
use App\Http\Controllers\Frontend\ServiceController as FrontendServiceController;
use App\Http\Controllers\Frontend\SupportController;
use App\Http\Controllers\FrontendController;
use App\Http\Controllers\InsuranceController;
use App\Http\Controllers\MenuController;
use App\Http\Controllers\OnlineCourseController;
use App\Http\Controllers\ProductController;
use App\Http\Controllers\ServiceController;
use App\Http\Controllers\ShelterController;
use App\Http\Controllers\SkillController;
use App\Http\Controllers\SliderController;
use App\Http\Controllers\StoreController;
use App\Http\Controllers\User\UserDashboardController;
use App\Http\Controllers\UserController;
use Illuminate\Support\Facades\Route;

// ----------------------------------------------
// Admin Routes with auth + is_admin middleware
// ----------------------------------------------
Route::middleware(['auth', 'is_admin', 'throttle.mutations:admin-mutations'])
    ->prefix('admin')
    ->name('admin.')
    ->group(function () {
        // Dashboard
        Route::get('/dashboard', [DashboardController::class, 'index'])->name('dashboard.index');

        // Resource controllers
        Route::resources([
            'users' => UserController::class,
            'blogs' => BlogController::class,
            'blog_ratings' => BlogRatingController::class,
            'carts' => CartController::class,
            'coaches' => CoachController::class,
            'categories' => CategoryController::class,
            'discounts' => DiscountController::class,
            'menus' => MenuController::class,
            'sliders' => SliderController::class,
            'transactions' => TransactionController::class,
            'services' => ServiceController::class,
            'dogs' => DogController::class,
            'courses' => CourseController::class,
            'insurances' => InsuranceController::class,
            'skills' => SkillController::class,
            'bookings' => BookingController::class,
            'breeds' => BreedController::class,
        ]);

        // Products CRUD routes
        Route::resource('products', AdminProductController::class);

        // Permissions & Roles
        Route::resource('permissions', PermissionController::class);
        Route::resource('roles', RoleController::class);

        // Clinics wallet under admin prefix
        Route::prefix('clinics')->group(function () {
            Route::get('wallet', [ClinicController::class, 'clinicwallet'])->name('clinics.wallet');
        });
    });

// ----------------------------------------------
// Guest routes
// ----------------------------------------------
Route::middleware('guest')->group(function () {
    Route::get('/login', [AuthenticatedSessionController::class, 'create'])->name('login');
    Route::post('/login', [AuthenticatedSessionController::class, 'store'])
        ->middleware('throttle:login');
});

Route::middleware('throttle:otp')->prefix('otp')->name('otp.')->group(function () {
    Route::post('/send', [OtpController::class, 'send'])->name('send');
    Route::post('/verify', [OtpController::class, 'verify'])->name('verify');
});

// ----------------------------------------------
// Authenticated routes
// ----------------------------------------------
Route::post('/logout', [AuthenticatedSessionController::class, 'destroy'])->middleware('auth')->name('logout');

Route::middleware('auth')->group(function () {
    // Wallet / checkout-adjacent
    Route::get('/wallet', [AdminWalletController::class, 'index'])
        ->middleware('throttle:payment')
        ->name('wallet.index');

    // Breeder routes
    Route::prefix('breeder')->name('breeder.')->group(function () {
        Route::get('/profile', [BreederController::class, 'profile'])->name('profile');
        Route::get('/dogs-listing', [BreederController::class, 'dogsListing'])->name('dogs.listing');
        Route::get('/dashboard-create', [BreederController::class, 'create'])->name('dashboard-create');
        Route::post('/dashboard/store', [BreederController::class, 'store'])->name('dashboard-store');
        Route::get('/profile/edit', [BreederController::class, 'edit'])->name('profile.edit');
        Route::post('/profile', [BreederController::class, 'update'])->name('profile.update');
    });

    // Coach profile
    Route::prefix('coach')->name('coaches.')->group(function () {
        Route::get('/profile/edit', [CoachController::class, 'editProfile'])->name('profile.edit');
        Route::post('/profile', [CoachController::class, 'updateProfile'])->name('profile.update');
        Route::get('/dashboard', [DashboardController::class, 'index'])->middleware('role:coach')->name('dashboard');
        Route::get('/wallet', [CoachController::class, 'coachWallet'])->name('wallet');
        Route::get('/coach/listing', [CoachController::class, 'listing'])->name('listing');


    });

    // Admin coach resource (under admin prefix + auth only)
    Route::prefix('admin')->name('admin.')->middleware('auth')->group(function () {
        Route::resource('coaches', CoachController::class);
    });

    // Clinic profile
    Route::prefix('clinic')->name('clinic.')->group(function () {
        Route::get('/profile/edit', [ClinicController::class, 'edit'])->name('profile.edit');
        Route::post('/profile', [ClinicController::class, 'update'])->name('profile.update');
        Route::get('/dashboard', [DashboardController::class, 'index'])->middleware('role:clinic')->name('dashboard');
    });

    // User dashboard with role:user
    Route::middleware('role:user')->prefix('user')->name('user.')->group(function () {
        Route::get('/dashboard', [UserDashboardController::class, 'index'])->name('dashboard');
    });

    // Breeder dashboard
    Route::middleware('role:breeder')->group(function () {
        Route::get('/breeder/dashboard', [DashboardController::class, 'index'])->name('breeder.dashboard');
    });
});

// ----------------------------------------------
// Public frontend routes
// ----------------------------------------------
Route::name('frontend.')->group(function () {
    Route::get('/', [HomeController::class, 'index'])->name('home');

    Route::get('/blogsix', [BlogController::class, 'showById'])->name('blog-all.index');
    Route::get('/blogfive', [BlogController::class, 'showByOne'])->name('blog.show.byone');

    Route::prefix('support')->name('support.')->group(function () {
        Route::get('/', [SupportController::class, 'index'])->name('index');
    });

    Route::get('/dog-supply', [DogSupplyController::class, 'index'])->name('dog.supply.index');
    Route::get('/dog-supply/suggest', [DogSupplyController::class, 'suggest'])->name('dog.supply.suggest');
    Route::get('/dog/{id}', [DogSupplyController::class, 'show'])->name('dog-supply.show');

    Route::get('/blog/{id}', [BlogController::class, 'show'])->name('blog.show');
    Route::get('/blog', [BlogController::class, 'index'])->name('blogs.index');

    Route::prefix('course')->group(function () {
        Route::get('/', [OnlineCourseController::class, 'index'])->name('online.courses');
        Route::get('/{slug}', [OnlineCourseController::class, 'showCourse'])->name('courses.show');
        Route::get('/filter', [OnlineCourseController::class, 'filter'])->name('courses.filter');
    });

    Route::get('/shelter', [ShelterController::class, 'index'])->name('shelter.index');

    Route::get('/coaches', [CoachController::class, 'indexFront'])->name('coaches.index');
    Route::get('/coaches/{id}', [CoachController::class, 'showById'])->name('coaches.show');

    Route::get('/clinic-insurance', [ClinicInsuranceController::class, 'index'])->name('clinic.insurance.index');
    Route::get('clinic/insurances/{insurance}', [ClinicInsuranceController::class, 'show'])->name('clinic.insurance.show');

    Route::get('/blogs/{slug}', [BlogController::class, 'showBlog'])->name('blogs.show');
    Route::post('/blog/comment/store', [BlogController::class, 'storeComment'])->name('blogs.store.comment');

    Route::post('/cart/show', [CartController::class, 'show'])
        ->middleware('throttle:payment')
        ->name('cart.show');

    Route::get('/support-web', [AboutController::class, 'support'])->name('support-web.index');
    Route::post('/support-web', [AboutController::class, 'storeSupport'])->name('support-web.store');

    Route::get('/contact', [AboutController::class, 'contact'])->name('contact.index');
    Route::post('/contact', [AboutController::class, 'store'])->name('contact.store');

    Route::get('/clinics', [ClinicController::class, 'index'])->name('clinics.index');
    Route::get('/clinics/{id}', [ClinicController::class, 'showClinicById'])->name('clinics.show');

    Route::get('/breeders', [BreederController::class, 'index'])->name('breeders.index');
    Route::get('/breeders/{id}', [BreederController::class, 'show'])->name('breeders.show');

    Route::get('/news', [BlogController::class, 'news'])->name('news.index');

    Route::get('/products/{id}', [StoreController::class, 'show'])->name('products.show');
    Route::get('/stores', [StoreController::class, 'index'])->name('stores.index');

    Route::get('/about', [AboutController::class, 'index'])->name('about');

    // Services
    Route::get('/services', [FrontendServiceController::class, 'index'])->name('services.index');
    Route::get('/service/{slug}', [FrontendServiceController::class, 'show'])->name('services.show');
    Route::get('/service/category/{slug}', [FrontendServiceController::class, 'showServiceCategory'])->name('services.show.category');
    Route::post('/service/store-comment', [FrontendServiceController::class, 'storeComment'])->name('services.store.comment');
    Route::post('/service/{serviceId}/rate', [FrontendServiceController::class, 'rateService']);
});

// Misc public routes
Route::view('/admin/coming-soon', 'coming-soon')->name('coming.soon');

Route::middleware('throttle:payment')->group(function () {
    Route::get('/order-summary', [FrontendController::class, 'myOrder'])->name('order.summary');
    Route::get('/summary', [FrontendController::class, 'summary']);
});

Route::get('/proposals', [FrontendController::class, 'proposals']);
Route::get('/select-time', [FrontendController::class, 'selectTime']);
Route::get('/select-breed', [FrontendController::class, 'selectBreed']);
Route::get('/select-trainer-type', [FrontendController::class, 'selectTrainerType']);
