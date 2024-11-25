<?php

namespace Tests\Unit;

use Tests\TestCase;
use Illuminate\Support\Facades\Http;

class GeminiApiTest extends TestCase
{
    public function testCanMakeRequestToGeminiApi()
    {
        Http::fake([
            'https://api.gemini.com/*' => Http::response('{"message": "success"}'),
        ]);

        $response = $this->get('/gemini/test'); // Substitua por sua rota

        $response->assertStatus(200);
        $response->assertJson(['message' => 'success']);
    }
}