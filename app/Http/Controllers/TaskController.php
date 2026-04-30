<?php

namespace App\Http\Controllers;

use App\Models\Task;
use App\Models\Category;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class TaskController extends Controller{
    public function index(Request $request){
        /** @var \App\Models\User $user */
        $user = Auth::user();
        $tasks = $user->tasks()
            ->with('category')
            ->when($request->status, fn($q, $v) => $q->where('status', $v))
            ->when($request->category, fn($q, $v) => $q->whereHas('category', fn($c) => $c->where('name', $v)))
            ->latest()
            ->paginate(10);

            $categories = Category::all();
            return view('tasks.index', compact('tasks', 'categories'));
    }

    public function show(Task $task){
        if($task ->user_id !== Auth::id()) abort(403);
        $task->load('category');
        return view('tasks.show', compact('task'));
    }

    public function create(){
        $categories = Category::all();
        return view('tasks.create', compact('categories'));
    }

    public function store(Request $request) {
        $data = $request->validate([
            'title' => 'required|string|max:255',
            'description' => 'nullable|string',
            'category_id' => 'required|exists:categories,id',
            'status' => 'in:todo,in_progress,in_review,done',
            'due_date' => 'nullable|date',
        ]);

        $data['user_id'] = Auth::id();
        $data['status'] = $data['status'] ?? 'todo';

        Task::create($data);

        return redirect()->route('tasks.index')
            ->with('success', 'Tâche créée avec succès !');
    }

    public function edit(Task $task){
        if ($task->user_id !== Auth::id()) abort(403);
        $categories = Category::all();
        return view('tasks.edit', compact('task', 'categories'));
    }

    public function update(Request $request, Task $task){
        if ($task->user_id !== Auth::id()) abort(403);
        $data = $request->validate([
            'title' => 'required|string|max:255',
            'description' => 'nullable|string',
            'category_id' => 'required|exists:categories,id',
            'status' => 'in:todo,in_progress,in_review,done',
            'due_date' => 'nullable|date',
        ]);

        // If the task is done, prevent status changes (must use reopen)
        if ($task->status === 'done'){
            $data['status'] = 'done';
        }
        $task->update($data);
        return redirect()->route('tasks.index')
            ->with('success', 'Tâche modifiée avec succès !');
    }

    public function destroy(Task $task){
        if ($task->user_id !== Auth::id()) abort(403);
        $task->delete();
        return redirect()->route('tasks.index')
            ->with('success', 'Tâche supprimée.');
    }

    public function updateStatus(Request $request, Task $task){
        if ($task->user_id !== Auth::id()) abort(403);

        // Block status changes on completed tasks
        if ($task->status === 'done') {
            return redirect()->back()
                ->with('error', 'Cette tâche est terminée. Réouvrez-la d\'abord.');
        }

        $request->validate([
            'status' => 'required|in:todo,in_progress,in_review,done',
        ]);

        $task->update(['status' => $request->status]);

        return redirect()->back()
            ->with('success', 'Statut mis à jour.');
    }

    public function reopen(Task $task){
        if ($task->user_id !== Auth::id()) abort(403);

        if ($task->status !== 'done'){
            return redirect()->back()
                ->with('error', 'Seules les tâches terminées peuvent être réouvertes.');
        }

        $task->update(['status' => 'todo']);
        return redirect()->back()
            ->with('success', 'Tâche réouverte avec succès !');
    }
}







