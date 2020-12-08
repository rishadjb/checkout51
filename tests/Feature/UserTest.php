<?php

namespace Tests\Feature;

use Tests\TestCase;
use Illuminate\Foundation\Testing\WithFaker;
use Illuminate\Foundation\Testing\RefreshDatabase;

class UserTest extends TestCase
{
    public function test_view_offers_page_loads()
    {
        $response = $this->get('/viewoffers/checkout51');

        $response->assertStatus(200);
    }

    public function test_edit_offers_page_loads()
    {
        $response = $this->get('/editoffers/checkout51');

        $response->assertStatus(200);
    }
}
