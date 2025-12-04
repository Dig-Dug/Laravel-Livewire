<?php

use Illuminate\Support\Facades\Route;
use App\Http\Livewire\Counter;
use App\Http\Livewire\PostsList;
use App\Http\Controllers\PostController;

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
Route::get('/counter', Counter::class);
/*Route::get('/posts', PostsList::class);
Route::get('post-list', PostsList::class);
Route::delete('/posts/{post}', [PostController::class, 'destroy'])->name('posts.destroy');*/
Route::get('posts', PostsList::class)->name('posts');