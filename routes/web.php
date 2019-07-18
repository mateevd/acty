<?php

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
	
	use Illuminate\Support\Facades\Auth;
	use Illuminate\Support\Facades\Route;

Route::get('/', function () {
    return view('welcome');
});

Auth::routes();
	Route::get('/configuration', 'HomeController@showChangePasswordForm')->name('Configuration');
	Route::post('/configuration', 'HomeController@changePassword')->name('Configuration');
	
	Route::get('/', 'HomeController@index')->name('indicateurs');
	Route::get('/home', 'HomeController@index')->name('indicateurs');
	
	Route::get('add-to-log', 'HomeController@myTestAddToLog')->name('journal');
	Route::get('logActivity', 'HomeController@logActivity')->name('journal');
	
	/*Projects Routes*/
	Route::get('activities', ['uses' => 'ActivityController@index', 'as' => 'activités']);
	Route::post('activities/create', ['uses' => 'ActivityController@create', 'as' => 'activities.create']);
	Route::get('activities/plan/{id}', ['uses' => 'ActivityController@plan', 'as' => 'planification']);
	Route::get('activities/plan/{id}/tasks/getPhases/{activity_id}', ['uses' => 'TaskController@getPhases', 'as' => 'tasks.getPhases']);
	Route::get('activities/details/{id}', ['uses' => 'ActivityController@details', 'as' => 'activities.details']);
	Route::put('activities/update', ['uses' => 'ActivityController@update', 'as' => 'activities.update']);
	Route::delete('activities/delete', ['uses' => 'ActivityController@destroy', 'as' => 'activities.destroy']);
	
	Route::post('activities/terminate', ['uses' => 'ActivityController@terminate', 'as' => 'activities.terminate']);
	Route::post('activities/activate', ['uses' => 'ActivityController@activate', 'as' => 'activities.activate']);
	Route::post('activities/privacy', ['uses' => 'ActivityController@privacy', 'as' => 'activities.privacy']);
	
	/*Phases Routes*/
	Route::post('phases/create/{activity_id}', ['uses' => 'PhaseController@create', 'as' => 'phases.create']);
	Route::put('phases', ['uses' => 'PhaseController@update', 'as' => 'phases.update']);
	Route::delete('phases/delete', ['uses' => 'PhaseController@destroy', 'as' => 'phases.destroy']);
	
	Route::post('phases/terminate{task_id?}', ['uses' => 'PhaseController@terminate', 'as' => 'phases.terminate']);
	Route::post('phases/activate', ['uses' => 'PhaseController@activate', 'as' => 'phases.activate']);
	Route::post('phases/privacy', ['uses' => 'PhaseController@privacy', 'as' => 'phases.privacy']);
	Route::post('phases/movePhase', ['uses' => 'PhaseController@movePhase', 'as' => 'phases.movePhase']);
	
	/*Tasks Routes*/
	Route::get('tasks', ['uses' => 'TaskController@index', 'as' => 'tâches']);
	Route::post('tasks/create/', ['uses' => 'TaskController@create', 'as' => 'tasks.create']);
	Route::post('tasks/createPublic', ['uses' => 'TaskController@createPublic', 'as' => 'tasks.createPublic']);
	Route::get('tasks/{id}/edit', ['uses' => 'TaskController@edit', 'as' => 'tasks.edit']);
	Route::get('tasks/{id}/show', ['uses' => 'TaskController@show', 'as' => 'tasks.show']);
	Route::put('tasks/update', ['uses' => 'TaskController@update', 'as' => 'tasks.update']);
	Route::delete('tasks/delete', ['uses' => 'TaskController@destroy', 'as' => 'tasks.destroy']);
	Route::post('tasks/copy', ['uses' => 'TaskController@copy', 'as' => 'tasks.copy']);
	Route::post('tasks/copyMultiTask', ['uses' => 'TaskController@copyMultiTask', 'as' => 'tasks.copyMultiTask']);
	Route::post('tasks/moveMultiTask', ['uses' => 'TaskController@moveMultiTask', 'as' => 'tasks.moveMultiTask']);
	Route::post('tasks/terminateMultiTask', ['uses' => 'TaskController@terminateMultiTask', 'as' => 'tasks.terminateMultiTask']);
	Route::post('tasks/terminateAll', ['uses' => 'TaskController@terminateAll', 'as' => 'tasks.terminateAll']);
	Route::post('tasks/activateAll', ['uses' => 'TaskController@activateAll', 'as' => 'tasks.activateAll']);
	
	Route::post('tasks/terminate/', ['uses' => 'TaskController@terminate', 'as' => 'tasks.terminate']);
	Route::post('tasks/activate/', ['uses' => 'TaskController@activate', 'as' => 'tasks.activate']);
	Route::post('tasks/milestone', ['uses' => 'TaskController@milestone', 'as' => 'tasks.milestone']);
	Route::get('tasks/getPhases/{activity_id}', ['uses' => 'TaskController@getPhases', 'as' => 'tasks.getPhases']);

