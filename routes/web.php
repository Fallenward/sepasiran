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
use App\Http\Controllers\DogOrderController;
use App\Http\Controllers\DogSupplyController;
use App\Http\Controllers\Frontend\BookingController as FrontendBookingController;
use App\Http\Controllers\Frontend\BookingApiController as FrontendBookingApiController;
use App\Http\Controllers\Frontend\OpenSessionController;
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
use App\Http\Controllers\Account\ProfileController;
use App\Http\Controllers\Account\NotificationController;
use App\Http\Controllers\Account\BookingHubController;
use App\Http\Controllers\Account\OwnedDogController;
use App\Http\Controllers\Account\CoachCourseController;
use App\Http\Controllers\Account\ProviderServicePriceController;
use App\Http\Controllers\Account\ProviderServiceController;
use App\Http\Controllers\Account\ProviderAvailabilityController;
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
// Guest auth routes live in routes/auth.php (login, register, password reset).

Route::middleware('throttle:otp')->prefix('otp')->name('otp.')->group(function () {
    Route::post('/send', [OtpController::class, 'send'])->name('send');
    Route::post('/verify', [OtpController::class, 'verify'])->name('verify');
});

// ----------------------------------------------
// Authenticated routes
// ----------------------------------------------
Route::match(['get', 'post'], '/logout', [AuthenticatedSessionController::class, 'destroy'])
    ->middleware('auth')
    ->name('logout');

