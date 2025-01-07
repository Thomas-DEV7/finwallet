<?php

namespace App\Http\Controllers;

use App\Models\Transaction;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

class AdminController extends Controller
{
    public function index()
    {
        // Buscar solicitações pendentes
        $requests = DB::table('reversal_requests')->where('status', 'pending')->get();

        return view('admin.reversal-requests', compact('requests'));
    }

    public function approve($uuid)
    {
        // Atualizar status para "approved"
        DB::table('reversal_requests')->where('uuid', $uuid)->update(['status' => 'approved']);

        return redirect()->back()->with('success', 'Solicitação aprovada.');
    }

    public function reject($uuid)
    {
        // Atualizar status para "rejected"
        DB::table('reversal_requests')->where('uuid', $uuid)->update(['status' => 'rejected']);

        return redirect()->back()->with('success', 'Solicitação rejeitada.');
    }
    public function dashboard()
    {
        // Dados para o dashboard
        $totalUsers = User::count();
        $totalTransactions = Transaction::count();
        $pendingReversalRequests = DB::table('reversal_requests')->where('status', 'pending')->count();
        $recentTransactions = Transaction::latest()->limit(10)->get();

        return view('admin.dashboard', compact(
            'totalUsers',
            'totalTransactions',
            'pendingReversalRequests',
            'recentTransactions'
        ));
    }
}
