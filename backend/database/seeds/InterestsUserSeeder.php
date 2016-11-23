<?php

use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;

class InterestsUserSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        DB::statement('SET FOREIGN_KEY_CHECKS=0');
        DB::table('interestuser')->truncate();

        $interestsUser = [
            ['user_id' => 4, 'interest_id' => 1],
            ['user_id' => 4, 'interest_id' => 2],
            ['user_id' => 14, 'interest_id' => 3],
            ['user_id' => 14, 'interest_id' => 4],
            ['user_id' => 24, 'interest_id' => 4],
            ['user_id' => 24, 'interest_id' => 5],
            ['user_id' => 34, 'interest_id' => 6],
            ['user_id' => 34, 'interest_id' => 3],
            ['user_id' => 44, 'interest_id' => 7],
        ];

        DB::table('interestuser')->insert($interestsUser);
    }
}
