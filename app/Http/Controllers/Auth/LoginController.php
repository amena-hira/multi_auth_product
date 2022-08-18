<?php

namespace App\Http\Controllers\Auth;

use App\Http\Controllers\Controller;
use App\Providers\RouteServiceProvider;
use Illuminate\Foundation\Auth\AuthenticatesUsers;
use Illuminate\Http\Request;

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

    use AuthenticatesUsers;

    /**
     * Where to redirect users after login.
     *
     * @var string
     */
    protected $redirectTo = RouteServiceProvider::HOME;
    protected function redirectTo(){
        if (Auth()->user()->role == 1) {
            return route('admin.home');
        }
        elseif (Auth()->user()->role == 2) {
            return route('home');
        }
        elseif (Auth()->user()->role == 3) {
            return route('seller.home');
        }
        
        
    }

    /**
     * Create a new controller instance.
     *
     * @return void
     */
    public function __construct()
    {
        $this->middleware('guest')->except('logout');
    }
    protected function credentials(Request $request)
    {
        if(is_numeric($request->get('email'))){
            return ['phone'=>$request->get('email'),'password'=>$request->get('password')];
        }
        return $request->only($this->username(), 'password');
    }

    public function login(Request $request)
    {
        $input = $request->all();
        $this->validate($request,[
            'email'=>'required|email',
            'password'=>'required'
        ]);

        if ( auth()->attempt(array('email'=>$input['email'], 'password'=>$input['password']))){
            if (auth()->user()->role == 1) {
                return redirect()->route('admin.home');
            }
            elseif (auth()->user()->role == 2) {
                return redirect()->route('home');
            }
            elseif (auth()->user()->role == 3) {
                return redirect()->route('seller.home');
            }
            

        }else{
            ;
            return redirect()->route('login')->with(['email' => ['Email and/or password invalid.']])->withInput();
            
        }
    }
}
