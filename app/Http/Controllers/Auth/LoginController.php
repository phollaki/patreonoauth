<?php

namespace App\Http\Controllers\Auth;

use App\Http\Controllers\Controller;
use App\Providers\RouteServiceProvider;
use Illuminate\Foundation\Auth\AuthenticatesUsers;
use Auth;
use Hash;
use Socialite;
use Str;
use App\User;

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

    /**
     * Create a new controller instance.
     *
     * @return void
     */
    public function __construct()
    {
        $this->middleware('guest')->except('logout');
    }

    public function patreon()
    {
        return Socialite::driver('patreon')->redirect();
    }

    public function patreonRedirect()
    {
        $user = Socialite::driver('patreon')->stateless()->user();

        $user = User::updateOrCreate([
            'email' => $user->email
        ], [
            'name' => $user->name,
            'patreon_id' => $user->id,
            'password'=> Hash::make(Str::random(24))
        ]);

        Auth::login($user, true);

        return redirect('/home');
    }
}
