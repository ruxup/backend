<?php

namespace App\Http\Controllers;

use App\Message;
use Carbon\Carbon;
use DateTime;
use Illuminate\Database\Eloquent\ModelNotFoundException;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Validator;

class MessageController extends Controller
{
    protected function validator(array $data)
    {
        return Validator::make($data, [
            'description' => 'required|max:255',
            'time_sent' => 'required|date_format:Y-m-d H:i:s',
            'event_id' => 'required|integer',
            'owner_id' => 'required|integer',
        ]);
    }

    public function comment(Request $request)
    {
        $messageData = $request->only('description', 'time_sent', 'owner_id', 'event_id');

        $owner = $messageData['owner_id'];
        $event = $messageData['event_id'];

        $eventuser = DB::table(config('constants.eventuser_table'))->where('user_id', $owner)->where('event_id', $event)->get();

        if (count($eventuser) != 0) {

            $validate = $this->validator($messageData);
            if ($validate->fails()) {
                return response($validate->errors(), 417);
            } else {
                Message::create($messageData);
                return response(json_encode('Comment added from user with id ' . $owner . ' to event with id ' . $event), 200);
            }
        } else {
            return response('Comment could not be added. User not member of the event.', 404);
        }

    }

    public function delete($id, $owner_id)
    {
        try {
            $message = Message::findOrFail($id);
            if ($message->owner_id == $owner_id) {
                $message->delete();
                return response("Message has been removed", 200);
            }
            return response("The current user is not the sender of this message", 403);
        } catch (ModelNotFoundException $exception) {
            return response('Message not found', 404);
        }
    }

    public function edit(Request $request, $id, $owner_id)
    {
        try {
            $message = Message::findOrFail($id);
            if ($message->owner_id == $owner_id) {
                $newContent = $request->input('content');
                if(is_null($newContent))
                {
                    return response('No new content has been found', 404);
                }
                $message->description = (string)$newContent;
                $message->time_sent = Carbon::now();
                $message->save();
                return response("Message has been successfully edited", 200);
            }
            return response("The current user is not the sender of this message", 403);
        } catch (ModelNotFoundException $exception) {
            return response('Message not found', 404);
        }
    }
}

