<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\AuthController;
use App\Http\Controllers\BlogController;
use App\Http\Controllers\PostController;
use App\Http\Controllers\DashboardController;
use App\Http\Controllers\UserController;
use App\Http\Controllers\CategoryController;
use App\Http\Controllers\CommentsController;

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

/**
 * Route to render home page with GET method
 */
Route::get('/', function () {
    return view('home')->with([
        'title' => 'Home',
        'subtitle' => 'About',
        'breadcrumb' => [
            'Home' => '/',
        ]
    ]);
})->name('home');

/**
 * Route to render about page with GET method
 */
Route::get('/about', function () {
    return view('about')->with([
        'title' => 'About',
        'subtitle' => 'About',
        'breadcrumb' => [
            'Home' => '/',
            'About' => '/about',
        ]
    ]);
})->name('about');

/**
 * Route to render contact page with GET method
 */
Route::get('/contact', function () {
    return view('contact')->with([
        'title' => 'Contact',
        'subtitle' => 'Contact',
        'breadcrumb' => [
            'Home' => '/',
            'Contact' => '/contact',
        ]
    ]);
})->name('contact');

/**
 * Route to render posts/blog page with GET method
 */
Route::get(
    '/blog',
    [
        BlogController::class,
        'index'
    ]
)->name('blog');

/**
 * Route to render single post page with GET method
 */
Route::get(
    '/blog/{post:slug}',
    [
        BlogController::class,
        'show'
    ]
)->name('blog.show');

/**
 * Route to render signin page with GET method
 */
Route::get(
    '/signin',
    [
        AuthController::class,
        'signInPage'
    ]
)->middleware('guest')->name('signin.page');

/**
 * Route to authenticate user with POST method
 */
Route::post(
    '/signin',
    [
        AuthController::class,
        'signIn'
    ]
)->middleware('guest')->name('signin');

/**
 * Route to render signup page with GET method
 */
Route::get(
    '/signup',
    [
        AuthController::class,
        'signUpPage'
    ]
)->middleware('guest')->name('signup.page');

/**
 * Route to create new user/registered new user with POST method
 */
Route::post(
    '/signup',
    [
        AuthController::class,
        'signUp'
    ]
)->middleware('guest')->name('signup');

/**
 * Route to logout user with GET method
 */
Route::get(
    '/signout',
    [
        AuthController::class,
        'signOut'
    ]
)->middleware('auth')->name('signout');

/**
 * Route to redirect to dashboard page with GET method
 */
Route::get(
    '/dashboard',
    [
        DashboardController::class,
        'index'
    ]
)->middleware('auth')->name('dashboard');

/**
 * Route to render users management or detail page with GET method
 */
Route::get(
    '/user/profile/{user:username}',
    [
        UserController::class,
        'show'
    ]
)->middleware('auth')->name('user.profile');

Route::group(['middleware' => ['auth', 'role:Administrator']], function () {
    /**
     * Route to render users management page with GET method
     */
    Route::get(
        '/user',
        [
            UserController::class,
            'index'
        ]
    )->name('user');

    /**
     * Route to create new user/registered new user with POST method
     */
    Route::post(
        '/user/store',
        [
            UserController::class,
            'store'
        ]
    )->name('user.store');

    /**
     * Route to update user with POST method
     */
    Route::post(
        '/user/update/{user:id}',
        [
            UserController::class,
            'update'
        ]
    )->name('user.update');

    /**
     * Route to delete user with GET method
     */
    Route::get(
        '/user/delete/{user:id}',
        [
            UserController::class,
            'destroy'
        ]
    )->name('user.destroy');

    /**
     * Route to render category management page with GET method
     */
    Route::get(
        '/category',
        [
            CategoryController::class,
            'index'
        ]
    )->name('category');

    /**
     * Route to create new category with POST method
     */
    Route::post(
        '/category/store',
        [
            CategoryController::class,
            'store'
        ]
    )->name('category.store');

    /**
     * Route to update category with POST method
     */
    Route::post(
        '/category/update/{category:id}',
        [
            CategoryController::class,
            'update'
        ]
    );

    /**
     * Route to delete category with GET method
     */
    Route::get(
        '/category/delete/{category:id}',
        [
            CategoryController::class,
            'destroy'
        ]
    );
});

/**
 * Route to render post management page with GET method
 */
Route::get(
    '/post',
    [
        PostController::class,
        'index'
    ]
)->middleware('auth')->name('post');

/**
 * Route to store post with POST method
 */
Route::post(
    '/post/store',
    [
        PostController::class,
        'store'
    ]
)->middleware('auth')->name('post.store');

/**
 * Route for showing the specific post with GET method
 */
Route::get(
    '/post/show/{post:slug}',
    [
        PostController::class,
        'show'
    ]
)->name('post.show');

/**
 * Route for handle getting single post data with GET method
 */
Route::get(
    '/post/get',
    [
        PostController::class,
        'get'
    ]
)->middleware('auth')->name('post.get');

/**
 * Route to update post with POST method
 */
Route::post(
    '/post/update/{post:id}',
    [
        PostController::class,
        'update'
    ]
)->middleware('auth')->name('post.update');

/**
 * Route to delete post with GET method
 */
Route::get(
    '/post/delete/{post:id}',
    [
        PostController::class,
        'destroy'
    ]
)->middleware('auth')->name('post.destroy');

/**
 * Route to render comments management page with GET method
 */
Route::get(
    '/comments/manage',
    [
        CommentsController::class,
        'index'
    ]
)->middleware('auth')->name('comment');

/**
 * Route to store comment with POST method
 */
Route::post(
    '/comments/store/{post:slug}',
    [
        CommentsController::class,
        'store'
    ]
)->middleware('auth')->name('comment.store');

/**
 * Route to replying specific comment with POST method
 */
Route::post(
    '/comments/reply/{comment:id}',
    [
        CommentsController::class,
        'reply'
    ]
)->middleware('auth')->name('comment.reply');

/**
 * Route to report comment with POST method
 */
Route::post(
    '/comments/report/{comment:id}',
    [
        CommentsController::class,
        'report'
    ]
)->middleware('auth')->name('comment.report');

/**
 * Route to get more comments with GET method
 */
Route::get(
    '/comments/get',
    [
        CommentsController::class,
        'get'
    ]
)->middleware('auth')->name('comment.get');

/**
 * Route to delete comment with GET method
 */
Route::get(
    '/comments/delete/{comment:id}',
    [
        CommentsController::class,
        'destroy'
    ]
)->middleware('auth')->name('comment.destroy');
