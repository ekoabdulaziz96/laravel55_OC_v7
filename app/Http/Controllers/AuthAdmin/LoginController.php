<?php

namespace App\Http\Controllers\AuthAdmin;

use App\Http\Controllers\Controller;
use Illuminate\Validation\Rule;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
class LoginController extends Controller
{
    /*
    |--------------------------------------------------------------------------
    | Login Controller
    |--------------------------------------------------------------------------
    |
    | This controller handles authenticating users for the application and
    | redirecting them to your home screen. The controller uses a trait
    | to conveniently provide its functionality to your applications.
    |
    */




    /**
     * Create a new controller instance.
     *
     * @return void
     */
    public function __construct()
    {
        $this->middleware('guest:admin')->except('logout','logoutUser');
    }

    protected function validateLogin(Request $request)
    {
        $this->validate($request, [           
            $this->username() => ['required','string',
            Rule::exists('users')->where(function($query){
                $query->where('active',true);
            })
        ],
            'password' => 'required|string',
        ],[
            $this->username().'.exists'=>'the selected email invalid or you need to active your account'
        ]);
    }
   public function showLoginForm()
    {
        return view('authAdmin.login');
    }

        /**
     * Handle a login request to the application.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\RedirectResponse|\Illuminate\Http\Response|\Illuminate\Http\JsonResponse
     */
    public function login(Request $request)
    {
        $this->validate($request,[
            'email' =>'required|email',
            'password' => 'required|min:6'
        ]);

        $credential = [
            'email' => $request->email,
            'password' => $request->password
        ];

        if(Auth::guard('admin')->attempt($credential,$request->member)){
            return redirect()->intended(route('admin.home'));
        }

        return redirect()->back()->withInput($request->only('email','remember'));
    }

   public function logoutUser()
    {
        Auth::guard('admin')->logout();
        return redirect('/');
    }
}
