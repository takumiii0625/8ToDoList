<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Folder;
use App\Models\Task;
use App\Http\Requests\CreateTask;
use App\Http\Requests\EditTask;
use Illuminate\Support\Facades\Auth;

class TaskController extends Controller
{
    public function index(int $id)
    {
        // すべてのフォルダを取得する
        // $folders = Folder::all();

        // ログインユーザーを取得
        //$user = Auth::user();

        // ユーザーが所有しているフォルダを取得する
        //$folders = Folder::where('user_id', $user->id)->get();

        // ログインユーザーのフォルダのみを取得する
        $folders = Auth::user()->folders;

        // 選ばれたフォルダを取得する
        //$current_folder = Folder::find($id);

        // ユーザーが所有するフォルダの中から、選ばれたフォルダを取得する
        $current_folder = $folders->firstWhere('id', $id);

        // 選ばれたフォルダに紐づくタスクを取得する
        //  $tasks = Task::where('folder_id', $current_folder->id)->get();


        $tasks = $current_folder->tasks()->get(); // ★
        // タスクが存在しない場合は、タスク作成ページへリダイレクトする
        if ($tasks->isEmpty()) {
            return redirect()->route('tasks.create', ['id' => $id]);
        }

        return view('tasks/index', [
            'folders' => $folders,
            'current_folder_id' => $current_folder->id,
            'tasks' => $tasks,
        ]);
    }
    /**
     * GET /folders/{id}/tasks/create
     */
    public function showCreateForm(int $id)
    {
        return view('tasks/create', [
            'folder_id' => $id
        ]);
    }

    public function create(int $id, CreateTask $request)
    {
        $current_folder = Folder::find($id);

        $task = new Task();
        $task->title = $request->title;
        $task->due_date = $request->due_date;

        $current_folder->tasks()->save($task);

        return redirect()->route('tasks.index', [
            'id' => $current_folder->id,
        ]);
    }

    /**
     * GET /folders/{id}/tasks/{task_id}/edit
     */
    public function showEditForm(int $id, int $task_id)
    {
        $task = Task::find($task_id);

        return view('tasks/edit', [
            'task' => $task,
        ]);
    }

    public function edit(int $id, int $task_id, EditTask $request)
    {
        // 1
        $task = Task::find($task_id);

        // 2
        $task->title = $request->title;
        $task->status = $request->status;
        $task->due_date = $request->due_date;
        $task->save();

        // 3
        return redirect()->route('tasks.index', [
            'id' => $task->folder_id,
        ]);
    }
}
