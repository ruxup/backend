<?php

namespace App\Http\Controllers\Auth;

use App\Http\Controllers\Controller;
use App\User;
use Illuminate\Foundation\Auth\SendsPasswordResetEmails;
use Illuminate\Http\Request;
use Psy\Util\Json;
use Validator;

class ForgotPasswordController extends Controller
{
    /*
    |--------------------------------------------------------------------------
    | Password Reset Controller
    |--------------------------------------------------------------------------
    |
    | This controller is responsible for handling password reset emails and
    | includes a trait which assists in sending these notifications from
    | your application to your users. Feel free to explore this trait.
    |
    */

    use SendsPasswordResetEmails;

    /**
     * Create a new controller instance.
     *
     * @return void
     */
    public function __construct()
    {
        $this->middleware('guest');
    }

    public function validate(Request $request, array $rules, array $messages = [], array $customAttributes = [])
    {
        $validator = $this->getValidationFactory()->make($request->all(), $rules, $messages, $customAttributes);
        return $validator;
    }

    public function getResetToken(Request $request)
    {
        $validator = $this->validate($request, ['email' => 'required|email']);

        if ($validator->fails()) {
            return response($validator->failed(), 417);
        }
        $user = User::where('email', $request->input('email'))->first();
        if (!$user) {
            return response()->json('User not found', 400);
        }
        $token = $this->broker()->createToken($user);
        return response()->json(compact('token'), 200);
    }
}
