<?php

use Illuminate\Database\Seeder;

class EventSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        DB::statement('SET FOREIGN_KEY_CHECKS=0');
        DB::table(config('constants.events_table'))->truncate();

        $events = [
            ['id' => 24, 'name' => 'PSV - Ajax', 'location' => 'Eindhoven', 'start_time' => new DateTime(), 'end_time' => date("Y-m-d", strtotime("+1 week")), 'category' => 'Sport', 'owner_id' => 1],
            ['id' => 25, 'name' => 'Ajax - PSV', 'location' => 'Amsterdam', 'start_time' => new DateTime(), 'end_time' => date("Y-m-d", strtotime("+1 week")), 'category' => 'Sport', 'owner_id' => 2],
        ];

        DB::table(config('constants.events_table'))->insert($events);
    }
}
