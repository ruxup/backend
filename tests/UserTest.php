<?php

use Carbon\Carbon;
use Illuminate\Foundation\Testing\WithoutMiddleware;
use Illuminate\Foundation\Testing\DatabaseMigrations;
use Illuminate\Foundation\Testing\DatabaseTransactions;
use Illuminate\Support\Facades\Artisan;

class UserTest extends TestCase
{
    /**
     * A basic test example.
     *
     * @return void
     */

    use DatabaseTransactions;

    public function setUp()
    {
        parent::setUp(); // TODO: Change the autogenerated stub

        Artisan::call('migrate:refresh');
        Artisan::call('db:seed');
    }

    public function test_get_events()
    {
        $this->json('GET', "api/user/1/events")->seeStatusCode(200)->decodeResponseJson();
    }

    public function test_get_events_where_owner()
    {
        $this->json('GET', "api/user/2/owner")->seeStatusCode(200)->decodeResponseJson();
    }

    public function test_join_event()
    {
        $response = $this->call('GET', "api/event/25/5");
        $this->assertEquals('200 User Radu has joined event Ajax - PSV', $response->status() . ' ' . $response->getContent());
    }

}