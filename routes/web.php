<?php

use App\Http\Controllers\Public\HomeController;
use App\Http\Controllers\Public\PostController;
use App\Http\Controllers\Public\ProjectController;
use Illuminate\Support\Facades\Route;

Route::get('/', [HomeController::class, 'index'])->name('home');
Route::get('/projects/{project:slug}', [ProjectController::class, 'show'])->name('projects.show');
Route::get('/insights/{post:slug}', [PostController::class, 'show'])->name('posts.show');
