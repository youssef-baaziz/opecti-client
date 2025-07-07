<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Comment;
use App\Models\Alert;
use App\Models\User;
use Illuminate\Support\Facades\Storage;

class CommentController extends Controller
{
    public function index()
    {
        $query = Comment::with(['alert', 'user']);

        if (request()->has('search') && request()->search != '') {
            $searchTerm = request()->search;
            $query->where('comment', 'like', '%' . $searchTerm . '%');
        }

        $comments = $query->latest()->paginate(10);
        return view('comments.index', compact('comments'));
    }

    public function create()
    {
        $alerts = Alert::all();
        $users = User::all();
        return view('comments.create', compact('alerts', 'users'));
    }

    public function store(Request $request)
    {
        $request->validate([
            'comment' => 'required|string',
            'alert_id' => 'required|exists:alerts,id',
            'user_id' => 'required|exists:users,id',
        ]);

        Comment::create([
            'comment' => $request->comment,
            'alert_id' => $request->alert_id,
            'user_id' => $request->user_id,
        ]);

        return redirect()->route('comments.index')->with('success', 'Comment created successfully.');
    }

    public function show(Comment $comment)
    {
        return view('comments.show', compact('comment'));
    }

    public function edit(Comment $comment)
    {
        $alerts = Alert::all();
        $users = User::all();
        return view('comments.edit', compact('comment', 'alerts', 'users'));
    }

    public function update(Request $request, Comment $comment)
    {
        $request->validate([
            'comment' => 'required|string',
            'alert_id' => 'required|exists:alerts,id',
            'user_id' => 'required|exists:users,id',
        ]);

        $comment->update([
            'comment' => $request->comment,
            'alert_id' => $request->alert_id,
            'user_id' => $request->user_id,
        ]);

        return redirect()->route('comments.index')->with('success', 'Comment updated successfully.');
    }

    public function destroy(Comment $comment)
    {
        $comment->delete();
        return redirect()->route('comments.index')->with('success', 'Comment deleted successfully.');
    }
}
