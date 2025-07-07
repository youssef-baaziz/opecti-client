<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Rapport;
use App\Models\Client;
use Illuminate\Support\Facades\Storage;

class RapportController extends Controller
{
    public function index()
    {
        $rapports = Rapport::with('client')->latest()->paginate(10);
        $clients = Client::all();
        return view('rapport.index', compact('rapports', 'clients'));
    }

    public function create()
    {
        $clients = Client::all();
        return view('rapport.create', compact('clients'));
    }

    public function store(Request $request)
    {
        $request->validate([
            'titre' => 'required|string|max:255',
            'type' => 'required|in:mensuel,hebdomadaire',
            'file' => 'required|file|mimes:pdf,doc,docx,xls,xlsx|max:2048',
            'client_id' => 'required|exists:clients,id',
        ]);

        $file = $request->file('file');
        $fileName = time() . '_' . $file->getClientOriginalName();
        $filePath = $file->storeAs('rapports', $fileName, 'public');

        Rapport::create([
            'titre' => $request->titre,
            'type' => $request->type,
            'client_id' => $request->client_id,
            'file' => $filePath,
        ]);

        return redirect()->route('rapports.index')->with('success', 'Rapport créé et fichier téléchargé avec succès.');
    }

    public function show(Rapport $rapport)
    {
        return view('rapport.show', compact('rapport'));
    }

    public function edit(Rapport $rapport)
    {
        $clients = Client::all();
        return view('rapport.edit', compact('rapport', 'clients'));
    }

    public function update(Request $request, Rapport $rapport)
    {
        $request->validate([
            'titre' => 'required|string|max:255',
            'type' => 'required|in:mensuel,hebdomadaire',
            'file' => 'nullable|file|mimes:pdf,doc,docx,xls,xlsx|max:2048',
            'client_id' => 'required|exists:clients,id',
        ]);

        if ($request->hasFile('file')) {
            $file = $request->file('file');
            $fileName = time() . '_' . $file->getClientOriginalName();
            $filePath = $file->storeAs('rapports', $fileName, 'public');
            $rapport->file = $filePath;
        }

        $rapport->titre = $request->titre;
        $rapport->type = $request->type;
        $rapport->client_id = $request->client_id;
        $rapport->save();

        return redirect()->route('rapports.index')->with('success', 'Rapport mis à jour avec succès.');
    }

    public function destroy(Rapport $rapport)
    {
        if (Storage::disk('public')->exists($rapport->file)) {
            Storage::disk('public')->delete($rapport->file);
        }
        $rapport->delete();
        return redirect()->route('rapports.index')->with('success', 'Rapport supprimé avec succès.');
    }

    public function download(Request $request)
    {
        if (Storage::disk('public')->exists($request->file)) {
            return response()->download(storage_path('app/public/' . $request->file));
        } else {
            return redirect()->back()->with('error', 'Fichier non trouvé.');
        }
    }

    public function search(Request $request)
    {
        $query = $request->input('query');

        $rapports = Rapport::where('titre', 'LIKE', "%{$query}%")
            ->orWhere('type', 'LIKE', "%{$query}%")
            ->orWhere('client_id', 'LIKE', "%{$query}%")
            ->orWhere('file', 'LIKE', "%{$query}%")
            ->get();

        // Return JSON response for AJAX requests
        return response()->json($rapports);
    }
}
