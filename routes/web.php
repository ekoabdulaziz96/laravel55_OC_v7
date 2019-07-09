<?php
use Illuminate\Http\Request;
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

Route::get('/', function () {
    return view('welcome');
});

Route::resource('contact', 'ContactController', [
	'except' => ['create']
]);
Route::get('api/contact', 'ContactController@apiContact')->name('api.contact');

Route::get('/exportpdf', 'ContactController@exportPDF')->name('contact.exportPDF');

Route::get('/exportexcel', 'ContactController@exportExcel')->name('contact.exportExcel');
Route::post('/importexcel', 'ContactController@importExcel')->name('contact.importExcel');

Route::get('/pdf', function(){
    $pdf = PDF::loadHTML('<h1>Test</h1>');
    return $pdf->stream();
});
// Route::resource('contact','ContactController');
// Route::get('api.contact','ContactController@apiContact')->name('api.contact');

Auth::routes();
Route::get('auth/activate','Auth\ActivationController@activate')->name('auth.activate');
Route::get('auth/activate/resend','Auth\ActivationResendController@showResendForm')->name('auth.activate.resend');
Route::post('auth/activate/resend','Auth\ActivationResendController@resend');


Route::get('/home', 'HomeController@index')->name('home');
Route::get('/user/logout', 'HomeController@logoutUser')->name('user.logout');

Route::prefix('admin')->group(function() {
   Route::get('/', 'AdminController@index')->name('admin.home');
    Route::get('/login', 'AuthAdmin\LoginController@showLoginForm')->name('admin.login');
    Route::post('/login', 'AuthAdmin\LoginController@login')->name('admin.login.submit');
    Route::get('/logout', 'AuthAdmin\LoginController@logoutUser')->name('admin.logout');  
    Route::get('/password/reset', 'AuthAdmin\ForgotPasswordController@showLinkRequestForm')->name('admin.password.request');   
    Route::post('/password/email', 'AuthAdmin\ForgotPasswordController@sendResetLinkEmail')->name('admin.password.email');   
    Route::get('/password/reset/{token}', 'AuthAdmin\ResetPasswordController@showResetForm')->name('admin.password.reset');
     Route::post('/password/reset', 'AuthAdmin\ResetPasswordController@reset');
});

//export excel
 Route::get('/search', function (Request $request) {
    // dd($request->search);
    $result = App\Contact::search($request->search)->get();
    // dd($result);
    return view('result', compact('result'));
});