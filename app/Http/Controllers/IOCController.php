<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\IOC;
use App\Models\Client;
use Illuminate\Support\Facades\Auth;
use Carbon\Carbon;

class IOCController extends Controller
{
    public function index()
    {
        $query = IOC::with('client');

        if (request()->has('search') && request()->search != '') {
            $searchTerm = request()->search;
            $query->where('type', 'like', '%' . $searchTerm . '%')
                  ->orWhere('value', 'like', '%' . $searchTerm . '%');
        }

        $iocs = $query->latest()->paginate(10);
        return view('iocs.index', compact('iocs'));
    }

    public function create()
    {
        $clients = Client::all();
        return view('iocs.create', compact('clients'));
    }

    public function store(Request $request)
    {
        $request->validate([
            'type' => 'required|string',
            'value' => 'required|string',
            'description' => 'nullable|string',
            'first_seen' => 'required|date',
            'last_seen' => 'required|date',
            'client_id' => 'required|exists:clients,id',
        ]);

        IOC::create([
            'type' => $request->type,
            'value' => $request->value,
            'description' => $request->description,
            'first_seen' => $request->first_seen,
            'last_seen' => $request->last_seen,
            'client_id' => $request->client_id,
        ]);

        return redirect()->route('iocs.index')->with('success', 'IOC created successfully.');
    }

    public function show(IOC $ioc)
    {
        return view('iocs.show', compact('ioc'));
    }

    public function edit(IOC $ioc)
    {
        $clients = Client::all();
        return view('iocs.edit', compact('ioc', 'clients'));
    }

    public function update(Request $request, IOC $ioc)
    {
        $request->validate([
            'type' => 'required|string',
            'value' => 'required|string',
            'description' => 'nullable|string',
            'first_seen' => 'required|date',
            'last_seen' => 'required|date',
            'client_id' => 'required|exists:clients,id',
        ]);

        $ioc->update([
            'type' => $request->type,
            'value' => $request->value,
            'description' => $request->description,
            'first_seen' => $request->first_seen,
            'last_seen' => $request->last_seen,
            'client_id' => $request->client_id,
        ]);

        return redirect()->route('iocs.index')->with('success', 'IOC updated successfully.');
    }

    public function destroy(IOC $ioc)
    {
        $ioc->delete();
        return redirect()->route('iocs.index')->with('success', 'IOC deleted successfully.');
    }

    public function dashboard(Request $request)
    {
        $user = Auth::user();
        if (!$user) {
            return response()->json(['message' => 'Unauthenticated.'], 401);
        }

        $tenantId = $user->id; // Utilisation de l'ID utilisateur comme tenant_id

        $query = Ioc::where('tenant_id', $tenantId);

        // Filtrer par jours (par défaut 7 jours)
        $days = $request->input('last_days', 7);
        if ($days > 0) {
            $query->where('detected_at', '>=', Carbon::now()->subDays($days));
        }

        // Filtrer par source_type (ex: 'Internal_EDR', 'TAXII_Feed_A')
        if ($request->has('source_type') && $request->input('source_type') !== 'all') {
            $query->where('source', $request->input('source_type'));
        }

        // Filtrer par type d'IOC (ex: 'ipv4-addr', 'file-hash-md5')
        if ($request->has('ioc_type') && $request->input('ioc_type') !== 'all') {
            $query->where('type', $request->input('ioc_type'));
        }

        $iocs = $query->orderBy('detected_at', 'desc')->get();

        return response()->json($iocs);
    }

    
}
