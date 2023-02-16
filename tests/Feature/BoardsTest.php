<?php

namespace Tests\Feature;

use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Foundation\Testing\WithFaker;
use Tests\TestCase;

class BoardsTest extends TestCase
{
    /**
     * A basic feature test example.
     *
     * @return void
     */
    public function testExample()
    {
        $response = $this->get('/');

        $response->assertStatus(200);
    }


    public function testResponseJsonForAddBoard()
    {
        $board = [
            "title" => "John Doe",
            "description" => "doe@example.com"
        ];

        $this->json('POST', 'api/boards', $board, ['Accept' => 'application/json'])
            ->assertStatus(200)
            ->assertJsonStructure([
                "responseCode",
                "responseMessage",
                "data"=> [
                    "title",
                    "description",
                    "status",
                    "updated_at",
                    "created_at",
                    "board_id"
                ]
            ]);
    }

    public function testResponseJsonForDeleteBoard()
    {
        $board = 3;

        $this->json('DELETE', 'api/boards/', $board, ['Accept' => 'application/json'])
            ->assertStatus(200)
            ->assertJsonStructure([
                "responseCode",
                "responseMessage",
                "data"=> [
                    "title",
                    "description",
                    "status",
                    "updated_at",
                    "created_at",
                    "board_id"
                ]
            ]);
    }
}
