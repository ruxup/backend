<?php

use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;

class InterestsSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        DB::statement('SET FOREIGN_KEY_CHECKS=0');
        DB::table(config('constants.interests_table'))->truncate();

        $interests = [
            ['id' => 1, 'name' => 'Sports', 'created_at' => new Datetime],
            ['id' => 2, 'name' => 'Travel', 'created_at' => new Datetime],
            ['id' => 3, 'name' => 'Hiking', 'created_at' => new Datetime],
            ['id' => 4, 'name' => 'Party', 'created_at' => new Datetime],
            ['id' => 5, 'name' => 'Meet people', 'created_at' => new Datetime],
            ['id' => 6, 'name' => 'Adventure', 'created_at' => new Datetime],
            ['id' => 7, 'name' => 'Camping', 'created_at' => new Datetime],
        ];

        DB::table(config('constants.interests_table'))->insert($interests);
    }
}
