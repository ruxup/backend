<?php

namespace App\Http\Controllers\Auth;

use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use Auth;
use Image;
use Validator;
use Illuminate\Foundation\Auth\User;

class EditController extends Controller
{
    protected function validator(array $data)
    {
        return Validator::make($data, [
            'name' => 'max:255',
            'email' => 'email|max:255|unique:users',
            'password' => 'min:6|confirmed',
            'nationality' => 'max:30',
            'bio' => 'max:255',
            'location' => 'max:50',
            'phone' => 'regex:/(\+)[0-9]{9,}/'
        ]);
    }

    private function checkIfNull(&$userData, $item)
    {
        if (is_null($userData[$item])) {
            unset($userData[$item]);
        }
    }

    public function UpdateProfile(Request $request, $id)
    {
        $userData = $request->only('name', 'email', 'password', 'nationality', 'bio', 'location', 'profile_pic', 'phone');
        $this->checkIfNull($userData, 'password');
        $this->checkIfNull($userData, 'email');

        $validate = $this->validator($userData);
        if ($validate->fails()) {
            return response()->json($validate->errors(), 400);
        }
        $user = User::find($id);
        if (is_null($user)) {
            return response()->json(["error" => "User not found"], 404);
        }

        //still need to think about this
        if (!is_null($userData['profile_pic'])) {
            $image = $request->file('image');
            $upload = 'img/posts/';
            $filename = time() . $image->getClientOriginalName();
            move_uploaded_file($image->getPathname(), $upload . $filename);
            $user->profile_pic = $filename;
        }

        if (!is_null($userData['name'])) {
            $user->name = (string)$userData['name'];
        }
        if (in_array('email', $userData)) {
            $user->email = (string)$userData['email'];
        }
        if (in_array('password', $userData)) {
            $user->password = (string)$userData['password'];
        }
        if (!is_null($userData['nationality'])) {
            $user->nationality = (string)$userData['nationality'];
        }
        if (!is_null($userData['bio'])) {
            $user->bio = $userData['bio'];
        }
        if (!is_null($userData['location'])) {
            $user->location = (string)$userData['location'];
        }
        if (!is_null($userData['phone'])) {
            $user->phone = (string)$userData['phone'];
        }
        $user->save();

        return response()->json(["message" => "Profile was successfully updated"], 200);
    }
}
