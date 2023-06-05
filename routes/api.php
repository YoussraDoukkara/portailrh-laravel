<?php

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\API\AuthController;
use App\Http\Controllers\API\UserController;
use App\Http\Controllers\API\DesignationController;
use App\Http\Controllers\API\DepartmentController;
use App\Http\Controllers\API\TeamController;
use App\Http\Controllers\API\EmployeeController;
use App\Http\Controllers\API\TimeController;
use App\Http\Controllers\API\TimesheetController;
use App\Http\Controllers\API\AttendanceController;
use App\Http\Controllers\API\ReportController;
use App\Http\Controllers\API\LeaveAbsenceRequestController;
use App\Http\Controllers\API\LeaveAbsenceRequestCommentController;
use App\Http\Controllers\API\DocumentCategoryController;
use App\Http\Controllers\API\DocumentController;
use App\Http\Controllers\API\NoteController;
use App\Http\Controllers\API\PostController;
use App\Http\Controllers\API\DataController;

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

Route::group(['prefix' => 'auth', 'as' => 'auth.'], function() {
    Route::post('login', [AuthController::class, 'login']);
    Route::post('send', [AuthController::class, 'send']);
    Route::post('reset', [AuthController::class, 'reset']);
});

Route::middleware('auth:api')->group(function () {
    Route::group(['prefix' => 'auth', 'as' => 'auth.'], function() {
        Route::post('logout', [AuthController::class, 'logout']);
    });

    Route::group(['prefix' => 'users', 'as' => 'users.'], function() {
        Route::get('/', [UserController::class, 'index']);
        Route::post('/', [UserController::class, 'store']);
        Route::get('/{id}', [UserController::class, 'show']);
        Route::put('/{id}', [UserController::class, 'update']);
        Route::delete('/{id}', [UserController::class, 'destroy']);
    });

    Route::group(['prefix' => 'designations', 'as' => 'designations.'], function() {
        Route::get('/', [DesignationController::class, 'index']);
        Route::post('/', [DesignationController::class, 'store']);
        Route::get('/{id}', [DesignationController::class, 'show']);
        Route::put('/{id}', [DesignationController::class, 'update']);
        Route::delete('/{id}', [DesignationController::class, 'destroy']);
    });

    Route::group(['prefix' => 'departments', 'as' => 'departments.'], function() {
        Route::get('/', [DepartmentController::class, 'index']);
        Route::post('/', [DepartmentController::class, 'store']);
        Route::get('/{id}', [DepartmentController::class, 'show']);
        Route::put('/{id}', [DepartmentController::class, 'update']);
        Route::delete('/{id}', [DepartmentController::class, 'destroy']);
    });

    Route::group(['prefix' => 'teams', 'as' => 'teams.'], function() {
        Route::get('/', [TeamController::class, 'index']);
        Route::post('/', [TeamController::class, 'store']);
        Route::get('/{id}', [TeamController::class, 'show']);
        Route::put('/{id}', [TeamController::class, 'update']);
        Route::delete('/{id}', [TeamController::class, 'destroy']);
    });

    Route::group(['prefix' => 'employees', 'as' => 'employees.'], function() {
        Route::get('/', [EmployeeController::class, 'index']);
        Route::post('/', [EmployeeController::class, 'store']);
        Route::get('/{id}', [EmployeeController::class, 'show']);
        Route::put('/{id}', [EmployeeController::class, 'update']);
        Route::delete('/{id}', [EmployeeController::class, 'destroy']);
    });

    Route::group(['prefix' => 'times', 'as' => 'times.'], function() {
        Route::get('/', [TimeController::class, 'index']);
        Route::post('/', [TimeController::class, 'store']);
        Route::get('/{id}', [TimeController::class, 'show']);
        Route::put('/{id}', [TimeController::class, 'update']);
        Route::delete('/{id}', [TimeController::class, 'destroy']);
    });

    Route::group(['prefix' => 'leave-absence-requests', 'as' => 'leave-absence-requests.'], function() {
        Route::get('/', [LeaveAbsenceRequestController::class, 'index']);
        Route::post('/', [LeaveAbsenceRequestController::class, 'store']);
        Route::get('/{id}', [LeaveAbsenceRequestController::class, 'show']);
        Route::put('/{id}', [LeaveAbsenceRequestController::class, 'update']);
        Route::delete('/{id}', [LeaveAbsenceRequestController::class, 'destroy']);

        Route::put('/{id}/approve', [LeaveAbsenceRequestController::class, 'approve']);
        Route::put('/{id}/reject', [LeaveAbsenceRequestController::class, 'reject']);

        Route::group(['prefix' => '/{leaveAbsenceRequest:id}/comments', 'as' => 'comments.'], function() {
            Route::get('/', [LeaveAbsenceRequestCommentController::class, 'index']);
            Route::post('/', [LeaveAbsenceRequestCommentController::class, 'store']);
            Route::get('/{id}', [LeaveAbsenceRequestCommentController::class, 'show']);
            Route::put('/{id}', [LeaveAbsenceRequestCommentController::class, 'update']);
            Route::delete('/{id}', [LeaveAbsenceRequestCommentController::class, 'destroy']);
        });
    });

    Route::group(['prefix' => 'timesheets', 'as' => 'timesheets.'], function() {
        Route::get('/', [TimesheetController::class, 'index']);
        Route::post('/', [TimesheetController::class, 'store']);
    });

    Route::group(['prefix' => 'attendances', 'as' => 'attendances.'], function() {
        Route::get('/', [AttendanceController::class, 'index']);

        Route::put('/check-in', [AttendanceController::class, 'checkIn']);
        Route::put('/check-out', [AttendanceController::class, 'checkOut']);
        Route::put('/break-in', [AttendanceController::class, 'breakIn']);
        Route::put('/break-out', [AttendanceController::class, 'breakOut']);
    });

    Route::group(['prefix' => 'reports', 'as' => 'reports.'], function() {
        Route::get('/', [ReportController::class, 'index']);
        Route::get('/download', [ReportController::class, 'download']);
    });

    Route::group(['prefix' => 'documents', 'as' => 'documents.'], function() {
        Route::group(['prefix' => 'categories', 'as' => 'categories.'], function() {
            Route::get('/', [DocumentCategoryController::class, 'index']);
            Route::post('/', [DocumentCategoryController::class, 'store']);
            Route::get('/{id}', [DocumentCategoryController::class, 'show']);
            Route::put('/{id}', [DocumentCategoryController::class, 'update']);
            Route::delete('/{id}', [DocumentCategoryController::class, 'destroy']);

            Route::group(['prefix' => '/{documentCategory:id}/documents', 'as' => 'documents.'], function() {
                Route::get('/', [DocumentController::class, 'index']);
                Route::post('/', [DocumentController::class, 'store']);
                Route::get('/{id}', [DocumentController::class, 'show']);
                Route::put('/{id}', [DocumentController::class, 'update']);
                Route::delete('/{id}', [DocumentController::class, 'destroy']);
    
                Route::get('/{id}/download', [DocumentController::class, 'download']);
            });
        });
    });

    Route::group(['prefix' => 'posts', 'as' => 'posts.'], function() {
        Route::get('/', [PostController::class, 'index']);
        Route::post('/', [PostController::class, 'store']);
        Route::get('/{id}', [PostController::class, 'show']);
        Route::put('/{id}', [PostController::class, 'update']);
        Route::delete('/{id}', [PostController::class, 'destroy']);
    });

    Route::group(['prefix' => 'notes', 'as' => 'notes.'], function() {
        Route::get('/', [NoteController::class, 'index']);
        Route::post('/', [NoteController::class, 'store']);
        Route::get('/{id}', [NoteController::class, 'show']);
        Route::put('/{id}', [NoteController::class, 'update']);
        Route::delete('/{id}', [NoteController::class, 'destroy']);
    });

    Route::get('/data', DataController::class);
});

Route::middleware('auth:api')->get('/user', function (Request $request) {
    return $request->user();
});
