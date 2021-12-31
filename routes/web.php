<?php

use App\Http\Controllers\SiteController;
use App\Http\Controllers\Subscriptions\SubscriptionController;
use Illuminate\Support\Facades\Route;

Route::post('subscriptions/store', [SubscriptionController::class, 'store'])->name('subscriptions.store');

Route::get('subscriptions/checkout', [SubscriptionController::class, 'checkout'])->name('subscriptions.checkout');

Route::get('subscriptions/premium', [SubscriptionController::class, 'premium'])->name('subscriptions.premium')->middleware((['subscribed']));

Route::get('subscriptions/account', [SubscriptionController::class, 'account'])->name('subscriptions.account')->middleware((['subscribed']));

Route::get('subscriptions/invoice/{invoice}', [SubscriptionController::class, 'downloadInvoice'])->name('subscriptions.invoice.download')->middleware((['subscribed']));

Route::get('subscriptions/invoice/cancel', [SubscriptionController::class, 'cancel'])->name('subscriptions.cancel')->middleware((['subscribed']));

Route::get('subscriptions/invoice/resume', [SubscriptionController::class, 'resume'])->name('subscriptions.resume')->middleware((['subscribed']));

//escolher o plano
Route::get('/assinar/{url}', [SiteController::class, 'createSessionPlan'])->name('choice.plan');



Route::get('/', [SiteController::class, 'index'])->name('site.home');

Route::get('/dashboard', function () {
    return view('dashboard');
})->middleware(['auth'])->name('dashboard');

require __DIR__.'/auth.php';
