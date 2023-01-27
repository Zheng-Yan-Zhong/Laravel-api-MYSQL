<?php
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;
use \App\Http\Controllers\ProductController;

Route::get('/product',[ProductController::class, 'getProduct']);
Route::post('/product',[ProductController::class, 'createProduct']);


Route::get('/test',function() {
    return "Server running";
});


Route::middleware('auth:sanctum')->get('/user', function (Request $request) {
    return $request->user();
});


