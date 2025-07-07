<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use App\Models\User;
use App\Models\Client;
use App\Models\Rapport;
use App\Models\IOC;

class DashboardController extends Controller
{
    public function index()
    {
        return view('dashboard.index');
    }

    public function adminDashboard()
    {
        $usersCount = User::count();
        $clientsCount = Client::count();
        $rapportsCount = Rapport::count();
        $iocsCount = IOC::count();

        return view('dashboard.admin', compact('usersCount', 'clientsCount', 'rapportsCount', 'iocsCount'));
    }

    public function clientDashboard()
    {
        $user = Auth::user();
        $rapports = Rapport::where('client_id', $user->id)->latest()->paginate(10);
        $iocs = IOC::where('client_id', $user->id)->latest()->paginate(10);

        return view('dashboard.client', compact('rapports', 'iocs'));
    }

    public function analystDashboard()
    {
        $rapports = Rapport::latest()->paginate(10);
        $iocs = IOC::latest()->paginate(10);

        return view('dashboard.analyst', compact('rapports', 'iocs'));
    }
}
