<?php

use App\Http\Controllers\HomeController;
use App\Http\Controllers\Links\CreateLinkController;
use App\Http\Controllers\Links\LinksIndexController;
use App\Http\Controllers\NewsletterController;
use App\Http\Controllers\NewsletterSubscriptionController;
use App\Http\Controllers\OriginalsController;
use App\Http\Controllers\PaymentsController;
use App\Http\Controllers\PostController;
use App\Http\Controllers\SpeakingController;
use Illuminate\Support\Facades\Route;
use Spatie\Honeypot\ProtectAgainstSpam;

Route::feeds();

Route::redirect('nova', '/nova/resources/post');

Route::get('/', HomeController::class);
Route::get('originals', OriginalsController::class);
Route::get('speaking', SpeakingController::class);

Route::view('about', 'front.about.index');
Route::view('advertising', 'front.advertising.index');
Route::view('search', 'front.search.index');
Route::view('mailcoach-contest', 'front.contest.mailcoach');
Route::view('ohdear-contest', 'front.contest.ohdear');
Route::view('laravel-package-training-contest', 'front.contest.laravel-package-training');

Route::middleware('doNotCacheResponse')->group(function () {
    Route::get('newsletter', NewsletterController::class);
    Route::post('subscribe', [NewsletterSubscriptionController::class, 'subscribe'])->middleware(ProtectAgainstSpam::class)->name('newsletter.subscribe');
    Route::get('confirm', [NewsletterSubscriptionController::class, 'confirm']);
    Route::get('confirmed', [NewsletterSubscriptionController::class, 'confirmed']);

    Route::get('payments', [PaymentsController::class, 'index']);
    Route::post('payments/set-amount', [PaymentsController::class, 'setAmount']);
    Route::post('payments', [PaymentsController::class, 'handlePayment']);
});

Route::view('newsletter/liked-it', 'front.newsletter.like')->name('newsletter.like');
Route::view('newsletter/could-be-improved', 'front.newsletter.dislike')->name('newsletter.dislike');

Route::prefix('links')->group(function () {
    Route::get('/', LinksIndexController::class)->name('links');
    Route::middleware(['auth', 'doNotCacheResponse'])->group(function () {
        Route::get('create', [CreateLinkController::class, 'create'])->name('links.create');
        Route::post('create', [CreateLinkController::class, 'store']);
        Route::view('thanks', 'front.links.thanks')->name('links.thanks');
    });
});

Route::redirect('me', '/about');
Route::redirect('php-version', '/1598-how-to-check-which-version-of-php-you-are-running');

Route::webhooks('webhook-webmentions', 'webmentions');

Route::redirect('/uses', '/1485-my-current-setup-2019-edition');

Route::view('legal', 'front.legal.index');



Route::get('{postSlug}', PostController::class);
