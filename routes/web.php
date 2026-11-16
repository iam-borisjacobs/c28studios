<?php

use App\Http\Controllers\Public\HomeController;
use App\Http\Controllers\Public\InquiryController;
use App\Http\Controllers\Public\PostController;
use App\Http\Controllers\Public\ProjectController;
use App\Http\Controllers\Public\SeoController;
use Illuminate\Support\Facades\Route;

Route::get('/', [HomeController::class, 'index'])->name('home');

Route::get('/projects/{project:slug}', [ProjectController::class, 'show'])
    ->where('project', '[a-z0-9\-]+')
    ->name('projects.show');

Route::get('/insights/{post:slug}', [PostController::class, 'show'])
    ->where('post', '[a-z0-9\-]+')
    ->name('posts.show');

Route::post('/inquiries', [InquiryController::class, 'store'])
    ->middleware('throttle:5,1')
    ->name('inquiries.store');

// Technical SEO & AI Discovery Endpoints
Route::get('/sitemap.xml', [SeoController::class, 'sitemap'])->name('seo.sitemap');
Route::get('/robots.txt', [SeoController::class, 'robots'])->name('seo.robots');
Route::get('/llms.txt', [SeoController::class, 'llms'])->name('seo.llms');
Route::get('/llms-full.txt', [SeoController::class, 'llms'])->name('seo.llms.full');
