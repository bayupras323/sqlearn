<?php

use App\Http\Controllers\Classroom\ClassroomController;
use App\Http\Controllers\DemoController;
use App\Http\Controllers\Exercises\DatabaseController;
use App\Http\Controllers\Exercises\ExerciseController;
use App\Http\Controllers\Exercises\PackageController;
use App\Http\Controllers\Exercises\SQL\DataDefinitionController;
use App\Http\Controllers\Exercises\SQL\DataManipulationController;
use App\Http\Controllers\Exercises\SQL\ParsonsProblem2dController;

use App\Http\Controllers\Menu\MenuGroupController;
use App\Http\Controllers\Menu\MenuItemController;
use App\Http\Controllers\ScheduleController;
use App\Http\Controllers\RoleAndPermission\AssignPermissionController;
use App\Http\Controllers\RoleAndPermission\AssignUserToRoleController;
use App\Http\Controllers\RoleAndPermission\ExportPermissionController;
use App\Http\Controllers\RoleAndPermission\ExportRoleController;
use App\Http\Controllers\RoleAndPermission\ImportPermissionController;
use App\Http\Controllers\RoleAndPermission\ImportRoleController;
use App\Http\Controllers\RoleAndPermission\PermissionController;
use App\Http\Controllers\RoleAndPermission\RoleController;
use App\Http\Controllers\Students\StudentController;
use App\Http\Controllers\Exercises\Erd\RelationshipController;
use App\Http\Controllers\ScoreController;
use App\Http\Controllers\LoggingController;
use App\Http\Controllers\Exercises\Erd\EntityController;
use Illuminate\Support\Facades\Route;
use App\Models\User;
use App\Http\Controllers\UserController;
use App\Http\Controllers\DashboardController;

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

require_once 'erd-relation-for-student.php';
require_once 'erd-entity-for-student.php';

Route::get('/', function () {
    return redirect('login');
});

// Make route for student
Route::group(['middleware' => ['auth', 'verified']], function () {

    Route::get('/home', [DashboardController::class, 'dashboardUser'])->name('dashboard.user');
    Route::get('/start-exam/{id}', [DashboardController::class, 'startExam'])->name('dashboard.user.start');
    Route::get('/start-exam/{id_exam}/exam/{id}', [DashboardController::class, 'showExercise'])->name('dashboard.user.exercise.ddl');
    Route::get('/exercise/{schedule}', [DashboardController::class, 'exercise'])->name('dashboard.user.exercise');

    Route::post('student/exercise/entity/next_step', [EntityController::class, 'nextStep']);
    Route::post('student/exercise/relationship/next_step', [RelationshipController::class, 'nextStep']);
});

