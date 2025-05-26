<?php

namespace App\Http\Controllers\Users;

use App\Models\Client;
use App\Http\Controllers\Controller;
use Illuminate\Http\Request;

class AdminController extends Controller
{
    public function index()
    {
        return view('admin.dashboard');
    }

    public function getlistClients()
    {
        return view('admin.clients.index');
    }

    public function AjouterClient()
    {
        return view('admin.clients.create');
    }

    public function ModiferClient()
    {
        return view('admin.clients.edit');
    }

    public function dashboard()
    {
        $clientsCount = Client::count();

        // Data for charts (alerts per month example)
        $clientsPerWeek = Client::selectRaw('WEEK(created_at) as week, COUNT(*) as count')
            ->groupBy('week')
            ->pluck('count', 'week');

        return view('admin.dashboard', compact('clientsCount', 'clientsPerWeek'));
    }
}
