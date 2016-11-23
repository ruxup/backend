<?php

namespace App\Http\Controllers\Auth;

use App\Http\Controllers\Controller;
use Illuminate\Foundation\Auth\AuthenticatesUsers;
use Illuminate\Http\Request;
use Tymon\JWTAuth\Exceptions\JWTException;
use Tymon\JWTAuth\Exceptions\TokenExpiredException;
use Tymon\JWTAuth\Exceptions\TokenInvalidException;
use Tymon\JWTAuth\Facades\JWTAuth;

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
    protected $redirectTo = '/home';

    /**
     * Create a new controller instance.
     *
     * @return void
     */
    public function __construct()
    {
        $this->middleware('guest', ['except' => 'logout']);
    }

    //get token for user
    public function login(Request $request)
    {
        $credentials = $request->only('email', 'password');

        try {
            //attempt to verify credentials and create token for user
            if(!$token = JWTAuth::attempt($credentials))
            {
                return response('invalid_credentials', 401);
            }
        }
        catch (JWTException $exc)
        {
            //token wrong
            return response('could_not_create_token', 500);
        }

        return response()->json(compact('token'));
    }

    //get the user data
    public function getAuthenticatedUser()
    {
        try {
            if(!isset($_SERVER['HTTP_TOKEN']))
            {
                return response("token_not_set", 401);
            }

            if (! $user = JWTAuth::setToken($_SERVER['HTTP_TOKEN'])->authenticate()) {
                return response("user_not_found", 404);
            }

        } catch (TokenExpiredException $e) {

            return response('token_expired', $e->getStatusCode());

        } catch (TokenInvalidException $e) {

            return response('token_invalid', $e->getStatusCode());

        } catch (JWTException $e) {

            return response('token_absent', $e->getStatusCode());
        }

        // the token is valid and we have found the user via the sub claim
        return response()->json(compact('user'));
    }

    public function logout()
    {
        try {

            if(isset($_SERVER['HTTP_TOKEN']))
            {
                JWTAuth::setToken($_SERVER['HTTP_TOKEN'])->invalidate();
                return response("user_logged_out", 200);
            }

        } catch (TokenExpiredException $e) {

            return response("token_expired", $e->getStatusCode());

        } catch (TokenInvalidException $e) {

            return response('token_invalid', $e->getStatusCode());

        } catch (JWTException $e) {

            return response('token_absent', $e->getStatusCode());

        }

        // the token is valid and we have found the user via the sub claim
        return response('token_not_found', 404);
    }
}
