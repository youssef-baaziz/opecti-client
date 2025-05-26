<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Rapport;
use App\Models\User;
use Illuminate\Support\Facades\Storage;

class RapportController extends Controller
{
    public function index()
    {
        $rapports = Rapport::with('user')->latest()->paginate(10);
        $users = User::all();
        return view('analyste.rapport.index', compact('rapports', 'users'));
    }

    public function create()
    {
        $users = User::all();
        return view('analyste.rapport.create', compact('users'));
    }

    public function store(Request $request)
    {
        $request->validate([
            'titre' => 'required|string|max:255',
            'type' => 'required|in:mensuel,hebdomadaire',
            'file' => 'required|file|mimes:pdf,doc,docx,xls,xlsx|max:2048',
            'user_id' => 'required|exists:users,id',
        ]);

        $file = $request->file('file');
        $fileName = time() . '_' . $file->getClientOriginalName();
        $filePath = $file->storeAs('rapports', $fileName, 'public');

        Rapport::create([
            'titre' => $request->titre,
            'type' => $request->type,
            'user_id' => $request->user_id,
            'file' => $filePath,
        ]);

        return redirect()->route('rapports.index')->with('success', 'Rapport créé et fichier téléchargé avec succès.');
    }

    public function show(Rapport $rapport)
    {
        return view('analyste.rapport.show', compact('rapport'));
    }

    public function edit(Rapport $rapport)
    {
        $users = User::all();
        return view('analyste.rapport.edit', compact('rapport', 'users'));
    }

    public function update(Request $request, Rapport $rapport)
    {
        $request->validate([
            'titre' => 'required|string|max:255',
            'type' => 'required|in:mensuel,hebdomadaire',
            'file' => 'nullable|file|mimes:pdf,doc,docx,xls,xlsx|max:2048',
            'user_id' => 'required|exists:users,id',
        ]);

        if ($request->hasFile('file')) {
            $file = $request->file('file');
            $fileName = time() . '_' . $file->getClientOriginalName();
            $filePath = $file->storeAs('rapports', $fileName, 'public');
            $rapport->file = $filePath;
        }

        $rapport->titre = $request->titre;
        $rapport->type = $request->type;
        $rapport->user_id = $request->user_id;
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
            ->orWhere('user_id', 'LIKE', "%{$query}%")
            ->orWhere('file', 'LIKE', "%{$query}%")
            ->get();

        // Return JSON response for AJAX requests
        return response()->json($rapports);
    }
}
