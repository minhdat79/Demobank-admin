<?php

use Illuminate\Support\Facades\Route;
use Illuminate\Support\Facades\Storage;

use App\Http\Controllers\Admin\DashboardController;
use App\Http\Controllers\Admin\UserController as AdminUserController;
use App\Http\Controllers\Admin\PostAdminController;
use App\Http\Controllers\Admin\UploadController;
use App\Http\Controllers\Admin\JobController as AdminJobController;
use App\Http\Controllers\Admin\JobApplicationController;

use App\Http\Controllers\BlogController;
use App\Http\Controllers\Front\JobPageController;
use App\Http\Controllers\Front\JobApplyController;

Route::get('/', fn () => redirect()->route('front.jobs.index'))->name('home');

Route::view('/gioi-thieu', 'about')->name('about');
Route::view('/ekyc', 'ekyc')->name('ekyc');
Route::view('/khuyen-mai/sinh-nhat', 'promo.birthday')->name('promo.birthday');

Route::get('/blog', [BlogController::class, 'index'])->name('blog.index');
Route::get('/blog/{slug}', [BlogController::class, 'show'])->name('blog.show');

Route::prefix('jobs')->name('front.jobs.')->group(function () {
    Route::get('/', [JobPageController::class, 'index'])->name('index');
   
    Route::get('/{job:slug}/apply',  [JobApplyController::class, 'create'])->name('apply');
    Route::post('/{job:slug}/apply', [JobApplyController::class, 'store'])->name('apply.store');

    Route::get('/{job:slug}', [JobPageController::class, 'show'])->name('show');
});

Route::get('/media/{path}', function (string $path) {
    $path = str_replace('\\', '/', $path);
    abort_unless(Storage::disk('public')->exists($path), 404);
    return Storage::disk('public')->response($path);
})->where('path', '.*')->name('media');     

Route::prefix('admin')->name('admin.')->middleware(['auth', 'role:admin'])->group(function () {
    Route::get('/', [DashboardController::class, 'index'])->name('dashboard');
    Route::resource('users', AdminUserController::class)->only([
        'index','create','store','edit','update','destroy'
    ]);
       
    Route::resource('posts', PostAdminController::class);
});

Route::middleware(['auth'])->prefix('admin')->name('admin.')->group(function () {
    Route::resource('jobs', AdminJobController::class)->except(['show']);
    Route::post('/uploads/ckeditor', [UploadController::class, 'ckeditor'])->name('uploads.ckeditor');

    Route::get('/applications', [JobApplicationController::class, 'index'])->name('applications.index');
    Route::patch('/applications/{id}/status', [JobApplicationController::class, 'updateStatus'])->name('applications.status');
});

require __DIR__ . '/auth.php';
require __DIR__ . '/profile.php';

