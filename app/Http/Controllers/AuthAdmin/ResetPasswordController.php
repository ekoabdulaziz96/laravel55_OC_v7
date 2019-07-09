<?php

namespace App\Http\Controllers\AuthAdmin;

use App\Http\Controllers\Controller;
use Illuminate\Foundation\Auth\ResetsPasswords;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Password;
use Illuminate\Support\Facades\Auth;

class ResetPasswordController extends Controller
{
    /*
    |--------------------------------------------------------------------------
    | Password Reset Controller
    |--------------------------------------------------------------------------
    |
    | This controller is responsible for handling password reset requests
    | and uses a simple trait to include this behavior. You're free to
    | explore this trait and override any methods you wish to tweak.
    |
    */

    use ResetsPasswords;

    /**
     * Where to redirect users after resetting their password.
     *
     * @var string
     */
    protected $redirectTo = '/admin';

    /**
     * Create a new controller instance.
     *
     * @return void
     */
    public function __construct()
    {
        $this->middleware('guest:admin');
    }

        protected function resetPassword($user, $password)
    {
        $user->password = bcrypt($password);

        $user->setRememberToken(str_random(60));

        $user->save();

       if($user->active){
            $this->guard()->login($user);
       }
    }

    public function guard(){
        return Auth::guard('admin');
    }
    public function broker(){
        return Password::broker('admins');
    }

    public function showResetForm(Request $request, $token=null){
        return view('authAdmin.passwords.reset')->with(['token'=>$token,'email'=>$request->email]);
    }

        /**
     * Get the response for a successful password reset.
     *
     * @param  string  $response
     * @return \Illuminate\Http\RedirectResponse|\Illuminate\Http\JsonResponse
     */
    protected function sendResetResponse($response)
    {
        return redirect()->route('login')
                            ->withSuccess('your password hass been reset successful');
    }
}