// Make route for except student
Route::group(['middleware' => ['auth', 'verified', 'except_student']], function () {

    Route::get('/dashboard', function () {
        return view('dashboard.home', ['users' => User::get(),]);
    })->name('dashboard');

    Route::get('/edit-profile', function () {
        return view('dashboard.profile');
    })->name('profile.edit');

    //user list
    Route::prefix('user-management')->group(function () {
        Route::resource('user', UserController::class);
        Route::post('import', [UserController::class, 'import'])->name('user.import');
        Route::get('export', [UserController::class, 'export'])->name('user.export');
        Route::get('demo', DemoController::class)->name('user.demo');
    });

    Route::prefix('menu-management')->group(function () {
        Route::resource('menu-group', MenuGroupController::class);
        Route::resource('menu-item', MenuItemController::class);
    });

    Route::group(['prefix' => 'role-and-permission'], function () {
        //role
        Route::resource('role', RoleController::class);
        Route::post('role-update-ordering/{id}', [RoleController::class, 'updateOrderingMenu'])->name('role.ordering');
        Route::get('role/export', ExportRoleController::class)->name('role.export');
        Route::post('role/import', ImportRoleController::class)->name('role.import');

        //permission
        Route::resource('permission', PermissionController::class);
        Route::get('permission/export', ExportPermissionController::class)->name('permission.export');
        Route::post('permission/import', ImportPermissionController::class)->name('permission.import');

        //assign permission
        Route::get('assign', [AssignPermissionController::class, 'index'])->name('assign.index');
        Route::get('assign/create', [AssignPermissionController::class, 'create'])->name('assign.create');
        Route::get('assign/{role}/edit', [AssignPermissionController::class, 'edit'])->name('assign.edit');
        Route::put('assign/{role}', [AssignPermissionController::class, 'update'])->name('assign.update');
        Route::post('assign', [AssignPermissionController::class, 'store'])->name('assign.store');

        //assign user to role
        Route::get('assign-user', [AssignUserToRoleController::class, 'index'])->name('assign.user.index');
        Route::get('assign-user/create', [AssignUserToRoleController::class, 'create'])->name('assign.user.create');
        Route::post('assign-user', [AssignUserToRoleController::class, 'store'])->name('assign.user.store');
        Route::get('assign-user/{user}/edit', [AssignUserToRoleController::class, 'edit'])->name('assign.user.edit');
        Route::put('assign-user/{user}', [AssignUserToRoleController::class, 'update'])->name('assign.user.update');
    });

    //packages
    Route::resource('packages', PackageController::class);
    Route::prefix('packages')->group(function () {
        //exercises
        Route::get('{package}/exercises/create', [ExerciseController::class, 'create'])->name('exercises.create');
        Route::get('{package}/exercises/{exercise}/show', [ExerciseController::class, 'show'])->name('exercises.show');
        Route::get('{package}/exercises/{exercise}/edit', [ExerciseController::class, 'edit'])->name('exercises.edit');
        Route::delete('exercises/{exercise}', [ExerciseController::class, 'destroy'])->name('exercises.destroy');
        //data manipulation
        Route::post('{package}/exercises/store/dml', [DataManipulationController::class, 'store'])->name('exercises.dml.store');
        Route::post('{package}/exercises/store/pp2d', [ParsonsProblem2dController::class, 'store'])->name('exercises.pp2d.store');

        Route::put('exercises/{exercise}/dml', [DataManipulationController::class, 'update'])->name('exercises.dml.update');
        //data definition
        Route::post('{package}/exercises/store/ddl', [DataDefinitionController::class, 'store'])->name('exercises.ddl.store');
        Route::prefix('exercises')->group(function () {
            Route::post('store/database', [DatabaseController::class, 'store'])->name('exercises.storeDatabase');
            Route::get('getDatabaseList', [DatabaseController::class, 'getDatabaseList'])->name('exercises.getDatabaseList');
            Route::get('getTableList/{db_name}', [DatabaseController::class, 'getTableListByDatabase'])->name('exercises.getTableList');
            Route::get('getTableData/{db_name}/{table}', [DatabaseController::class, 'getTableData'])->name('exercises.getTableData');
            Route::get('getTableDesc/{db_name}/{table}', [DatabaseController::class, 'getTableDesc'])->name('exercises.getTableDesc');
            Route::get('getCustomizeTable/{db_name}/{table}', [DatabaseController::class, 'getCustomizeTable'])->name('exercises.getCustomizeTable');
            Route::get('executeQuery/{db_id}/{selected_tables}/{answer}', [DatabaseController::class, 'executeQuery'])->name('exercises.executeQuery');

            //erd section
            //=============================================Relationship================================================================\\
            Route::get('erd/relationship/{id}/edit', [RelationshipController::class, 'edit'])->name('erd.relationship.edit');
            Route::get('erd/relationship/delete/{id}', [RelationshipController::class, 'destroy'])->name('erd.relationship.destroy');
            //Route::get('erd/relationship/get', [RelationshipController::class, 'get'])->name('erd.relationship.get');
           // Route::post('erd/relationship/get', [RelationshipController::class, 'get'])->name('erd.relationship.get.post');
            Route::post('erd/relationship/store', [RelationshipController::class, 'store'])->name('erd.relationship.store');
            Route::post('erd/relationship/update', [RelationshipController::class, 'update'])->name('erd.relationship.update');
            //=============================================Relationship================================================================\\
            //erd entity
            //============================================ Entity&Attribute ======================================================\\
            Route::get('erd/entity/{id}/edit', [EntityController::class, 'edit'])->name(('erd.entity,edit'));
            Route::get('erd/entity/delete/{id}', [EntityController::class, 'destroy'])->name(('erd.entity.destroy'));
            Route::get('erd/entity/get', [EntityController::class, 'get'])->name('erd.entity.get');
            Route::post('erd/entity/get', [EntityController::class, 'getData'])->name('erd.entity.get.post');
            Route::post('erd/entity/store', [EntityController::class, 'store'])->name('erd.entity.store');
            Route::post('erd/entity/update', [EntityController::class, 'update'])->name('erd.entity.update');
            //============================================ Entity&Attribute ======================================================\\
        });
    });


    //classroom
    Route::resource('classrooms', ClassroomController::class);

    //Student
    Route::resource('students', StudentController::class)->except(['index', 'show']);
    Route::get('students/{classroom}', [StudentController::class, 'index'])->name('students.index');
    Route::post('students/import', [StudentController::class, 'import'])->name('students.import');

    //schedules
    Route::resource('schedules', ScheduleController::class);

    //score
    Route::resource('scores', ScoreController::class)->parameters(['scores' => 'schedule'])->except(['create', 'store', 'show', 'edit', 'update', 'destroy']);
    Route::get('scores/{schedule}', [ScoreController::class, 'show'])->name('scores.show');
    Route::get('scores/log/{schedule}/{student}', [ScoreController::class, 'log'])->name('scores.log');
    Route::get('scores/summary/{schedule}/{classroom}', [ScoreController::class, 'summary'])->name('scores.summary');
    Route::get('scores/export/{schedule}/{classroom}', [ScoreController::class, 'export'])->name('scores.export');

    // log
    Route::get('logs-pp2d', [LoggingController::class, 'index'])->name('logs-pp2d.index');
    Route::get('logs/{classroom}', [LoggingController::class, 'show'])->name('logs.show');
});
