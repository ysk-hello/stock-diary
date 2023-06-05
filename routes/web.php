<?php

use Illuminate\Support\Facades\Route;
use Illuminate\Http\Request;

/*
|--------------------------------------------------------------------------
| Web Routes
|--------------------------------------------------------------------------
|
| Here is where you can register web routes for your application. These
| routes are loaded by the RouteServiceProvider and all of them will
| be assigned to the "web" middleware group. Make something great!
|
*/

Route::get('/', function () {
    return view('welcome');
});

Route::get('/terms', function () {
    return view('terms');
})->name('terms');

Route::get('/policy', function () {
    return view('policy');
})->name('policy');

Route::get('/contact', function () {
    return view('contact');
})->name('contact');

Route::post('/contact/mail', function(Request $request) {
    Mail::send(new App\Mail\ContactMail($request));
});

Auth::routes();

Route::get('/diary', [App\Http\Controllers\DiaryController::class, 'index'])->name('diary');
Route::get('/diary/get', [App\Http\Controllers\DiaryController::class, 'getDiary'])->name('diary.get');
Route::get('/diary/gets', [App\Http\Controllers\DiaryController::class, 'getDiaries'])->name('diary.gets');
Route::post('/diary/save', [App\Http\Controllers\DiaryController::class, 'saveDiary'])->name('diary.save');
Route::get('/diary/delete', [App\Http\Controllers\DiaryController::class, 'deleteDiary'])->name('diary.delete');

Route::group(['middleware' => ['auth', 'admin']], function(){
    Route::get('/upload', function(){
        return view('upload');
    })->name('upload');
});

Route::get('/company', [App\Http\Controllers\CompanyController::class, 'index'])->name('company');
Route::post('/company/upload', [App\Http\Controllers\CompanyController::class, 'uploadFile']);
Route::post('/company/gets', [App\Http\Controllers\CompanyController::class, 'getCompanies']);
Route::get('/company/search', [App\Http\Controllers\CompanyController::class, 'searchCompanies'])->name('company.search');
Route::get('/company/getname', [App\Http\Controllers\CompanyController::class, 'getName'])->name('company.getname');

Route::get('/favorite', [App\Http\Controllers\FavoriteController::class, 'index'])->name('favorite');
Route::get('/favorite/gets', [App\Http\Controllers\FavoriteController::class, 'getFavorites'])->name('favorite.gets');
Route::get('/favorite/check', [App\Http\Controllers\FavoriteController::class, 'checkState'])->name('favorite.check');
Route::get('/favorite/toggle', [App\Http\Controllers\FavoriteController::class, 'toggleState'])->name('favorite.toggle');

Route::get('/companydiary', [App\Http\Controllers\CompanyDiaryController::class, 'index'])->name('companydiary');
Route::get('/companydiary/get', [App\Http\Controllers\CompanyDiaryController::class, 'getCompanyDiary'])->name('companydiary.get');
Route::get('/companydiary/gets', [App\Http\Controllers\CompanyDiaryController::class, 'getCompanyDiaries'])->name('companydiary.gets');
Route::post('/companydiary/save', [App\Http\Controllers\CompanyDiaryController::class, 'saveCompanyDiary'])->name('companydiary.save');
Route::get('/companydiary/delete', [App\Http\Controllers\CompanyDiaryController::class, 'deleteCompanyDiary'])->name('companydiary.delete');

Route::get('/search', [App\Http\Controllers\SearchDiaryController::class, 'index'])->name('search');
Route::get('/search/diary', [App\Http\Controllers\SearchDiaryController::class, 'searchDiaries'])->name('search.diary');
Route::get('/search/companydiary', [App\Http\Controllers\SearchDiaryController::class, 'searchCompanyDiaries'])->name('search.companydiary');

Route::get('/output', [App\Http\Controllers\OutputDiaryController::class, 'index'])->name('output');
Route::get('/output/diary', [App\Http\Controllers\OutputDiaryController::class, 'outputDiaries'])->name('output.diary');
Route::get('/output/companydiary', [App\Http\Controllers\OutputDiaryController::class, 'outputCompanyDiaries'])->name('output.companydiary');