<?php

namespace App\Http\Controllers;

use App\Interest;
use App\InterestUser;
use App\User;
use Illuminate\Database\Eloquent\ModelNotFoundException;
use Illuminate\Database\QueryException;
use Illuminate\Http\Request;

class InterestsController extends Controller
{
    public function add(Request $request)
    {
        try {
            $userId = $request->input('user_id');
            $interestId = $request->input('interest_id');
            $user = User::findOrFail($userId);
            $interest = Interest::findOrFail($interestId);

            InterestUser::create(['user_id' => $userId, 'interest_id' => $interestId]);
            return response('Interest: '. $interest->name .' linked to user: ' . $user->name, 200);
        }
        catch (ModelNotFoundException $exception)
        {
            return response($exception->getModel() . ' Not found.', 404);
        }
        catch (QueryException $exception)
        {
            return response($exception->getMessage(), 403);
        }

    }

    public function delete($userId, $interestId)
    {
        $interest = InterestUser::withTrashed()->where('user_id', $userId)->where('interest_id', $interestId)->first();
        if (is_null($interest)) {
            return response('(user,interest) pair do not match', 404);
        }
        InterestUser::withTrashed()->where('user_id', $userId)->where('interest_id', $interestId)->forceDelete();
        return response("Interest has been removed", 200);
    }
}
