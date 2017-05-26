<?php

namespace App\Http\Controllers\Auth;

use App\Http\Controllers\Controller;
use App\User;
use function foo\func;
use Illuminate\Foundation\Auth\SendsPasswordResetEmails;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Mail;
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
        $email = $request->input('email');

        if ($validator->fails()) {
            return response($validator->failed(), 417);
        }
        $user = User::where('email', $email)->first();
        if (!$user) {
            return response()->json('User not found', 400);
        }
        $token = $this->broker()->createToken($user);

        $message = $this->CreateMessage($token, $email);
        Mail::raw($message, function ($message) use ($email){
            $message->from('ruxup@app', 'Ruxup');

            $message->to($email)->subject('Reset link');
        });
        return response()->json(compact('token'), 200);
    }

    private function CreateMessage($token, $email)
    {
        $message = "Hello, \n\n" . "Click this link to reset your password! \n\n\n" . "Greetings from Ruxup team.";
        return $message;
    }
}
