<?php

namespace Tests\Feature;

use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Foundation\Testing\WithFaker;
use Illuminate\Support\Facades\Log;
use Tests\TestCase;

class TodoTest extends TestCase
{
    /**
     * A basic feature test example.
     */
    public function test_fetch_all_todos(): void
    {
        $response = $this->get('/api/v1/todos');

        $response->assertSuccessful();
        $response->assertOk();
        $response->assertJsonIsObject();
        $response->assertJsonStructure(['data' => ['*' => ['id', 'title', 'completed', 'created_at', 'updated_at']], 'message']);
        $response->assertSeeText('Todos fetched!');
    }


    public function test_todo_crud(): void
    {
        $createData = ['title' => 'Test Todo'];
        $putData = ['title' => 'Test Todo updated', 'completed' => true];
        $patchData = ['title' => 'Test patch Todo'];

        // Create
        $response = $this->post('/api/v1/todos', $createData);

        $response->assertSuccessful();
        $response->assertCreated();
        $response->assertJsonIsObject();
        $response->assertJsonStructure(['data' =>  ['id', 'title', 'completed', 'created_at', 'updated_at'], 'message']);
        $response->assertSeeText('Todo created!');

        $id = $response->json('data.id');

        // GET
        $response = $this->get("/api/v1/todos/$id");

        $response->assertSuccessful();
        $response->assertOk();
        $response->assertJsonIsObject();
        $response->assertJsonStructure(['data' =>  ['id', 'title', 'completed', 'created_at', 'updated_at'], 'message']);
        $response->assertSeeText('Todo fetched!');

        // PUT
        $response = $this->put("/api/v1/todos/$id", $putData);

        $response->assertSuccessful();
        $response->assertNoContent();

        // PATCH
        $response = $this->patch("/api/v1/todos/$id", $patchData);

        $response->assertSuccessful();
        $response->assertNoContent();

        // DELETE
        $response = $this->delete("/api/v1/todos/$id");

        $response->assertSuccessful();
        $response->assertNoContent();
    }
}
