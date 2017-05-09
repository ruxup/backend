<?php

namespace App\Http\Controllers;

use App\Chat;
use App\Event;
use App\EventUser;
use App\InterestUser;
use App\Message;
use App\Rating;
use App\User;
use Illuminate\Http\Request;
use Illuminate\Database\Eloquent\ModelNotFoundException;
use Illuminate\Database\QueryException;
use Illuminate\Support\Facades\DB;
use DateTime;
use Validator;

class UserController extends Controller
{
    /**
     * @SWG\Get(
     *   path="/api/user/events/{id}",
     *   summary="List events where the user is a member of.",
     *   operationId="getEvents",
     *  tags={"events"},
     *  produces={"application/json"},
     *   @SWG\Parameter(
     *     name="id",
     *     in="path",
     *     description="User id",
     *     required=true,
     *     type="integer"
     *   ),
     *   @SWG\Response(response=200, description="Get events"),
     *   @SWG\Response(response=404, description="User_Not_Found")
     * )
     *
     */
    public function getEvents($id)
    {
        try {
            $user = User::findOrFail($id);
            return response(json_encode(array("data" => $user->events)), 200);
        } catch (ModelNotFoundException $exception) {
            return response(json_encode(array("error message" => "User_Not_Found")), 404);
        }
    }

    public function getEventsWhereOwner($id)
    {
        try {
            User::findOrFail($id);
        } catch (ModelNotFoundException $e) {
            return response("User_Not_Found", 404);
        }
        $arrayEvents = DB::table(config('constants.events_table'))->where('owner_id', $id)->get();
        if (count($arrayEvents) == 0) {
            return response("User is not an owner", 404);
        }
        return response(json_encode($arrayEvents), 200);

    }

    public function joinEvent($eventId, $userId)
    {
        try {
            $user = User::findOrFail($userId);
            $event = Event::findOrFail($eventId);

            $event->users()->attach($user, array('joined_at' => new DateTime(), 'active' => 1));
            if (is_null($event->owner_id)) {
                $event->owner_id = $user->id;
                $event->save();
            }
            return response('User ' . $user->name . ' has joined event ' . $event->name, 200);
        } catch (ModelNotFoundException $exception) {
            return response($exception->getModel() . ' not found', 404);
        } catch (QueryException $queryException) {
            return response('User already is a member of this event', 409);
        }

    }

    public function findEvent($type, Request $request)
    {
        $locationEvents = $this->getEventsByCriteria($request, $type);
        if (count($locationEvents) != 0) {
            return response()->json($locationEvents, 200);
        } else {
            return response()->json(['error' => 'No events found for selected criteria.'], 404);
        }
    }

    public function removeProfile($id)
    {
        try {
            $user = User::findOrFail($id);
            $user->delete();
            EventUser::where('user_id', $id)->delete();
            InterestUser::where('user_id', $id)->delete();
            Message::withTrashed()->where('owner_id', $id)->forceDelete();
            return response("User: " . $user->name . " has been removed", 200);
        } catch (ModelNotFoundException $exception) {
            return response("User is not active", 404);
        }
    }

    public function restoreProfile($id)
    {
        try {
            User::withTrashed()
                ->where('id', $id)
                ->restore();
            EventUser::where('user_id', $id)->restore();
            InterestUser::where('user_id', $id)->restore();
            $user = User::findOrFail($id);
            return response("User: " . $user->name . " has been restored", 200);
        } catch (\ErrorException $exception) {
            return response("User is active.", 404);
        } catch (ModelNotFoundException $exception) {
            return response("User not found", 404);
        }
    }

    public function changeOwner($id, Request $request)
    {
        try {
            $currentOwner = User::findOrFail($request->input('owner_id'));
            $user = User::findOrFail($request->input('user_id'));
            if ($currentOwner->id == $user->id) {
                return response('Current user is already the owner of this event.', 400);
            }
            $event = Event::findOrFail($id);
            if ($currentOwner->id != $event->owner_id) {
                return response('Access denied.', 403);
            }
            $flag = $this->checkIfUserIsMember($user, $event);
            if (!$flag) {
                return response('User to be owner is not member of this event.', 400);
            }
            $event->owner_id = $user->id;
            $event->save();
            return response('Event: ' . $event->name . " new owner's is: " . $user->name, 200);
        } catch (ModelNotFoundException $exception) {
            return response($exception->getModel() . ' not found.', 404);
        }

    }

    private function checkIfUserIsMember($user, $event)
    {
        $flag = false;
        $usersInEvent = EventUser::all()->where('event_id', $event->id);
        foreach ($usersInEvent as $item) {
            if ($item['user_id'] == $user->id) {
                $flag = true;
            }
        }
        return $flag;
    }

    private function getEventsByCriteria(Request $request, $searchType)
    {
        $location = $request->input('location');
        $startTime = $request->input('start_time');
        $endTime = $request->input('end_time');
        $category = $request->input('category');
        switch ($searchType) {
            case 'time':
                return DB::table(config('constants.events_table'))->whereBetween('start_time', [$startTime, $endTime])->get();
                break;
            case 'location':
                return DB::table(config('constants.events_table'))->where('location', $location)->get();
                break;
            case 'category':
                return DB::table(config('constants.events_table'))->where('category', $category)->get();
            default:
                return DB::table(config('constants.events_table'))->where('location', $location)->whereBetween('start_time', [$startTime, $endTime])->where('category', $category)->get();
                break;
        }
    }

    public function rate($id, Request $request)
    {
        try {
            $rater = User::findOrFail($id);
            $request->request->add(['rater_id' => $id]);
            $rating = $request->all();
            $rated = User::findOrFail($rating['ratee_id']);
            if ($rating['star'] < 1 || $rating['star'] > 5) {
                throw new ModelNotFoundException('Star');
            }
            $rateObj = Rating::where('rater_id', $rating['rater_id'])->where('ratee_id', $rating['ratee_id'])->first();
            if(is_null($rateObj)) {
                Rating::create($rating);
            }
            else
            {
                $rateObj->star = $rating['star'];
                $rateObj->save();
            }
            return response()->json(array(['message' => 'User ' . $rater->name . ' rated ' . $rated->name . ' with ' . $rating['star'] . ' stars.']), 200);
        } catch (ModelNotFoundException $exception) {
            return response()->json(array(['error' => $exception->getModel() . ' not found']), 404);
        } catch (QueryException $queryException) {
            return response()->json(array(['error' => $queryException->getMessage()]), 409);
        }
    }

    private function validateChat($chatData)
    {
        return Validator::make($chatData, [
            'receiver_id' => 'required|',
            'message' => 'required|min:1|max:255',
        ]);
    }

    public function chat($id, Request $request)
    {
        try {
            $sender = User::findOrFail($id);
            $request->request->add(['sender_id' => $id]);
            $chatData = $request->all();
            $validate = $this->validateChat($chatData);
            if ($validate->fails()) {
                return response()->json(array('error' => $validate->errors()), 400);
            }
            $receiver = User::findOrFail($chatData['receiver_id']);
            Chat::create($chatData);
            return response()->json(array('message' => 'Message has been successfully sent from ' . $sender->name . ' to ' . $receiver->name), 200);
        } catch (ModelNotFoundException $exception) {
            return response()->json(array('error' => $exception->getModel() . ' not found'), 403);
        }
    }
}
