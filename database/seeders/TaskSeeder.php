<?php

namespace Database\Seeders;

use App\Models\Task;
use App\Models\User;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;

class TaskSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $admin = User::query()->where('name', 'admin')->firstOrFail();
        $user = User::query()->where('name', 'user')->firstOrFail();

        // added 10 task for each
        Task::factory()->titlePrefix('admin')->count(10)->create([
           'user_id' => $admin->id,
        ]);

        Task::factory()->titlePrefix('user')->count(10)->create([
           'user_id' => $user->id,
        ]);
    }
}
