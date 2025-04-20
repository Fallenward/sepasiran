<?php

use App\Http\Controllers\BlogController;
use App\Http\Controllers\BlogRatingController;
use App\Http\Controllers\BookingController;
use App\Http\Controllers\BreedController;
use App\Http\Controllers\CartController;
use App\Http\Controllers\CategoryController;
use App\Http\Controllers\ChatController;
use App\Http\Controllers\CommentAnswerController;
use App\Http\Controllers\CommentController;
use App\Http\Controllers\CourseController;
use App\Http\Controllers\DiscountController;
use App\Http\Controllers\DogController;
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
use App\Http\Controllers\TransactionController;
use App\Http\Controllers\UserController;
use App\Http\Controllers\Frontend\HomeController;
use Illuminate\Support\Facades\Route;

Route::get('/', function () {
    return view('welcome');
});
Route::group(['prefix' => 'admin'], function () {
    Route::resources([
        'users' => UserController::class,
        'blogs' => BlogController::class,
        'blog_ratings' => BlogRatingController::class,
        'carts' => CartController::class,
        'categories' => CategoryController::class,
        'chats' => ChatController::class,
        'comments' => CommentController::class,
        'discounts' => DiscountController::class,
        'menus' => MenuController::class,
        'notifications' => NotificationController::class,
        'settings' => SettingController::class,
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

    Route::prefix('support')->name('support.')->group(function () {
        Route::get('/', [SupportController::class, 'index'])->name('index');
    });

    Route::get('/dog-supply', [DogSupplyController::class, 'index'])->name('dog.supply.index');

    Route::get('/online-courses', [OnlineCourseController::class, 'index'])->name('online.courses');

    Route::get('/shelter', [ShelterController::class, 'index'])->name('shelter.index');

});
