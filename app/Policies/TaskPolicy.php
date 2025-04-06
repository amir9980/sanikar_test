<?php

namespace App\Policies;

use App\Models\Task;
use App\Models\User;

class TaskPolicy
{

    public function before(User $user) // always allow admins
    {
        return $user->role === 'admin' ? true : null;
    }
    public function delete(User $user,Task $task)
    {
        return $task->user_id === $user->id;
    }
    public function update(User $user,Task $task)
    {
        return $task->user_id == $user->id;
    }
}
