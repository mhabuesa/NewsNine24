<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\HomeController;
use App\Http\Controllers\NewsController;
use App\Http\Controllers\PostController;
use App\Http\Controllers\UserController;
use App\Http\Controllers\ProfileController;
use App\Http\Controllers\CategoryController;
use App\Http\Controllers\AdvertiseController;
use App\Http\Controllers\FrontendController;
use App\Http\Controllers\SubcategoryController;

Route::controller(FrontendController::class)->group(function () {
    Route::get('/', 'index')->name('index');
});


Route::middleware('auth')->group(function () {
    // Dashboard Route
    Route::get('/dashboard', [HomeController::class, 'dashboard'])->name('dashboard');

    // Profile Routes
    Route::controller(ProfileController::class)->name('profile.')->prefix('profile')->group(function () {
        Route::get('/', 'index')->name('index');
        Route::post('/update', 'update')->name('update');
        Route::post('/password', 'profile_password')->name('password');
    });

    // Category Routes
    Route::controller(CategoryController::class)->name('category.')->prefix('category')->group(function () {
        Route::get('/', 'index')->name('index');
        Route::post('/store', 'store')->name('store');
        Route::delete('/destroy/{id}', 'destroy')->name('destroy');
        Route::post('/status/{id}', 'updateStatus')->name('status.update');
        Route::post('/update', 'updateAjax')->name('update');
    });

    // Subcategory Routes
    Route::controller(SubcategoryController::class)->name('subcategory.')->prefix('subcategory')->group(function () {
        Route::get('/', 'index')->name('index');
        Route::post('/store', 'store')->name('store');
        Route::delete('/destroy/{id}', 'destroy')->name('destroy');
        Route::post('/status/{id}', 'updateStatus')->name('status.update');
        Route::post('/update', 'updateAjax')->name('update');
    });

    // Advertise Routes
    Route::controller(AdvertiseController::class)->name('advertise.')->prefix('advertise')->group(function () {
        Route::get('/', 'index')->name('index');
        Route::get('/store', 'store')->name('store');
    });



    // Extra Routes of resource controllers can be defined here
    // News Routes
    Route::controller(NewsController::class)->name('news.')->prefix('news')->group(function () {
        Route::get('/get-subcategories/{category}', 'getSubcategories');
        Route::get('/trash', 'trash')->name('trash');
        Route::get('/restore/{id}', 'restore')->name('restore');
        Route::get('/permanentlydelete/{id}', 'permanentlydelete')->name('permanentlydelete');
        Route::get('/getList/ajax', 'getList')->name('getList.ajax');
        Route::get('/featured', 'featured')->name('featured');
        Route::get('/featuredUpdate/{id}', 'featuredUpdate')->name('featuredUpdate');
        Route::get('/hotUpdate/{id}', 'hotUpdate')->name('hotUpdate');
    });


    // Resource Routes
    Route::resources([
        'news' => NewsController::class,
        'users' => UserController::class,
    ]);
});

require __DIR__ . '/auth.php';
