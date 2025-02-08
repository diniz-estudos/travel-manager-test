<?php

namespace Tests\Unit;

use Tests\TestCase;
use Illuminate\Foundation\Testing\RefreshDatabase;
use App\Models\TravelOrder;
use App\Models\User;

class TravelOrderIndexTest extends TestCase
{
    use RefreshDatabase;

    public function testIndex()
    {
        $user = User::factory()->create();
        $this->actingAs($user, 'api');

        TravelOrder::factory()->count(5)->create(['user_id' => $user->id]);

        $response = $this->getJson('/api/travel-orders');

        $response->assertStatus(200)
                 ->assertJsonStructure([
                    'data' => [
                        '*' => [
                            'id',
                            'status',
                            'destino',
                            'data_ida',
                            'data_volta',
                            'user' => [
                                'id',
                                'name',
                                'email'
                            ],
                            'created_at',
                            'updated_at'
                        ]
                    ]
                 ]);
    }
}
