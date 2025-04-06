<?php

namespace App\Http\Controllers;

use App\Http\Requests\CreateTaskRequest;
use App\Http\Requests\UpdateTaskRequest;
use App\Http\Resources\TaskResource;
use App\Models\Task;
use Illuminate\Http\Request;
use Nette\Schema\ValidationException;

class TaskController extends Controller
{
    public function index()
    {
        $tasks = auth()->user()->tasks()->latest()->get();
        return response()->json(['tasks' => TaskResource::collection($tasks)]);
    }

    public function store(CreateTaskRequest $request)
    {
        $task = [
            'title' => $request->title,
            'description' => $request->description,
            'start_date' => $request->start_date,
            'end_date' => $request->end_date,
        ];

        if(jdate($request->start_date) > jdate($request->end_date)){
            throw new ValidationException('تاریخ شروع نمیتواند قبل از تاریخ پایان باشد.');
        }

        auth()->user()->tasks()->create($task);

        return response()->json(['message' => 'با موفقیت ایحاد شد.']);
    }

    public function delete(Request $request, Task $task)
    {
        $task->delete();
        return response()->json([
            'message' => 'وظیفه حذف شد.'
        ]);
    }

    public function update(UpdateTaskRequest $request, Task $task)
    {
        $data = [
            'title' => $request->title,
            'description' => $request->description,
            'completed' => $request->completed,
            'start_date' => $request->start_date,
            'end_date' => $request->end_date,
        ];

        $task->update($data);

        return response()->json(['message' => 'با موفقیت ویرایش شد.']);
    }
}
