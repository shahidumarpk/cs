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

use App\Notifications\RecordingPublished;
use App\Notifications\NewLead;
use Illuminate\Support\Collection;

Route::get('/', function () {
    return view('home');
});
//Notification toMail TEST Route	//Working
Route::get('/testemail', function () {
    //return view('testemail');
	$user = \App\User::first();
	$lead = \App\Lead::first();
	$check = $user->notify(new RecordingPublished($user,$lead)); 
	if($check){
		echo "EMAIL SENT";
	}
});

//Notification toDatabase TEST Route	//Working
Route::get('/testdatabase', function () {
	$user = \App\User::find(20);
	$lead = \App\Lead::find(9);
	$user->notify(new NewLead($user,$lead)); 
});

Route::get('/notify', function () {
	$users = \App\User::all();
	$letter = collect(['title' => 'New Policy Update' , 'body' =>'We have update our TOS' ]);
	Notification::send($users, new NewLead($letter));
	echo "Notification sent to all users";
});

Route::get('/markAsRead', function () {
	Auth::user()->notifications->markAsRead();
	return redirect()->back();
})->name('markAsRead');

Route::get('/markAsUnRead', function () {
	Auth::user()->notifications->markAsUnRead();
	return redirect()->back();
})->name('markAsUnRead');

 Route::get('/markAsRead_NOT/{id}', 'UserController@markAsRead_NOT')->middleware('auth');

Auth::routes();
// Two Factor Authentication
Route::get('/otp', 'TwoFactorController@showTwoFactorForm');
Route::post('/otp', 'TwoFactorController@verifyTwoFactor');
Route::get('/home', 'HomeController@index')->name('home');

Route::get('/dashboard', ['as' => 'dashboard' , function () {
   return view('dashboard');
}])->middleware('auth');

Route::get('/changepassword', ['as' => 'changepassword' , function () {
    return view('changepassword');
 }])->middleware('auth');

/*  Route::get('/profile', ['as' => 'profile' , function () {
    return view('profile');
 }])->middleware('auth');
 */
Route::get('profile', 'UserController@profile')->middleware('auth');

 //Sub admins/staff
 Route::get('/resetpassword/{id}', 'UserController@resetPassword')->middleware('auth');
 Route::get('/admins/deactivate/{id}', 'UserController@deactivate')->middleware('auth');
 Route::get('/admins/active/{id}', 'UserController@active')->middleware('auth');
 Route::resource('admins', 'UserController')->middleware('auth');

 
 //Call logs
 Route::resource('calllogs', 'CallLogsController')->middleware('auth');
    Route::post('calllogs/search', 'CallLogsController@index')->middleware('auth')->name('calllogs.search');   
 
 
 