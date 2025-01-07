<?php

namespace App\Http\Controllers;

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
}
