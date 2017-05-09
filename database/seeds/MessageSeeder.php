<?php

use Illuminate\Database\Seeder;

class MessageSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        DB::statement('SET FOREIGN_KEY_CHECKS=0');
        DB::table(config('constants.messages_table'))->truncate();

        $messages = [
            ['content' => 'First message', 'time_sent' => new DateTime(), 'owner_id' => 1, 'event_id' => 24],
            ['content' => 'Second message', 'time_sent' => new DateTime(), 'owner_id' => 4, 'event_id' => 24],
        ];

        DB::table(config('constants.messages_table'))->insert($messages);
    }
}
