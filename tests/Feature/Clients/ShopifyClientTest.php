<?php

namespace Tests\Feature\Clients;

use App\Models\Store;
use Tests\TestCase;
use Illuminate\Foundation\Testing\WithFaker;
use Illuminate\Foundation\Testing\RefreshDatabase;

class ShopifyClientTest extends TestCase
{
    /**
     * A basic feature test example.
     */
    public function test_example(): void
    {
        $this->markTestSkipped();
        $store = Store::factory()->create(['name' => '', 'access_token' => '']);
    }
}
