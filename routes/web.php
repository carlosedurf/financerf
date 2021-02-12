<?php

use Illuminate\Support\Facades\Route;
use App\Http\Livewire\Expense\ExpenseCreate;
use App\Http\Livewire\Expense\ExpenseEdit;
use App\Http\Livewire\Expense\ExpenseList;
use \Illuminate\Support\Facades\{
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

Route::middleware(['auth:sanctum', 'verified'])->group(function (){

    Route::prefix('expenses')->name('expenses.')->group(function (){

        Route::get('/', ExpenseList::class)->name('index');
        Route::get('/create', ExpenseCreate::class)->name('create');
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

});
