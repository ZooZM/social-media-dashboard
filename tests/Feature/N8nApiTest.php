<?php

namespace Tests\Feature;

use App\Models\Client;
use App\Models\InteractionLog;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Foundation\Testing\WithFaker;
use Tests\TestCase;

class N8nApiTest extends TestCase
{
    use RefreshDatabase; // Use if DB reset is needed, otherwise omit if persisting data

    protected function setUp(): void
    {
        parent::setUp();
        // Mock the config for token
        config(['app.api_token' => 'test-token']);
    }

    public function test_get_all_clients()
    {
        $mockService = $this->mock(\App\Services\ClientService::class, function ($mock) {
            $mock->shouldReceive('getAllClients')
                ->once()
                ->andReturn([
                    [
                        'id' => 'client_1',
                        'name' => 'Client A',
                        'business_category' => 'Tech',
                        'services' => [],
                        'business_info' => [],
                        'ai_config' => [],
                    ],
                    [
                        'id' => 'client_2',
                        'name' => 'Client B',
                        'business_category' => 'Retail',
                        'services' => [],
                        'business_info' => [],
                        'ai_config' => [],
                    ]
                ]);
        });

        $response = $this->withHeader('Authorization', 'Bearer test-token')
                         ->getJson('/api/n8n/clients');

        $response->assertStatus(200)
                 ->assertJsonCount(2, 'data')
                 ->assertJsonStructure([
                     'success',
                     'data' => [
                         '*' => ['id', 'name', 'business_category']
                     ]
                 ]);
    }

    public function test_get_interaction_logs()
    {
        $mockService = $this->mock(\App\Services\InteractionLogService::class, function ($mock) {
            $mock->shouldReceive('getLatestLogs')
                ->once()
                ->andReturn(collect([
                    [
                        'client_id' => 'client_1',
                        'message' => 'Hello',
                        'response' => 'Hi',
                        'platform' => 'whatsapp',
                    ]
                ]));
        });

        $response = $this->withHeader('Authorization', 'Bearer test-token')
                         ->getJson('/api/n8n/logs');

        $response->assertStatus(200)
                 ->assertJsonStructure([
                     'success',
                     'data' => [
                         '*' => ['client_id', 'message', 'response', 'platform']
                     ]
                 ]);
    }

    public function test_get_statistics()
    {
        $mockService = $this->mock(\App\Services\InteractionLogService::class, function ($mock) {
            $mock->shouldReceive('getStatistics')
                ->once()
                ->andReturn([
                    'total_messages' => 3,
                    'messages_today' => 1,
                    'active_platforms' => 2,
                    'platform_breakdown' => [
                        'whatsapp' => 2,
                        'telegram' => 1
                    ]
                ]);
        });

        $response = $this->withHeader('Authorization', 'Bearer test-token')
                         ->getJson('/api/n8n/stats');

        $response->assertStatus(200)
                 ->assertJsonFragment(['total_messages' => 3])
                 ->assertJsonFragment(['active_platforms' => 2]);
    }
}
