<?php

use App\Http\Controllers\AboutController;
use App\Http\Controllers\Admin\AdminWalletController;
use App\Http\Controllers\Admin\BlogController;
use App\Http\Controllers\Admin\CoachController;
use App\Http\Controllers\Admin\CourseController;
use App\Http\Controllers\Admin\DashboardController;
use App\Http\Controllers\Admin\DogController;
use App\Http\Controllers\Admin\TransactionController;
use App\Http\Controllers\Admin\TypeController;
use App\Http\Controllers\Auth\AuthenticatedSessionController;
use App\Http\Controllers\BlogRatingController;
use App\Http\Controllers\BookingController;
use App\Http\Controllers\BreedController;
use App\Http\Controllers\BreederController;
use App\Http\Controllers\CartController;
use App\Http\Controllers\CategoryController;
use App\Http\Controllers\ClinicController;
use App\Http\Controllers\ClinicInsuranceController;
use App\Http\Controllers\DiscountController;
use App\Http\Controllers\DogOrderController;
use App\Http\Controllers\DogSupplyController;
use App\Http\Controllers\Frontend\BlogController as FrontendBlogController;
use App\Http\Controllers\Frontend\HomeController;
use App\Http\Controllers\Frontend\ServiceController as FrontendServiceController;
use App\Http\Controllers\Frontend\SupportController;
use App\Http\Controllers\InsuranceController;
use App\Http\Controllers\MenuController;
use App\Http\Controllers\OnlineCourseController;
use App\Http\Controllers\ProductController;
use App\Http\Controllers\ServiceController;
use App\Http\Controllers\ShelterController;
use App\Http\Controllers\SkillController;
use App\Http\Controllers\SliderController;
use App\Http\Controllers\StoreController;
use App\Http\Controllers\UserController;
use Illuminate\Foundation\Console\AboutCommand;
use Illuminate\Support\Facades\Route;





Route::middleware(['auth', 'is_admin'])
    ->prefix('admin')
    ->name('admin.')
    ->group(function () {
        // داشبورد
        Route::get('/dashboard', [DashboardController::class, 'index'])->name('dashboard.index');

        Route::get('/dashboard-breeders', [BreederController::class, 'dashboard'])->name('breeders.dashboard');

        // منابع CRUD
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
            'products' => ProductController::class,
            'courses' => CourseController::class,
            'insurances' => InsuranceController::class,
            'skills' => SkillController::class,
            'bookings' => BookingController::class,
            'breeds' => BreedController::class,
        ]);
    });

Route::middleware('guest')->group(function () {
    Route::get('/login',  [AuthenticatedSessionController::class, 'create'])->name('login');
    Route::post('/login', [AuthenticatedSessionController::class, 'store']);
});

Route::post('/logout', [AuthenticatedSessionController::class, 'destroy'])
    ->middleware('auth')
    ->name('logout');
Route::view('/admin/coming-soon', 'coming-soon')->name('coming.soon');

