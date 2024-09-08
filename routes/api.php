<?php

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\AuthController;
use App\Http\Controllers\BookController;
use App\Http\Controllers\UserController;
use App\Http\Controllers\RatingController;
use App\Http\Controllers\BorrowRecordController;



/*
|--------------------------------------------------------------------------
| API Routes
|--------------------------------------------------------------------------
|
| Here is where you can register API routes for your application. These
| routes are loaded by the RouteServiceProvider and all of them will
| be assigned to the "api" middleware group. Make something great!
|
*/

Route::middleware('auth:api')->get('/user', function (Request $request) {
    return $request->user();
});

Route::post('register', [AuthController::class, 'register']);
Route::post('login', [AuthController::class, 'login']);
Route::post('logout', [AuthController::class, 'logout'])->middleware('auth:api');
Route::get('me', [AuthController::class, 'me'])->middleware('auth:api');


Route::apiResource('/books', BookController::class);
Route::apiResource('/user', UserController::class);
Route::apiResource('/borrows', BorrowRecordController::class);
Route::put('/borrows/{id}/status', [BorrowRecordController::class, 'approveOrRejectBorrow'])->middleware('auth:api');
Route::apiResource('/ratings', RatingController::class);  // إنشاء كافة عمليات الـ CRUD للتقييمات
// Route::apiResource('/books/{book}/ratings', RatingController::class);
Route::get('/books/{bookId}/average-rating', [BookController::class, 'getAverageRating']);


// Route::middleware('auth:api')->group(function () {
//     // User routes
//     Route::post('/borrow', [BorrowRecordController::class, 'store']);
    // Route::get('/user/borrows', [BorrowRecordController::class, 'myBorrows']);
//     Route::get('/user/borrows/{id}', [BorrowRecordController::class, 'showMyBorrow']);
//     Route::post('/borrow/return', [BorrowRecordController::class, 'returnBook']);

//     // Admin routes
//     Route::middleware('admin')->group(function () {
//         Route::get('/admin/borrows', [BorrowRecordController::class, 'index']);
//         Route::get('/admin/borrows/{id}', [BorrowRecordController::class, 'showMyBorrow']);

//         Route::post('/admin/borrows/{id}/accept', [BorrowRecordController::class, 'accept']); // Admin accept route
//     });
// });


