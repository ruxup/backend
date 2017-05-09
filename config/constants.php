<?php
/**
 * Created by PhpStorm.
 * User: Radu
 * Date: 4/15/2017
 * Time: 20:08
 */

return [

    /*
|--------------------------------------------------------------------------
| User Defined Variables
|--------------------------------------------------------------------------
|
| This is a set of variables that are made specific to this application
| that are better placed here rather than in .env file.
| Use config('your_key') to get the values.
|
*/

    'events_table' => env('EVENTS_TABLE', 'events'),
    'eventuser_table' => env('EVENTUSER_TABLE', 'eventuser'),
    'interests_table' => env('INTERESTS_TABLE', 'interests'),
    'interestuser_table' => env('INTERESTUSER_TABLE', 'interestuser'),
    'messages_table' => env('MESSAGES_TABLE','messages'),
    'rating_table' => env('RATING_TABLE', 'rating'),
    'users_table' => env('USERS_TABLE', 'users'),
    'chat_table' => env('CHATS_TABLE', 'chats'),
]

?>