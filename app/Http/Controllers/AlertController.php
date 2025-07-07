<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Alert;
use App\Models\User;
use Illuminate\Support\Facades\Storage;

class AlertController extends Controller
{
    public function index()
    {
        $query = Alert::with('user');

        if (request()->has('search') && request()->search != '') {
            $searchTerm = request()->search;
            $query->where('title', 'like', '%' . $searchTerm . '%')
                  ->orWhere('description', 'like', '%' . $searchTerm . '%');
        }

        $alerts = $query->latest()->paginate(10);
        $users = User::all();
        return view('alerts.index', compact('alerts', 'users'));
    }

    public function create()
    {
        $users = User::all();
        return view('alerts.create', compact('users'));
    }

    public function store(Request $request)
    {
        $request->validate([
            'title' => 'required|string|max:255',
            'description' => 'required|string',
            'status' => 'required|in:active,closed,reopened',
            'severity' => 'required|in:low,medium,high,critical',
            'user_id' => 'required|exists:users,id',
        ]);

        Alert::create([
            'title' => $request->title,
            'description' => $request->description,
            'status' => $request->status,
            'severity' => $request->severity,
            'user_id' => $request->user_id,
        ]);

        return redirect()->route('alerts.index')->with('success', 'Alert created successfully.');
    }

    public function show(Alert $alert)
    {
        return view('alerts.show', compact('alert'));
    }

    public function edit(Alert $alert)
    {
        $users = User::all();
        return view('alerts.edit', compact('alert', 'users'));
    }

    public function update(Request $request, Alert $alert)
    {
        $request->validate([
            'title' => 'required|string|max:255',
            'description' => 'required|string',
            'status' => 'required|in:active,closed,reopened',
            'severity' => 'required|in:low,medium,high,critical',
            'user_id' => 'required|exists:users,id',
        ]);

        $alert->title = $request->title;
        $alert->description = $request->description;
        $alert->status = $request->status;
        $alert->severity = $request->severity;
        $alert->user_id = $request->user_id;
        $alert->save();

        return redirect()->route('alerts.index')->with('success', 'Alert updated successfully.');
    }

    public function destroy(Alert $alert)
    {
        $alert->delete();
        return redirect()->route('alerts.index')->with('success', 'Alert deleted successfully.');
    }

    public function search(Request $request)
    {
        $query = $request->input('query');

        $alerts = Alert::where('title', 'LIKE', "%{$query}%")
            ->orWhere('description', 'LIKE', "%{$query}%")
            ->orWhere('status', 'LIKE', "%{$query}%")
            ->orWhere('severity', 'LIKE', "%{$query}%")
            ->orWhere('user_id', 'LIKE', "%{$query}%")
            ->get();

        // Return JSON response for AJAX requests
        return response()->json($alerts);
    }
}
