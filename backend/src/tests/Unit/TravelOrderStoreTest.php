<?php

namespace Tests\Unit;

use Tests\TestCase;
use Illuminate\Foundation\Testing\RefreshDatabase;
use App\Models\TravelOrder;
use App\Models\User;

class TravelOrderStoreTest extends TestCase
{
    use RefreshDatabase;

    public function testStore()
    {
        $user = User::factory()->create();
        $this->actingAs($user, 'api');

        $travelOrderData = [
            'status' => 'solicitado',
            'destino' => 'São Paulo',
            'data_ida' => '2025-02-10',
            'data_volta' => '2025-02-20',
            'user_id' => $user->id
        ];

        $response = $this->postJson('/api/travel-orders', $travelOrderData);

        $response->assertStatus(201)
                 ->assertJsonStructure([
                    'data' => [
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
                 ]);

        $this->assertDatabaseHas('travel_orders', [
            'status' => 'solicitado',
            'destino' => 'São Paulo',
            'data_ida' => '2025-02-10',
            'data_volta' => '2025-02-20',
            'user_id' => $user->id
        ]);
    }
}
