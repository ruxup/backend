<?php

use Illuminate\Database\Seeder;

class DatabaseSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        $this->call('InterestsSeeder');
        $this->call('UserSeeder');
        $this->call('InterestsUserSeeder');
        $this->call('EventSeeder');
        $this->call('EventUserLinkSeeder');
        $this->call('MessageSeeder');
    }
}
