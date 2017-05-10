<?php

use Illuminate\Database\Seeder;
use Faker\Factory as Faker;

class UserSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        DB::table(config('constants.users_table'))->truncate();

        $faker = Faker::create();
        $users = [];

        foreach(range(1,4) as $index)
        {
            $users[] = [
                'id' => $index,
                'name' => $faker->name,
                'email' => $faker->email,
                'password' => bcrypt('123456'),
            ];
        }

        array_push($users, ['id' => 5, 'name' => 'Radu', 'email' => 'radu.stoica1994@gmail.com', 'password' => bcrypt('123456')]);

        DB::table(config('constants.users_table'))->insert($users);
    }
}
