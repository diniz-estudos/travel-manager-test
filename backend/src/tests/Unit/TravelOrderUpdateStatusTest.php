<?php

namespace Tests\Feature;

use Tests\TestCase;
use App\Models\User;
use App\Models\TravelOrder;
use Illuminate\Foundation\Testing\RefreshDatabase;

class TravelOrderUpdateStatusTest extends TestCase
{
    use RefreshDatabase;

    public function testUpdateStatus()
    {
        $user = User::factory()->create();
        $anotherUser = User::factory()->create();
        $this->actingAs($user, 'api');

        $travelOrder = TravelOrder::factory()->create([
            'status' => 'solicitado',
            'destino' => 'São Paulo',
            'data_ida' => '2025-02-10',
            'data_volta' => '2025-02-20',
            'user_id' => $anotherUser->id
        ]);

        $response = $this->putJson('/api/travel-orders/' . $travelOrder->id . '/status', [
            'status' => 'aprovado'
        ]);

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
            'status' => 'aprovado',
            'destino' => 'São Paulo',
            'data_ida' => '2025-02-10',
            'data_volta' => '2025-02-20',
            'user_id' => $anotherUser->id
        ]);
    }

    public function testUserCannotUpdateOwnOrderStatus()
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

        $response = $this->putJson('/api/travel-orders/' . $travelOrder->id .' /status', [
            'status' => 'aprovado'
        ]);

        $response->assertStatus(403)
                 ->assertJson([
                    'error' => 'Você não pode alterar o status do seu próprio pedido'
                 ]);

        $this->assertDatabaseHas('travel_orders', [
            'id' => $travelOrder->id,
            'status' => 'solicitado', // status should remain unchanged
            'destino' => 'São Paulo',
            'data_ida' => '2025-02-10',
            'data_volta' => '2025-02-20',
            'user_id' => $user->id
        ]);
    }
}
