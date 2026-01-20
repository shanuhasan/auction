<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\ProfileController;
use App\Http\Controllers\Admin\TeamController;
use App\Http\Controllers\Admin\UserController;
use App\Http\Controllers\Admin\AdminController;
use App\Http\Controllers\Admin\PlayerController;
use App\Http\Controllers\Admin\AuctionController;
use App\Http\Controllers\Admin\UploadImageController;
use App\Http\Controllers\Admin\AuctionPlayerController;

Route::get('/', function () {
    return view('welcome');
});

Route::get('/dashboard', function () {
    return view('dashboard');
})->middleware(['auth', 'verified'])->name('dashboard');

Route::middleware('auth')->group(function () {
    Route::get('/profile', [ProfileController::class, 'edit'])->name('profile.edit');
    Route::patch('/profile', [ProfileController::class, 'update'])->name('profile.update');
    Route::delete('/profile', [ProfileController::class, 'destroy'])->name('profile.destroy');
});

Route::middleware(['auth', 'role:superadmin'])->group(function () {
    Route::group(['prefix' => 'admin', 'as' => 'admin.'], function () {
        Route::get('/dashboard', [AdminController::class, 'dashboard'])->name('dashboard');

        //users
        Route::get('/users', [UserController::class, 'index'])->name('user.index');
        Route::get('/user/create', [UserController::class, 'create'])->name('user.create');
        Route::post('/user/store', [UserController::class, 'store'])->name('user.store');
        Route::get('/user/{guid}/edit', [UserController::class, 'edit'])->name('user.edit');
        Route::put('/user/{guid}', [UserController::class, 'update'])->name('user.update');
        Route::get('/user/{guid}', [UserController::class, 'destroy'])->name('user.delete');

        //auctions
        Route::get('/auction', [AuctionController::class, 'index'])->name('auction.index');
        Route::get('/auction/create', [AuctionController::class, 'create'])->name('auction.create');
        Route::post('/auction/store', [AuctionController::class, 'store'])->name('auction.store');
        Route::get('/auction/{guid}/edit', [AuctionController::class, 'edit'])->name('auction.edit');
        Route::put('/auction/{guid}', [AuctionController::class, 'update'])->name('auction.update');
        Route::get('/auction/{guid}', [AuctionController::class, 'destroy'])->name('auction.delete');

        //teams
        Route::get('/team', [TeamController::class, 'index'])->name('team.index');
        Route::get('/team/create', [TeamController::class, 'create'])->name('team.create');
        Route::post('/team/store', [TeamController::class, 'store'])->name('team.store');
        Route::get('/team/{guid}/edit', [TeamController::class, 'edit'])->name('team.edit');
        Route::put('/team/{guid}', [TeamController::class, 'update'])->name('team.update');
        Route::get('/team/{guid}', [TeamController::class, 'destroy'])->name('team.delete');

        //players
        Route::get('/player', [PlayerController::class, 'index'])->name('player.index');
        Route::get('/player/create', [PlayerController::class, 'create'])->name('player.create');
        Route::post('/player/store', [PlayerController::class, 'store'])->name('player.store');
        Route::get('/player/{guid}/edit', [PlayerController::class, 'edit'])->name('player.edit');
        Route::put('/player/{guid}', [PlayerController::class, 'update'])->name('player.update');
        Route::get('/player/{guid}', [PlayerController::class, 'destroy'])->name('player.delete');

        //auction players
        Route::get('/auction-player', [AuctionPlayerController::class, 'index'])->name('auction-player.index');
        Route::get('/auction-player/{guid}/edit', [AuctionPlayerController::class, 'edit'])->name('auction-player.edit');
        Route::post('/auction-player/store', [AuctionPlayerController::class, 'store'])->name('auction-player.store');
        Route::get('/start-auction-player/{guid}/start', [AuctionPlayerController::class, 'start'])->name('auction-player.start');
        Route::put('/auction-player/{guid}', [AuctionPlayerController::class, 'update'])->name('auction-player.update');

        Route::post('/upload-image', [UploadImageController::class, 'create'])->name('media.create');
    });
    

    
});

// Route::middleware(['auth', 'role:admin'])->group(function () {
//     Route::get('/admin/dashboard', [AdminController::class, 'dashboard'])->name('admin.dashboard');
// });

// Route::middleware(['auth', 'role:user'])->group(function () {
//     Route::get('/dashboard', [UserController::class, 'dashboard'])->name('user.dashboard');
// });

require __DIR__.'/auth.php';
