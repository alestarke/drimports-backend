<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\Api\AuthController;
use App\Http\Controllers\Api\BrandsController;
use App\Http\Controllers\Api\UsersController;
use App\Http\Controllers\Api\ProductsController;
use App\Http\Controllers\Api\CategoriesController;
use App\Http\Controllers\Api\ImportController;
use App\Http\Controllers\Api\ClientsController;
use App\Http\Controllers\Api\SalesController;

//LOGIN
Route::post('/login', [AuthController::class, 'login'])->middleware('throttle:5,1');

//REGISTER USER
Route::post('/register', [UsersController::class, 'store'])->middleware('throttle:5,1');

//ROUTES PROTECTED BY AUTH SANCTUM
Route::middleware('auth:sanctum')->group(function () {
    //USER ROUTES
    Route::get('/users', [UsersController::class, 'index']);
    Route::get('/users/{user}', [UsersController::class, 'show']);
    Route::put('/users/{user}', [UsersController::class, 'update']);
    Route::delete('/users/{user}', [UsersController::class, 'destroy']);

    //PRODUCT ROUTES
    Route::get('/products', [ProductsController::class, 'index']);
    Route::get('/products/{product}', [ProductsController::class, 'show']);
    Route::post('/products', [ProductsController::class, 'store']);
    Route::put('/products/{product}', [ProductsController::class, 'update']);
    Route::delete('/products/{product}', [ProductsController::class, 'destroy']);

    //BRAND ROUTES
    Route::get('/brands', [BrandsController::class, 'index']);
    Route::get('/brands/{brand}', [BrandsController::class, 'show']);
    Route::post('/brands', [BrandsController::class, 'store']);
    Route::put('/brands/{brand}', [BrandsController::class, 'update']);
    Route::delete('/brands/{brand}', [BrandsController::class, 'destroy']);

    //CATEGORY ROUTES
    Route::get('/categories', [CategoriesController::class, 'index']);
    Route::get('/categories/{category}', [CategoriesController::class, 'show']);
    Route::post('/categories', [CategoriesController::class, 'store']);
    Route::put('/categories/{category}', [CategoriesController::class, 'update']);
    Route::delete('/categories/{category}', [CategoriesController::class, 'destroy']);

    //IMPORT ROUTES
    Route::get('/imports', [ImportController::class, 'index']);
    Route::get('/imports/{import}', [ImportController::class, 'show']);
    Route::post('/imports', [ImportController::class, 'store']);
    Route::put('/imports/{import}', [ImportController::class, 'update']);
    Route::delete('/imports/{import}', [ImportController::class, 'destroy']);

    //CLIENT ROUTES
    Route::get('/clients', [ClientsController::class, 'index']);
    Route::get('/clients/{client}', [ClientsController::class, 'show']);
    Route::post('/clients', [ClientsController::class, 'store']);
    Route::put('/clients/{client}', [ClientsController::class, 'update']);
    Route::delete('/clients/{client}', [ClientsController::class, 'destroy']);

    //SALES ROUTES
    Route::get('/sales', [SalesController::class, 'index']);
    Route::get('/sales/{sale}', [SalesController::class, 'show']);
    Route::post('/sales', [SalesController::class, 'store']);
    Route::put('/sales/{sale}', [SalesController::class, 'update']);
    Route::delete('/sales/{sale}', [SalesController::class, 'destroy']);
});
