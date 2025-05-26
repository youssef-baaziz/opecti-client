<?php

namespace App\Http\Controllers\Users;

use App\Models\Client;
use Illuminate\Support\Facades\Auth;
use App\Http\Controllers\Controller;
use Illuminate\Http\Request;

class ClientController extends Controller
{
    public function index()
    {
        $clients = Client::latest()->paginate(10);
        return view('admin.clients.index', compact('clients'));
    }

    public function dashboard()
    {
        return view('client.dashboard');
    }

    public function create()
    {
        return view('admin.clients.create');
    }

    public function store(Request $request)
    {
        $request->validate([
            'nom' => 'required|string|max:255',
            'secteur' => 'required|string|max:255'
        ]);

        Client::create($request->only(['nom', 'secteur']));
        return redirect()->route('clients.index')->with('success', 'Client created successfully.');
    }

    public function show(Client $client)
    {
        return view('admin.clients.show', compact('client'));
    }

    public function edit(Client $client)
    {
        return view('admin.clients.edit', compact('client'));
    }


    public function update(Request $request, Client $client)
    {
        $request->validate([
            'nom' => 'required|string|max:255',
            'secteur' => 'required|string|max:255',
        ]);

        $client->update($request->only(['nom', 'secteur']));

        return redirect()->route('clients.index')->with('success', 'Client updated successfully.');
    }


    public function destroy(Client $client)
    {
        $client->delete();
        return redirect()->route('clients.index')->with('success', 'Client deleted successfully.');
    }

    public function search(Request $request)
    {
        $query = $request->input('query');
        $clients = Client::where('nom', 'LIKE', "%{$query}%")
            ->orWhere('secteur', 'LIKE', "%{$query}%")
            ->get(); // Use get() instead of paginate() for AJAX

        // Return JSON response for AJAX requests
        return response()->json($clients);
    }
}
