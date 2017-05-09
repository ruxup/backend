<?php

use Illuminate\Database\Seeder;

class EventUserLinkSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        DB::statement('SET FOREIGN_KEY_CHECKS=0');
        DB::table(config('constants.eventuser_table'))->truncate();

        $eventsUser = [
            ['user_id' => 1, 'event_id' => 24, 'joined_at' => new DateTime()],
            ['user_id' => 4, 'event_id' => 24, 'joined_at' => new DateTime()],
            ['user_id' => 2, 'event_id' => 25, 'joined_at' => new DateTime()],
        ];

        DB::table(config('constants.eventuser_table'))->insert($eventsUser);
    }
}
