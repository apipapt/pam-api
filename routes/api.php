<?php

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;

use App\Http\Controllers\AuthController;
use App\Http\Controllers\Admin\PermissionsController;
use App\Http\Controllers\Admin\RolesController;
use App\Http\Controllers\Admin\UsersController;
use App\Http\Controllers\MembersController;

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

// Route::middleware('auth:sanctum')->get('/user', function (Request $request) {
//     return $request->user();
// });

Route::group(['prefix' => 'auth'], function() {
    Route::post('login', [AuthController::class, 'login']);
    Route::get('logout', [AuthController::class, 'logout'])->middleware('auth:api');
    Route::get('user-profile', [AuthController::class, 'userProfile'])->middleware('auth:api');
    // Route::post('social-login', [AuthController::class, 'socialLogin']);
});


Route::group(['prefix' => 'admin', 'middleware' => 'auth:api'], function() {

    Route::group(['prefix' => 'roles'], function() {
        Route::get      ('',                [RolesController::class, 'index'            ])->middleware('permission:roles-list');
        Route::post     ('',                [RolesController::class, 'store'            ])->middleware('permission:roles-add');
        Route::get      ('{id}',            [RolesController::class, 'show'             ])->middleware('permission:roles-list');
        Route::put      ('{id}',            [RolesController::class, 'update'           ])->middleware('permission:roles-edit');
        Route::delete   ('/multi-delete',   [RolesController::class, 'multiDestroy'     ])->middleware('permission:roles-delete');
        Route::delete   ('{id}',            [RolesController::class, 'destroy'          ])->middleware('permission:roles-delete');
        Route::get      ('get/permissions', [RolesController::class, 'getPermissions'   ])->middleware('permission:roles-list');
    });

    Route::group(['prefix' => 'permissions'], function() {
        Route::get      ('',                [PermissionsController::class, 'index'          ])->middleware('permission:permissions-list');
        Route::post     ('',                [PermissionsController::class, 'store'          ])->middleware('permission:permissions-add');
        Route::get      ('{id}',            [PermissionsController::class, 'show'           ])->middleware('permission:permissions-list');
        Route::put      ('{id}',            [PermissionsController::class, 'update'         ])->middleware('permission:permissions-edit');
        Route::delete   ('/multi-delete',   [PermissionsController::class, 'multiDestroy'   ])->middleware('permission:permissions-delete');
        Route::delete   ('{id}',            [PermissionsController::class, 'destroy'        ])->middleware('permission:permissions-delete');
    });

    Route::group(['prefix' => 'users'], function() {
        Route::get      ('',                [UsersController::class, 'index'            ])->middleware('permission:users-list');
        Route::post     ('',                [UsersController::class, 'store'            ])->middleware('permission:users-add');
        Route::get      ('{id}',            [UsersController::class, 'show'             ])->middleware('permission:users-list');
        Route::put      ('{id}',            [UsersController::class, 'update'           ])->middleware('permission:users-edit');
        Route::delete   ('/multi-delete',   [UsersController::class, 'multiDestroy'     ])->middleware('permission:users-delete');
        Route::delete   ('{id}',            [UsersController::class, 'destroy'          ])->middleware('permission:users-delete');
        Route::get      ('get/roles',       [UsersController::class, 'getRoles'         ])->middleware('permission:users-list');
    });
});

// Member
Route::group(['middleware' => 'auth:api'], function() {
    Route::group(['prefix' => 'members'], function() {
        Route::get      ('',                [MembersController::class, 'index'            ])->middleware('permission:members-list');
        Route::post     ('',                [MembersController::class, 'store'            ])->middleware('permission:members-add');
        Route::get      ('{id}',            [MembersController::class, 'show'             ])->middleware('permission:members-list');
        Route::put      ('{id}',            [MembersController::class, 'update'           ])->middleware('permission:members-edit');
        Route::delete   ('/multi-delete',   [MembersController::class, 'multiDestroy'     ])->middleware('permission:members-delete');
        Route::delete   ('{id}',            [MembersController::class, 'destroy'          ])->middleware('permission:members-delete');
        Route::get      ('get/members',     [MembersController::class, 'getMembers'       ])->middleware('permission:members-list');
    });
});