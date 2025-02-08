<?php

namespace Tests\Unit;

use Tests\TestCase;
use Illuminate\Foundation\Testing\RefreshDatabase;
use App\Models\TravelOrder;
use App\Models\User;

class TravelOrderShowTest extends TestCase
{
    use RefreshDatabase;

    public function testShow()
    {
        $user = User::factory()->create();
        $this->actingAs($user, 'api');

        $travelOrder = TravelOrder::factory()->create([
            'status' => 'solicitado',
            'destino' => 'São Paulo',
            'data_ida' => '2025-02-10',
            'data_volta' => '2025-02-20',
            'user_id' => $user->id
        ]);

        $response = $this->getJson('/api/travel-orders/' . $travelOrder->id);

        $response->assertStatus(200)
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
            'id' => $travelOrder->id,
            'status' => 'solicitado',
            'destino' => 'São Paulo',
            'data_ida' => '2025-02-10',
            'data_volta' => '2025-02-20',
            'user_id' => $user->id
        ]);
    }
}
