<?php
namespace Tests\Feature;

use Tests\TestCase;
use App\Models\User;
use App\Models\TravelOrder;
use Illuminate\Foundation\Testing\RefreshDatabase;

class TravelOrderCancelTest extends TestCase
{
    use RefreshDatabase;

    public function testCancel()
    {
        $user = User::factory()->create();
        $this->actingAs($user, 'api');

        $travelOrder = TravelOrder::factory()->create([
            'status' => 'aprovado',
            'destino' => 'São Paulo',
            'data_ida' => '2025-02-10',
            'data_volta' => '2025-02-20',
            'user_id' => $user->id
        ]);

        $response = $this->deleteJson('/api/travel-orders/' . $travelOrder->id . '/cancel');

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
            'status' => 'cancelado'
        ]);
    }
}
