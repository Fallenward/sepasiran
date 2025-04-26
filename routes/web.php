<?php

use App\Http\Controllers\Admin\DashboardController;
use App\Http\Controllers\Admin\TypeController;
use App\Http\Controllers\Auth\AuthenticatedSessionController;
use App\Http\Controllers\BlogController;
use App\Http\Controllers\BlogRatingController;
use App\Http\Controllers\BookingController;
use App\Http\Controllers\BreedController;
use App\Http\Controllers\BreederController;
use App\Http\Controllers\CartController;
use App\Http\Controllers\CategoryController;
use App\Http\Controllers\ChatController;
use App\Http\Controllers\ClinicController;
use App\Http\Controllers\ClinicInsuranceController;
use App\Http\Controllers\CoachSelectionController;
use App\Http\Controllers\CommentAnswerController;
use App\Http\Controllers\CommentController;
use App\Http\Controllers\CourseController;
use App\Http\Controllers\DiscountController;
use App\Http\Controllers\DogController;
use App\Http\Controllers\DogOrderController;
use App\Http\Controllers\DogSupplyController;
use App\Http\Controllers\Frontend\SupportController;
use App\Http\Controllers\InsuranceController;
use App\Http\Controllers\MenuController;
use App\Http\Controllers\NotificationController;
use App\Http\Controllers\OnlineCourseController;
use App\Http\Controllers\ProductController;
use App\Http\Controllers\ServiceController;
use App\Http\Controllers\SettingController;
use App\Http\Controllers\ShelterController;
use App\Http\Controllers\SkillController;
use App\Http\Controllers\SliderController;
use App\Http\Controllers\StoreController;
use App\Http\Controllers\TransactionController;
use App\Http\Controllers\UserController;
use App\Http\Controllers\Frontend\HomeController;
use Illuminate\Support\Facades\Route;

Route::get('/', function () {
    return view('welcome');
});

Route::middleware(['auth'])->group(function () {
    Route::prefix('admin')->name('admin.')->group(function () {
        Route::get('/dashboard', [DashboardController::class, 'index'])->name('dashboard.index');
    });
});

Route::middleware('guest')->group(function () {
    Route::get('/login', [AuthenticatedSessionController::class, 'create'])->name('login');
    Route::post('/new-login', [AuthenticatedSessionController::class, 'store'])->name('new-login');
});


Route::group(['prefix' => 'admin', 'middleware' => ['auth', 'is_admin']], function () {
    Route::resources([
        'users' => UserController::class,
        'blogs' => BlogController::class,
        'blog_ratings' => BlogRatingController::class,
        'carts' => CartController::class,
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

Route::name('frontend.')->group(function () {
    Route::get('/', [HomeController::class, 'index'])->name('home');

    Route::get('/blog', [BlogController::class, 'index'])->name('blog.index');
    Route::get('/blog/{id}', [BlogController::class, 'show'])->name('blog.show');

    Route::prefix('support')->name('support.')->group(function () {
        Route::get('/', [SupportController::class, 'index'])->name('index');
    });

    Route::get('/dog-supply', [DogSupplyController::class, 'index'])->name('dog.supply.index');

    Route::get('/online-courses', [OnlineCourseController::class, 'index'])->name('online.courses');

    Route::get('/shelter', [ShelterController::class, 'index'])->name('shelter.index');

    Route::get('/dog-order', [DogOrderController::class, 'index'])->name('dog.order');
    Route::post('/dog-order', [DogOrderController::class, 'store'])->name('dog.order.store');

    Route::get('/coach-selection', [CoachSelectionController::class, 'index'])->name('coach-selection.index');

    Route::get('/clinic-insurance', [ClinicInsuranceController::class, 'index'])->name('clinic.insurance.index');
    Route::get('/clinics', [ClinicController::class, 'index'])->name('clinics.index');

    Route::get('/breeders', [BreederController::class, 'index'])->name('breeders.index');

    Route::get('/stores', [StoreController::class, 'index'])->name('stores.index');
});
