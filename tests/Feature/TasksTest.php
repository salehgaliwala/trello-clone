<?php

namespace Tests\Feature;

use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Foundation\Testing\WithFaker;
use Tests\TestCase;

class TasksTest extends TestCase
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
    public function testResponseJsonForAddTask()
    {
        $task = [
            "title" => "John Doe",
            "description" => "doe@example.com",
            "board_id" => "3"
        ];

        $this->json('POST', 'api/tasks', $task, ['Accept' => 'application/json'])
            ->assertStatus(200)
            ->assertJsonStructure([
                "responseCode",
                "responseMessage",
                "data"]);
    }

    public function testResponseJsonForUpdateTask()
    {
        $task = [
            "title" => "Bin Doe",
            "description" => "doe@example.com",
            "board_id" => "3"
        ];
        $task_id = "27";

        $this->json('PATCH', 'api/tasks/'.$task_id, $task, ['Accept' => 'application/json'])
            ->assertStatus(200)
            ->assertJsonStructure([
                "responseCode",
                "responseMessage",
                "data"]);
    }

    public function testResponseJsonForDeleteTask()
    {
        $task = 3;
        $task_id = "27";
        $this->json('DELETE', 'api/tasks/'.$task_id, $task, ['Accept' => 'application/json'])
            ->assertStatus(200)
            ->assertJsonStructure([
                "responseCode",
                "responseMessage",
                "data"
            ]);
    }
}
