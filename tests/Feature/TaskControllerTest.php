<?php

namespace Tests\Feature;

use App\Models\Task;
use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

class TaskControllerTest extends TestCase
{
    use RefreshDatabase;

    protected function authenticate()
    {
        $user = User::factory()->create();
        $this->actingAs($user);
        return $user;
    }

    public function test_index_returns_tasks_of_authenticated_user()
    {
        $user = $this->authenticate();

        Task::factory()->count(3)->create(['user_id' => $user->id]);
        Task::factory()->create();

        $response = $this->getJson('/api/v1/tasks');

        $response->assertStatus(200);
        $response->assertJsonCount(3, 'tasks');
    }

    public function test_store_creates_task_for_authenticated_user()
    {
        $user = $this->authenticate();

        $data = [
            'title' => 'New Task',
            'description' => 'Test Description',
            'start_date' => now()->toDateString(),
            'end_date' => now()->addDay()->toDateString(),
        ];

        $response = $this->postJson('/api/v1/tasks', $data);

        $response->assertStatus(200);
        $response->assertJson(['message' => 'با موفقیت ایحاد شد.']);
        $this->assertDatabaseHas('tasks', ['title' => 'New Task', 'user_id' => $user->id]);
    }

    public function test_update_task_works_properly()
    {
        $user = $this->authenticate();

        $task = Task::factory()->create(['user_id' => $user->id]);

        $updateData = [
            'title' => 'Updated Title',
            'description' => 'Updated Description',
            'completed' => true,
            'start_date' => now()->toDateString(),
            'end_date' => now()->addDays(2)->toDateString(),
        ];

        $response = $this->putJson("/api/v1/tasks/update/{$task->id}", $updateData);

        $response->assertStatus(200);
        $response->assertJson(['message' => 'با موفقیت ویرایش شد.']);
        $this->assertDatabaseHas('tasks', ['title' => 'Updated Title', 'completed' => true]);
    }

    public function test_delete_task_works_properly()
    {
        $user = $this->authenticate();

        $task = Task::factory()->create(['user_id' => $user->id]);

        $response = $this->deleteJson("/api/v1/tasks/delete/{$task->id}");

        $response->assertStatus(200);
        $response->assertJson(['message' => 'وظیفه حذف شد.']);
        $this->assertDatabaseMissing('tasks', ['id' => $task->id]);
    }

    public function test_user_cannot_delete_other_users_task()
    {
        $user = User::factory()->create();
        $otherUser = User::factory()->create();

        $task = Task::factory()->create(['user_id' => $otherUser->id]);

        $this->actingAs($user);

        $response = $this->deleteJson(route('tasks.delete', ['task' => $task->id]));

        $response->assertStatus(403);
    }

    public function test_user_cannot_update_other_users_task()
    {
        $user = User::factory()->create();
        $otherUser = User::factory()->create();

        $task = Task::factory()->create(['user_id' => $otherUser->id]);

        $this->actingAs($user);

        $response = $this->putJson(route('tasks.update', ['task' => $task->id]), [
            'title' => 'Updated',
            'description' => 'Updated',
            'completed' => false,
            'start_date' => now()->toDateString(),
            'end_date' => now()->addDay()->toDateString(),
        ]);

        $response->assertStatus(403);
    }

}
