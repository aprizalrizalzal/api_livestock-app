<?php

use App\Http\Controllers\Auth\AuthController;
use App\Http\Controllers\Livestock\LivestockController;
use App\Http\Controllers\Livestock\LivestockPhotoController;
use App\Http\Controllers\Livestock\LivestockSpeciesController;
use App\Http\Controllers\Livestock\LivestockTypeController;
use App\Http\Controllers\Payment\PaymentController;
use App\Http\Controllers\Profile\ProfileController;
use App\Http\Controllers\Transaction\TransactionController;
use App\Http\Controllers\User\UserController;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;

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

Route::middleware('auth:sanctum')->get('/user', function (Request $request) {
    return $request->user();
});

Route::get('livestocks-anonymous', [LivestockController::class, 'getLivestocksAnonymous']);

Route::post('register', [AuthController::class, 'register']);
Route::get('roles', [UserController::class, 'getRoles']);
Route::get('permissions', [UserController::class, 'getPermissions']);

Route::post('login', [AuthController::class, 'login']);

Route::middleware(['auth:sanctum'])->group(function () {
    // Users
    Route::get('users', [UserController::class, 'getUsers']);
    Route::get('user/{id}', [UserController::class, 'getUserById']);
    Route::delete('user/{id}', [UserController::class, 'deleteUserById']);

    // Profiles
    Route::get('profile', [ProfileController::class, 'getProfile']);
    Route::post('profile', [ProfileController::class, 'postProfile']);
    Route::post('profile-photo', [ProfileController::class, 'postProfilePhoto']);
    Route::put('profile-photo', [ProfileController::class, 'putProfilePhoto']);
    Route::put('profile', [ProfileController::class, 'putProfile']);
    Route::delete('profile', [ProfileController::class, 'deleteProfile']);

    // Livestocks
    Route::get('livestocks', [LivestockController::class, 'getLivestocks']);
    Route::get('livestocks/{profile_id}', [LivestockController::class, 'getLivestockByIdProfile']);
    Route::post('livestock', [LivestockController::class, 'postLivestock']);
    Route::post('livestock-photo/{id}', [LivestockController::class, 'postLivestockPhotoById']);
    Route::put('livestock-photo/{id}', [LivestockController::class, 'putLivestockPhotoById']);
    Route::get('livestock/{id}', [LivestockController::class, 'getLivestockById']);
    Route::put('livestock/{id}', [LivestockController::class, 'putLivestockById']);
    Route::delete('livestock/{id}', [LivestockController::class, 'deleteLivestockById']);

    // Livestock Photos
    Route::get('livestock/livestock-photos/{livestock_id}', [LivestockPhotoController::class, 'getLivestockPhotosByIdLivestock']);
    Route::post('livestock/livestock-photo/{livestock_id}', [LivestockPhotoController::class, 'postLivestockPhotoByIdLivestock']);
    Route::delete('livestock/livestock-photo/{id}', [LivestockPhotoController::class, 'deleteLivestockPhotoById']);

    // Livestock Types
    Route::get('livestock-types', [LivestockTypeController::class, 'getLivestockTypes']);
    Route::post('livestock-type', [LivestockTypeController::class, 'postLivestockType']);
    Route::get('livestock-type/{id}', [LivestockTypeController::class, 'getLivestockTypeById']);
    Route::put('livestock-type/{id}', [LivestockTypeController::class, 'putLivestockTypeById']);
    Route::delete('livestock-type/{id}', [LivestockTypeController::class, 'deleteLivestockTypeById']);

    // Livestock Species
    Route::get('livestocks-species/{livestock_type_id}', [LivestockSpeciesController::class, 'getLivestockSpeciesByIdLivestockType']);
    Route::post('livestock-species/{livestock_type_id}', [LivestockSpeciesController::class, 'postLivestockSpeciesByIdLivestockType']);
    Route::get('livestock-species/{id}', [LivestockSpeciesController::class, 'getLivestockSpeciesById']);
    Route::put('livestock-species/{id}', [LivestockSpeciesController::class, 'putLivestockSpeciesById']);
    Route::delete('livestock-species/{id}', [LivestockSpeciesController::class, 'deleteLivestockSpeciesById']);

    // Transactions
    Route::get('transactions', [TransactionController::class, 'getTransactions']);
    Route::post('transaction/{livestock_id}', [TransactionController::class, 'postTransactionByIdLivestock']);
    Route::get('transaction/{id}', [TransactionController::class, 'getTransactionById']);
    Route::put('transaction/{id}', [TransactionController::class, 'putTransactionById']);
    Route::delete('transaction/{id}', [TransactionController::class, 'deleteTransactionById']);

    // Payments
    Route::get('payments', [PaymentController::class, 'getPayments']);
    Route::post('payment/{transaction_id}', [PaymentController::class, 'postPaymentByIdTransaction']);
    Route::get('payment/{id}', [PaymentController::class, 'getPaymentById']);
    Route::put('payment/{id}', [PaymentController::class, 'putPaymentById']);
    Route::delete('payment/{id}', [PaymentController::class, 'deletePaymentById']);

    // Logout
    Route::post('logout', [AuthController::class, 'logout']);
});