Route::middleware('auth')->group(function () {
    // Wallet / checkout-adjacent
    Route::get('/wallet', [AdminWalletController::class, 'index'])
        ->middleware('throttle:payment')
        ->name('wallet.index');
    Route::post('/wallet/deposit', [AdminWalletController::class, 'deposit'])
        ->middleware('throttle:payment')
        ->name('wallet.deposit');

    // Breeder routes
    Route::prefix('breeder')->name('breeder.')->group(function () {
        Route::get('/profile', [BreederController::class, 'profile'])->name('profile');
        Route::get('/dogs-listing', [BreederController::class, 'dogsListing'])->name('dogs.listing');
        Route::get('/dashboard-create', [BreederController::class, 'create'])->name('dashboard-create');
        Route::post('/dashboard/store', [BreederController::class, 'store'])->name('dashboard-store');
        Route::get('/profile/edit', [BreederController::class, 'edit'])->name('profile.edit');
        Route::post('/profile', [BreederController::class, 'update'])->name('profile.update');
        Route::get('/reservations', [BookingHubController::class, 'inbox'])->middleware('role:breeder')->name('reservations');
    });

    // Coach profile
    Route::prefix('coach')->name('coaches.')->group(function () {
        Route::get('/profile/edit', [CoachController::class, 'editProfile'])->name('profile.edit');
        Route::post('/profile', [CoachController::class, 'updateProfile'])->name('profile.update');
        Route::get('/dashboard', [DashboardController::class, 'index'])->middleware('role:coach')->name('dashboard');
        Route::get('/wallet', [CoachController::class, 'coachWallet'])->name('wallet');
        Route::get('/listing', [CoachController::class, 'listing'])->name('listing');
        Route::get('/courses', [CoachCourseController::class, 'index'])->middleware('role:coach')->name('courses.index');
        Route::get('/courses/create', [CoachCourseController::class, 'create'])->middleware('role:coach')->name('courses.create');
        Route::post('/courses', [CoachCourseController::class, 'store'])->middleware('role:coach')->name('courses.store');
        Route::get('/courses/{course}/edit', [CoachCourseController::class, 'edit'])->middleware('role:coach')->name('courses.edit');
        Route::put('/courses/{course}', [CoachCourseController::class, 'update'])->middleware('role:coach')->name('courses.update');
        Route::delete('/courses/{course}', [CoachCourseController::class, 'destroy'])->middleware('role:coach')->name('courses.destroy');
        Route::get('/reservations', [BookingHubController::class, 'inbox'])->middleware('role:coach')->name('reservations');


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
        Route::get('/reservations', [BookingHubController::class, 'inbox'])->middleware('role:clinic')->name('reservations');
    });


    // Shared account profile + notifications
    Route::prefix('account')->name('account.')->group(function () {
        Route::get('/profile', [ProfileController::class, 'edit'])->name('profile.edit');
        Route::post('/profile', [ProfileController::class, 'update'])->name('profile.update');
        Route::get('/notifications', [NotificationController::class, 'index'])->name('notifications.index');
        Route::post('/notifications/read-all', [NotificationController::class, 'readAll'])->name('notifications.read-all');
        Route::post('/notifications/{notification}/read', [NotificationController::class, 'read'])->name('notifications.read');
        Route::get('/bookings', [BookingHubController::class, 'index'])->name('bookings.index');
        Route::get('/bookings/{booking}', [BookingHubController::class, 'show'])->name('bookings.show');
        Route::patch('/bookings/{booking}/status', [BookingHubController::class, 'updateStatus'])->name('bookings.status');
        Route::get('/reservations', [BookingHubController::class, 'inbox'])->name('reservations.index');
        Route::get('/dogs', [OwnedDogController::class, 'index'])->name('dogs.index');
        Route::get('/dogs/create', [OwnedDogController::class, 'create'])->name('dogs.create');
        Route::post('/dogs', [OwnedDogController::class, 'store'])->name('dogs.store');
        Route::get('/dogs/{dog}/edit', [OwnedDogController::class, 'edit'])->name('dogs.edit');
        Route::put('/dogs/{dog}', [OwnedDogController::class, 'update'])->name('dogs.update');
        Route::delete('/dogs/{dog}', [OwnedDogController::class, 'destroy'])->name('dogs.destroy');
        Route::get('/service-prices', [ProviderServicePriceController::class, 'edit'])->name('service-prices.edit');
        Route::post('/service-prices', [ProviderServicePriceController::class, 'update'])->name('service-prices.update');
        Route::get('/services', [ProviderServiceController::class, 'index'])->name('services.index');
        Route::post('/services', [ProviderServiceController::class, 'store'])->name('services.store');
        Route::put('/services/{service}', [ProviderServiceController::class, 'update'])->name('services.update');
        Route::delete('/services/{service}', [ProviderServiceController::class, 'destroy'])->name('services.destroy');
        Route::get('/availability', [ProviderAvailabilityController::class, 'edit'])->name('availability.edit');
        Route::post('/availability/settings', [ProviderAvailabilityController::class, 'updateSettings'])->name('availability.settings');
        Route::post('/availability/rules', [ProviderAvailabilityController::class, 'storeRule'])->name('availability.rules.store');
        Route::delete('/availability/rules/{rule}', [ProviderAvailabilityController::class, 'destroyRule'])->name('availability.rules.destroy');
        Route::post('/availability/exceptions', [ProviderAvailabilityController::class, 'storeException'])->name('availability.exceptions.store');
        Route::delete('/availability/exceptions/{exception}', [ProviderAvailabilityController::class, 'destroyException'])->name('availability.exceptions.destroy');
    });

    // User / customer account hub (any authenticated user)
    Route::prefix('user')->name('user.')->group(function () {
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
    Route::post('/dog/{id}/order', [DogOrderController::class, 'store'])
        ->middleware('auth')
        ->name('dog.order.store');
    Route::post('/dog/{id}/order/{order}/pay', [DogOrderController::class, 'pay'])
        ->middleware(['auth', 'throttle:payment'])
        ->name('dog.order.pay');
    Route::match(['get', 'post'], '/dog-order/{order}/callback', [DogOrderController::class, 'callback'])
        ->middleware('throttle:payment')
        ->name('dog.order.callback');

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

    // Public reservation / booking
    Route::get('/booking', [FrontendBookingController::class, 'index'])->name('booking.index');

    // Provider-created open sessions (Flow B)
    Route::get('/open-sessions', [OpenSessionController::class, 'index'])->name('open-sessions.index');
    Route::get('/open-sessions/mine', [OpenSessionController::class, 'mine'])->middleware('auth')->name('open-sessions.mine');
    Route::get('/open-sessions/create', [OpenSessionController::class, 'create'])->middleware('auth')->name('open-sessions.create');
    Route::post('/open-sessions', [OpenSessionController::class, 'store'])->middleware('auth')->name('open-sessions.store');
    Route::get('/open-sessions/{openSession}', [OpenSessionController::class, 'show'])->name('open-sessions.show');
    Route::post('/open-sessions/{openSession}/publish', [OpenSessionController::class, 'publish'])->middleware('auth')->name('open-sessions.publish');
    Route::post('/open-sessions/{openSession}/pause', [OpenSessionController::class, 'pause'])->middleware('auth')->name('open-sessions.pause');
    Route::post('/open-sessions/{openSession}/apply', [OpenSessionController::class, 'apply'])->middleware('auth')->name('open-sessions.apply');
    Route::get('/open-session-applications/{application}', [OpenSessionController::class, 'application'])->middleware('auth')->name('open-sessions.application');
    Route::post('/open-session-applications/{application}/propose', [OpenSessionController::class, 'propose'])->middleware('auth')->name('open-sessions.propose');
    Route::post('/open-session-applications/{application}/offers/{offer}/accept', [OpenSessionController::class, 'acceptOffer'])->middleware('auth')->name('open-sessions.accept-offer');
    Route::post('/booking', [FrontendBookingController::class, 'store'])
        ->middleware('auth')
        ->name('booking.store');
    Route::get('/booking/open', [FrontendBookingController::class, 'openBoard'])->name('booking.open');
    Route::get('/booking/open/{code}', [FrontendBookingController::class, 'openShow'])->name('booking.open.show');
    Route::get('/booking/success/{code}', [FrontendBookingController::class, 'success'])->name('booking.success');
    Route::get('/booking/pay/{code}', [FrontendBookingController::class, 'pay'])
        ->middleware('auth')
        ->name('booking.pay');
    Route::post('/booking/pay/{code}', [FrontendBookingController::class, 'processPay'])
        ->middleware('auth')
        ->name('booking.pay.process');
    // Gateways (and the local driver) may return via GET redirect or POST form.
    Route::match(['get', 'post'], '/booking/pay/{code}/callback', [FrontendBookingController::class, 'payCallback'])
        ->middleware('throttle:payment')
        ->name('booking.pay.callback');
    Route::get('/booking/providers', [FrontendBookingController::class, 'providersShim'])->name('booking.providers');

    Route::prefix('booking/api')->name('booking.api.')->group(function () {
        Route::get('/meta', [FrontendBookingApiController::class, 'meta'])->name('meta');
        Route::get('/cities', [FrontendBookingApiController::class, 'cities'])->name('cities');
        Route::get('/breeds', [FrontendBookingApiController::class, 'breeds'])->name('breeds');
        Route::get('/providers', [FrontendBookingApiController::class, 'providers'])->name('providers');
        Route::get('/provider-services', [FrontendBookingApiController::class, 'providerServices'])->name('provider-services');
        Route::get('/slots', [FrontendBookingApiController::class, 'slots'])->name('slots');

        Route::middleware('auth')->group(function () {
            Route::get('/my-dogs', [FrontendBookingApiController::class, 'myDogs'])->name('my-dogs');
            Route::post('/my-dogs', [FrontendBookingApiController::class, 'storeMyDog'])->name('my-dogs.store');
            Route::post('/quote', [FrontendBookingApiController::class, 'quote'])->name('quote');
            Route::post('/', [FrontendBookingApiController::class, 'store'])->name('store');
            Route::get('/inbox/open', [FrontendBookingApiController::class, 'openInbox'])->name('inbox.open');
            Route::get('/{booking_code}/offers', [FrontendBookingApiController::class, 'listOffers'])->name('offers.index');
            Route::post('/{booking_code}/offers', [FrontendBookingApiController::class, 'storeOffer'])->name('offers.store');
            Route::post('/{booking_code}/offers/{offer_id}/accept', [FrontendBookingApiController::class, 'acceptOffer'])->name('offers.accept');
            Route::post('/{booking_code}/offers/{offer_id}/reject', [FrontendBookingApiController::class, 'rejectOffer'])->name('offers.reject');
            Route::post('/{booking_code}/respond', [FrontendBookingApiController::class, 'respond'])->name('respond');
            Route::get('/{booking_code}/threads', [FrontendBookingApiController::class, 'threads'])->name('threads');
            Route::get('/{booking_code}/messages', [FrontendBookingApiController::class, 'messages'])->name('messages.index');
            Route::post('/{booking_code}/messages', [FrontendBookingApiController::class, 'storeMessage'])->name('messages.store');
            Route::post('/{booking_code}/cancel', [FrontendBookingApiController::class, 'cancel'])->name('cancel');
            Route::get('/{booking_code}', [FrontendBookingApiController::class, 'show'])->name('show');
        });
    });
});

// Misc public routes
Route::view('/admin/coming-soon', 'coming-soon')->name('coming.soon');

Route::middleware('throttle:payment')->group(function () {
    Route::redirect('/order-summary', '/booking')->name('order.summary');
    Route::redirect('/summary', '/booking');
});

Route::redirect('/proposals', '/booking');
Route::redirect('/select-time', '/booking');
Route::redirect('/select-breed', '/booking');
Route::redirect('/select-trainer-type', '/booking');
