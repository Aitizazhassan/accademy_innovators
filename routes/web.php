<?php

use Illuminate\Support\Facades\View;
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\CrewController;
use App\Http\Controllers\ItemController;
use App\Http\Controllers\MCQsController;
use App\Http\Controllers\RoleController;
use App\Http\Controllers\BoardController;
use App\Http\Controllers\TopicController;
use App\Http\Controllers\UsersController;
use App\Http\Controllers\ChapterController;
use App\Http\Controllers\CountryController;
use App\Http\Controllers\ProfileController;
use App\Http\Controllers\ProjectController;
use App\Http\Controllers\SubjectController;
use App\Http\Controllers\AssemblyController;
use App\Http\Controllers\CategoryController;
use App\Http\Controllers\CostCodeController;
use App\Http\Controllers\EstimateController;
use App\Http\Controllers\ClassRoomController;
use App\Http\Controllers\LaborCodeController;
use App\Http\Controllers\SourceCodeController;
use App\Http\Controllers\CrewPositionController;
use App\Http\Controllers\MaterialCodeController;

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
    // return view('welcome');
    return redirect('/login');
});

Route::get('/dashboard', function () {
    return view('dashboard');
})->middleware(['auth', 'verified'])->name('dashboard');

Route::middleware('auth')->group(function () {
    Route::get('/profile', [ProfileController::class, 'edit'])->name('profile.edit')->middleware('permission:profile.edit');
    Route::patch('/profile', [ProfileController::class, 'update'])->name('profile.update')->middleware('permission:profile.edit');
    Route::delete('/profile', [ProfileController::class, 'destroy'])->name('profile.destroy')->middleware('permission:profile.delete');

    Route::prefix('settings')->group(function () {

        // user routes
        Route::get('/users', [UsersController::class, 'index'])->name('users.index')->middleware('permission:user.view');
        Route::get('/users/setting/{user}', [UsersController::class, 'setting'])->name('users.setting')->middleware('permission:user.edit');
        Route::patch('/users/setting/{user}', [UsersController::class, 'update'])->name('users.update')->middleware('permission:user.edit');
        Route::put('/users/setting/update-password/{user}', [UsersController::class, 'updatePassword'])->name('users.update.password')->middleware('permission:user.edit');
        Route::delete('/users/{user}', [UsersController::class, 'destroy'])->name('users.destroy')->middleware('permission:user.delete');
        Route::get('/users/create', [UsersController::class, 'create'])->name('users.create')->middleware('permission:user.create');
        Route::post('/users/store', [UsersController::class, 'store'])->name('users.store')->middleware('permission:user.create');


        // roles
        Route::resource('roles', RoleController::class)->middleware('permission:role.view');
        Route::get('/edit-permission/{id}', [RoleController::class, 'editPermissions'])->name('roles.edit.permissions')->middleware('permission:role.edit');
        Route::put('/update-permission/{id}', [RoleController::class, 'updatePermissions'])->name('roles.update.permissions')->middleware('permission:role.edit');


        //country

        Route::resource('country', CountryController::class);


        // boards
        // Route::get('/boards', [BoardController::class, 'index'])->name('boards.index')->middleware('permission:user.view');
        // Route::get('/boards/{user}', [BoardController::class, 'edit'])->name('boards.edit')->middleware('permission:user.edit');
        // Route::patch('/boards/setting/{user}', [BoardController::class, 'update'])->name('boards.update')->middleware('permission:user.edit');
        // Route::delete('/boards/{user}', [BoardController::class, 'destroy'])->name('boards.destroy')->middleware('permission:user.delete');
        // Route::get('/boards/create', [BoardController::class, 'create'])->name('boards.create')->middleware('permission:user.create');
        // Route::post('/boards/store', [BoardController::class, 'store'])->name('boards.store')->middleware('permission:user.create');


        Route::resource('boards', BoardController::class);

        // classroom
        Route::resource('classroom', ClassRoomController::class);
        // subject
        Route::resource('subject', SubjectController::class);
        // chapter
        Route::resource('chapter', ChapterController::class);

        // chapter
        Route::resource('topic', TopicController::class);
        Route::resource('mcqs', MCQsController::class);

        Route::get('/classes/{board_id}', [MCQsController::class, 'getClass'])->name('getClass');
        Route::get('/subjects/{class_id}', [MCQsController::class, 'getSubjects'])->name('getSubjects');
        Route::get('/chapters/{subject_id}', [MCQsController::class, 'getChapters'])->name('getChapters');
        Route::get('/topics/{chapter_id}', [MCQsController::class, 'getTopics'])->name('getTopics');

        Route::get('/download-mcq/pptx/{id}', [MCQsController::class, 'downloadSingleMcqAsPptx'])->name('download.mcq.pptx');

    });
});

require __DIR__ . '/auth.php';
