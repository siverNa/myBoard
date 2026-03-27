<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\AuthController;
use App\Http\Controllers\ChannelController;
use App\Http\Controllers\HomeController;
use App\Http\Controllers\PostController;
use App\Http\Controllers\CommentController;
use App\Http\Controllers\ChannelRequestController;

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

Route::get('/', [HomeController::class, 'index'])->name('home');

Route::get('/register', [AuthController::class, 'showRegisterForm'])->name('register.form');
Route::post('/register', [AuthController::class, 'register'])->name('register');

Route::get('/login', [AuthController::class, 'showLoginForm'])->name('login.form');
Route::post('/login', [AuthController::class, 'login'])->name('login');

Route::post('/logout', [AuthController::class, 'logout'])->name('logout');

// 아이디 찾기 및 비밀번호 초기화
Route::get('/find-id', [AuthController::class, 'showFindIdForm'])->name('auth.find-id.form');
Route::post('/find-id', [AuthController::class, 'findId'])->name('auth.find-id');

Route::get('/password/reset/request', [AuthController::class, 'showPasswordResetRequestForm'])->name('auth.password.request.form');
Route::post('/password/reset/request', [AuthController::class, 'checkPasswordResetRequest'])->name('auth.password.request');

Route::get('/password/reset/form', [AuthController::class, 'showPasswordResetForm'])->name('auth.password.reset.form');
Route::post('/password/reset/form', [AuthController::class, 'resetPassword'])->name('auth.password.reset');

// 게시글 생성
Route::get('/channels', [ChannelController::class, 'index'])->name('channels.index');
Route::get('/channels/{channelPk}', [ChannelController::class, 'show'])->name('channels.show');

Route::get('/channels/{channelPk}/posts/create', [PostController::class, 'create'])
    ->middleware('auth')
    ->name('posts.create');

Route::post('/channels/{channelPk}/posts', [PostController::class, 'store'])
    ->middleware('auth')
    ->name('posts.store');

// 게시글 상세보기
Route::get('/posts/{postPk}', [PostController::class, 'show'])->name('posts.show');

// 수정 페이지
Route::get('/posts/{postPk}/edit', [PostController::class, 'edit'])
    ->middleware('auth')
    ->name('posts.edit');
// 수정 진행
Route::put('/posts/{postPk}', [PostController::class, 'update'])
    ->middleware('auth')
    ->name('posts.update');
// 삭제
Route::delete('/posts/{postPk}', [PostController::class, 'destroy'])
    ->middleware('auth')
    ->name('posts.destroy');

// 댓글 작성
Route::post('/posts/{postPk}/comments', [CommentController::class, 'store'])
    ->middleware('auth')
    ->name('comments.store');
// 댓글 수정
Route::put('/comments/{commentPk}', [CommentController::class, 'update'])
    ->middleware('auth')
    ->name('comments.update');
// 댓글 삭제
Route::delete('/comments/{commentPk}', [CommentController::class, 'destroy'])
    ->middleware('auth')
    ->name('comments.destroy');

// 채널 관련 라우트
Route::get('/channel-requests/create', [ChannelRequestController::class, 'create'])
    ->middleware('auth')
    ->name('channel-requests.create');

Route::post('/channel-requests', [ChannelRequestController::class, 'store'])
    ->middleware('auth')
    ->name('channel-requests.store');

Route::get('/admin/channel-requests', [ChannelRequestController::class, 'index'])
    ->middleware('auth')
    ->name('channel-requests.index');

Route::post('/admin/channel-requests/{requestPk}/approve', [ChannelRequestController::class, 'approve'])
    ->middleware('auth')
    ->name('channel-requests.approve');

Route::post('/admin/channel-requests/{requestPk}/reject', [ChannelRequestController::class, 'reject'])
    ->middleware('auth')
    ->name('channel-requests.reject');