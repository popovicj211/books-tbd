<?php


use Illuminate\Support\Facades\Route;
use App\Http\Controllers\BookController;
use App\Http\Controllers\Admin\BooksController as BookControllerAdmin;
use App\Http\Controllers\Admin\UserController;
use App\Http\Controllers\AuthController;
/*
|--------------------------------------------------------------------------
| API Routes
|--------------------------------------------------------------------------
|
| Here is where you can register API routes for your application. These
| routes are loaded by the RouteServiceProvider within a group which
| is assigned the "api" middleware group. Enjoy building your API!
|
*/
/*
Route::middleware('auth:sanctum')->get('/user', function (Request $request) {
    return $request->user();
});*/



Route::group([

    'middleware' => 'api',
    'prefix' => 'auth',
    'except' => 'login'
], function () {

    Route::post('login', [AuthController::class, 'login'])->name('login');
    Route::post('logout',  [AuthController::class, 'logout'])->name('logout');
    Route::post('refresh',  [AuthController::class, 'refresh']);
    Route::post('me',  [AuthController::class, 'me'])->name('me');
   // Route::get('activate/{tokenemail}',  [AuthController::class, 'verify'])->name('verification');;
});

Route::group([
    'middleware' => ['api','jwt.verify','checkAdmin'],
    'prefix' => 'auth'
], function (){
Route::resource('users', UserController::class);
Route::resource('books', BookControllerAdmin::class);

});


Route::post('/register', [AuthController::class, 'register']);
Route::get('/books', [BookController::class, 'getAllBooks']);





