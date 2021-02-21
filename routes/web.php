<?php

use Illuminate\Support\Facades\Route;
use App\Http\Livewire\Expense\{
    ExpenseCreate,
    ExpenseEdit,
    ExpenseList
};
use App\Http\Livewire\Plan\{
    PlanCreate,
    PlanList
};
use Illuminate\Support\Facades\{
    File,
    Storage
};

/*
|--------------------------------------------------------------------------
| Web Routes
|--------------------------------------------------------------------------
|
| Here is where you can register web routes for your application. These
| routes are loaded by the RouteServiceProvider within a group which
| contains the "web" middleware group. Now create something great!
|
*/

Route::get('/', function () {
    return view('welcome');
});

Route::middleware(['auth:sanctum', 'verified'])->get('/dashboard', function () {
    return view('dashboard');
})->name('dashboard');

Route::middleware(['auth:sanctum', 'verified', 'check.usersubscription'])->group(function (){

    Route::prefix('expenses')->name('expenses.')->group(function (){

        Route::get('/', ExpenseList::class)->name('index');
        Route::get('/create', ExpenseCreate::class)
                    ->middleware('check.amountexpense')->name('create');

        Route::get('/edit/{expense}', ExpenseEdit::class)->name('edit');

        Route::get('/{expense}/photo', function ($expense){
            $expense = auth()->user()->expenses()->findOrFail($expense);

            // Get image
            if(!Storage::disk('public')->get($expense->photo))
                return abort(404, 'Image not found!');

            $image = Storage::disk('public')->get($expense->photo);

            $mimeType = File::mimeType(storage_path('app/public/' . $expense->photo));

            // Return with file image
            return response($image)->header('Content-Type', $mimeType);
        })->name('photo');

    });

    Route::prefix('plans')->name('plans.')->group(function (){

        Route::get('/', PlanList::class)->name('index');
        Route::get('/create', PlanCreate::class)->name('create');

    });

});

Route::prefix('subscription')->group(function (){

    Route::get('/choosed/{plan:slug}', function ($plan){
        session()->put('choosed_plan', $plan);

        return redirect()->route('plan.subscription', $plan);
    })->name('choosed.plan');

    Route::get('/{plan:slug}', \App\Http\Livewire\Payment\CreditCard::class)
            ->name('plan.subscription')
            ->middleware(['auth:sanctum']);

});

Route::get('/notification', function (){

//    $code = '4DD45469959581DEE4EF2FB592CF7D0E';
    $code = '4F60E8199B0B9B0B55766466FF908D87D908';

//    return (new App\Services\PagSeguro\Subscription\SubscriptionReaderService())->getSubscriptionByCode($code);
    return (new App\Services\PagSeguro\Subscription\SubscriptionReaderService())->getSubscriptionNotificationCode($code);

});

Route::get('/clear-session', fn() => session()->flush());