Route::name('frontend.')->group(function () {
    Route::get('/', [HomeController::class, 'index'])->name('home');

    Route::get('/blogsix', [BlogController::class, 'showById'])->name('blog-all.index');
        Route::get('/blogfive', [BlogController::class, 'kir'])->name('blog.show.byone');



    Route::prefix('support')->name('support.')->group(function () {
        Route::get('/', [SupportController::class, 'index'])->name('index');
    });

    Route::get('/dog-supply', [DogSupplyController::class, 'index'])->name('dog.supply.index');
    Route::get('/dog/{id}', [DogSupplyController::class, 'show'])->name('dog-supply.show');

    Route::get('/blog/{id}', [BlogController::class, 'show'])->name('blog.show');
    Route::get('/blog', [BlogController::class, 'index'])->name('blogs.index');


    Route::prefix('course')->group(function () {
        Route::get('/', [OnlineCourseController::class, 'index'])->name('online.courses');
        Route::get('/{slug}', [OnlineCourseController::class, 'showCourse'])->name('courses.show');
    });

    Route::get('/shelter', [ShelterController::class, 'index'])->name('shelter.index');

    Route::get('/coaches', [CoachController::class, 'indexFront'])->name('coaches.index');
    Route::get('/coaches/{id}', [CoachController::class, 'showById'])->name('coaches.show');


    Route::get('/clinic-insurance', [ClinicInsuranceController::class, 'index'])->name('clinic.insurance.index');
    // web.php
    Route::get('clinic/insurances/{insurance}', [ClinicInsuranceController::class, 'show'])->name('clinic.insurance.show');


    Route::get('/blogs/{slug}', [BlogController::class, 'showBlog'])->name('blogs.show');
    Route::post('/blog/comment/store', [BlogController::class, 'storeComment'])->name('blogs.store.comment');

    Route::post('/cart/show', [CartController::class, 'show'])->name('card.show');


    Route::get('/course/filter',[OnlineCourseController::class, 'filter'])->name('courses.filter');

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

Route::middleware(['auth'])->group(function () {
    Route::get('/breeder/profile', [BreederController::class, 'profile'])->name('profile');
    // Route::post('/profile', [BreederController::class, 'updateProfile']);
});
Route::get('/breeder/dogs-listing', [BreederController::class, 'dogsListing'])->name('breeder.dogs.listing');
Route::get('/breeder/dashboard-create', [BreederController::class, 'create'])->name('breeders.dashboard-create');
Route::post('/breeder/dashboard/store', [BreederController::class, 'store'])->name('breeders.dashboard-store');
Route::get('/breederwallet/', [BreederController::class, 'wallet'])->name('breeders.wallet');


Route::middleware(['auth'])->group(function () {
    Route::get('/coach/profile', [CoachController::class, 'editProfile'])->name('coach.profile.edit');
    Route::post('/coach/profile', [CoachController::class, 'updateProfile'])->name('coach.profile.update');
});

Route::prefix('admin')->name('admin.')->middleware(['auth'])->group(function () {
    Route::resource('coaches', CoachController::class);
});

Route::middleware(['auth'])->group(function () {
    Route::get('/coach/{coachId?}/products', [CoachController::class, 'listing'])->name('coaches.listing');
    Route::get('/coaches/create', [CoachController::class, 'createList'])->name('coaches.create');
    Route::post('/coaches/store', [CoachController::class, 'storeList'])->name('coaches.store');
    Route::delete('/coaches/{id}', [CoachController::class, 'destroyList'])->name('coaches.destroy');
    Route::get('/coaches/wallet', [CoachController::class, 'coachWallet' ])->name('coaches.wallet');
    Route::get('coach/profile', [CoachController::class, 'editProfile'])->name('coach.profile.edit');

// Handle form submission
Route::post('/profile', [CoachController::class, 'updateProfile'])->name('profile');
});


Route::prefix('admin/clinics')->middleware(['auth'])->group(function () {
    Route::get('wallet', [ClinicController::class, 'clinicwallet'])->name('admin.clinics.wallet');
});



Route::middleware(['auth'])->group(function () {
    Route::get('breeder/profile', [BreederController::class, 'edit'])->name('breeder.profile.edit');
    Route::post('breeder/profile', [BreederController::class, 'update'])->name('breeder.profile');
});




Route::middleware(['auth'])->group(function () {

    Route::get('/clinic/dashboard', [DashboardController::class, 'index'])
        ->middleware('role:clinic')
        ->name('clinic.dashboard');

    Route::get('/coach/dashboard', [DashboardController::class, 'index'])
        ->middleware('role:coach')
        ->name('coach.dashboard');

    Route::get('/breeder/dashboard', [DashboardController::class, 'index'])
        ->middleware('role:breeder')
        ->name('breeder.dashboard');

    Route::get('/wallet', [AdminWalletController::class, 'index'])
        ->name('wallet.index');
});


Route::middleware(['auth'])->group(function () {
    Route::get('clinic/profile', [ClinicController::class, 'edit'])->name('clinic.profile.edit');
    Route::post('clinic/profile', [ClinicController::class, 'update'])->name('clinic.profile.update');
});


