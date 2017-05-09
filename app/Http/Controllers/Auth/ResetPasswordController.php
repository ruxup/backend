<?php

namespace App\Http\Controllers\Auth;

use App\Http\Controllers\Controller;
use Illuminate\Foundation\Auth\ResetsPasswords;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Password;
use Psy\Util\Json;

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

    public function reset(Request $request)
    {

        $validator = $this->validate($request, $this->rules(), $this->validationErrorMessages());
        if ($validator->fails()) {
            return response($validator->failed(), 417);
        }

        $response = $this->broker()->reset(
            $this->credentials($request), function ($user, $password) {
            $this->resetPassword($user, $password);
        });

        if ($response == Password::PASSWORD_RESET) {
            return json_encode(['message' => trans($response), 'email' => $request->input('email')]);
        } else {
            return json_encode(['message' => 'Issue reseting password.', 'email' => $request->input('email') , 'errors' => trans($response)]);
        }

    }

}