//WDays routes
	Route::get('wdays', ['uses' => 'WorkdayController@index', 'as' => 'temps']);
	Route::get('activities/plan/wdays/show/{task_id}', ['uses' => 'WorkdayController@show', 'as' => 'wday.show']);
	Route::get('wdays/show/{task_id}', ['uses' => 'WorkdayController@show', 'as' => 'wday.show']);
	Route::post('wdays/create/', ['uses' => 'WorkdayController@create', 'as' => 'wday.create']);
	
	Route::put('wdays/update/', ['uses' => 'WorkdayController@update', 'as' => 'wday.update']);
	Route::delete('wdays/destroy/', ['uses' => 'WorkdayController@destroy', 'as' => 'wday.destroy']);
	Route::post('wdays/validate_user_all/', ['uses' => 'WorkdayController@validate_user_all', 'as' => 'wday.validate_user_all']);
	Route::post('wdays/deny_user_all/', ['uses' => 'WorkdayController@deny_user_all', 'as' => 'wday.deny_user_all']);
	Route::post('wdays/validate_all/', ['uses' => 'WorkdayController@validate_all', 'as' => 'wday.validate_all']);
	Route::post('wdays/deny_all/', ['uses' => 'WorkdayController@deny_all', 'as' => 'wday.deny_all']);
	Route::post('wdays/validate_wd/', ['uses' => 'WorkdayController@validate_wd', 'as' => 'wday.validate_wd']);
	Route::post('wdays/deny_wd/', ['uses' => 'WorkdayController@deny_wd', 'as' => 'wday.deny_wd']);
	Route::get('wdays/details_to_validate/{user_id}/{month}/{year}', ['uses' => 'WorkdayController@details_to_validate', 'as' => 'wday.details_to_validate']);
	Route::get('wdays/details_to_deny/{user_id}/{month}/{year}', ['uses' => 'WorkdayController@details_to_deny', 'as' => 'wday.details_to_deny']);

//Absences routes
	Route::get('absences', ['uses' => 'AbsenceController@index', 'as' => 'absences']);
	Route::get('absences/show/', ['uses' => 'AbsenceController@show', 'as' => 'absences.show']);
	Route::post('absences/create/', ['uses' => 'AbsenceController@create', 'as' => 'absences.create']);
	Route::put('absences/update/', ['uses' => 'AbsenceController@update', 'as' => 'absences.update']);
	Route::delete('absences/destroy/', ['uses' => 'AbsenceController@destroy', 'as' => 'absences.destroy']);
	
	Route::get('/info', ['uses' => 'HomeController@getTaskTypeHelp', 'as' => 'info']);

//Charges routes
	Route::get('charges', ['uses' => 'ChargeController@index', 'as' => 'charges']);
	Route::get('charges/details/{user_id}/{month}/{year}', ['uses' => 'ChargeController@details', 'as' => 'charges.details']);

//Users routes
	Route::get('users', ['uses' => 'UserController@index', 'as' => 'utilisateurs']);
	Route::get('users/show/', ['uses' => 'UserController@show', 'as' => 'users.show']);
	Route::get('users/getData/', ['uses' => 'UserController@getData', 'as' => 'users.data']);
	Route::post('users/create/', ['uses' => 'UserController@create', 'as' => 'users.create']);
	Route::put('users/update/', ['uses' => 'UserController@update', 'as' => 'users.update']);
	Route::delete('users/destroy/', ['uses' => 'UserController@destroy', 'as' => 'users.destroy']);
	Route::post('users/terminate/', ['uses' => 'UserController@terminate', 'as' => 'users.terminate']);
	Route::post('users/activate/', ['uses' => 'UserController@activate', 'as' => 'users.activate']);
	Route::post('users/settings/', ['uses' => 'UserController@settings', 'as' => 'users.settings']);
	Route::get('users/getServicesList/{user_department_id}', ['uses' => 'UserController@getServicesList', 'as' => 'users.getServicesList']);
