<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\HomeController;
use App\Http\Controllers\LoadController;
use App\Http\Controllers\InvoiceController;
use App\Http\Controllers\CommentController;
use App\Http\Controllers\UsersController;


Route::get('/', function () {
    return view('welcome');
});

Route::get('/users', [UsersController::class, 'getUsers']);
Route::get('/api/comments/all', [CommentController::class, 'getCommentsAll']);

Route::get('/portalhome', [HomeController::class, 'index']);

Route::get('/loads', [LoadController::class, 'index']);
Route::get('/api/loads', [LoadController::class, 'getLoads']);

Route::get('/load/{loadNumber}', [LoadController::class, 'indexLoadDetails']);
Route::get('/api/loads/load/{loadNumber}', [LoadController::class, 'getLoadDetails']);
Route::get('/api/invoices/load/{loadNumber}', [InvoiceController::class, 'getInvoicesByLoad']);


Route::get('/invoices', [InvoiceController::class, 'index']);
Route::get('/api/invoices', [InvoiceController::class, 'getInvoices']);
Route::post('/api/invoices/create', [InvoiceController::class, 'createInvoice']);


Route::get('/load/{loadNumber}/invoice/{invoiceNumber}', [InvoiceController::class, 'indexInvoiceDetails']);
Route::get('/api/invoice/invoice/{invoiceNumber}', [InvoiceController::class, 'getInvoiceDetails']);
Route::put('/api/invoice/edit/invoice/{invoiceNumber}', [InvoiceController::class, 'saveInvoiceDetails']);

Route::get('/api/comments/invoice/{invoiceNumber}', [CommentController::class, 'getCommentsByInvoice']);
Route::post('/api/comments/add', [CommentController::class, 'addComment']);

Route::put('/api/comments/edit/comment/{commentId}', [CommentController::class, 'editComment']);
Route::delete('/api/comments/comment/{commentId}', [CommentController::class, 'deleteComment']);

Route::delete('/api/comments/invoice/delete/{invoiceNumber}', [CommentController::class, 'deleteAllInvoiceComments']);
Route::delete('/api/invoice/delete/invoice/{invoiceNumber}', [InvoiceController::class, 'deleteInvoice']);

Route::get('/load/{loadNumber}/invoice/{invoiceNumber}/comment/{commentId}', [CommentController::class, 'indexCommentDetails']);



//Hierarchical
Route::delete('/delete/load/{loadNumber}/invoice/{invoiceNumber}/comment/{commentId}', [CommentController::class, 'deleteCommentH']);
Route::post('/insert/load/{loadNumber}/invoice/{invoiceNumber}/comment', [CommentController::class, 'insertCommentH']);
Route::put('/edit/load/{loadNumber}/invoice/{invoiceNumber}/comment/{commentId}', [CommentController::class, 'editCommentH']);
Route::get('/get/load/{loadNumber}/invoice/{invoiceNumber}/comment/{commentId}', [CommentController::class, 'getCommentH']);
Route::get('/get/load/{loadNumber}/invoice/{invoiceNumber}/comments', [CommentController::class, 'getAllCommentsH']);

Route::delete('/delete/load/{loadNumber}/invoice/{invoiceNumber}', [InvoiceController::class, 'deleteInvoiceH']);
Route::post('/insert/load/{loadNumber}', [InvoiceController::class, 'insertInvoiceH']);
Route::put('/edit/load/{loadNumber}/invoice/{invoiceNumber}', [InvoiceController::class, 'editInvoiceH']);
Route::get('/get/load/{loadNumber}/invoice/{invoiceNumber}', [InvoiceController::class, 'getInvoiceH']);
Route::get('/get/load/{loadNumber}/invoices', [InvoiceController::class, 'getAllInvoicesH']);

Route::delete('/delete/load/{loadNumber}', [LoadController::class, 'deleteLoadH']);
Route::post('/insert', [LoadController::class, 'insertLoadH']);
Route::put('/edit/load/{loadNumber}', [LoadController::class, 'editLoadH']);
Route::get('/get/load/{loadNumber}', [LoadController::class, 'getLoadH']);
Route::get('/getloads', [LoadController::class, 'getAllLoadsH']);


Route::post('login', 'AuthController@login');
