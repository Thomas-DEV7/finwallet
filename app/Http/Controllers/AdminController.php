<?php

namespace App\Http\Controllers;

use App\Models\Transaction;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

class AdminController extends Controller
{

    public function update(Request $request)
    {
        // Validar dados
        $validated = $request->validate([
            'user_id' => 'required|exists:users,id',
            'name' => 'required|string|max:255',
            'role' => 'required|in:admin,user',
        ]);

        // Atualizar o usuário
        $user = User::findOrFail($validated['user_id']);
        $user->update([
            'name' => $validated['name'],
            'role' => $validated['role'],
        ]);

        return redirect()->route('admin.users')->with('success', 'Usuário atualizado com sucesso.');
    }

    public function delete(Request $request)
    {
        // Validar ID do usuário
        $validated = $request->validate([
            'user_id' => 'required|exists:users,id',
        ]);

        // Excluir o usuário
        $user = User::findOrFail($validated['user_id']);
        $user->delete();

        return redirect()->route('admin.users')->with('success', 'Usuário excluído com sucesso.');
    }

    public function users()
    {
        $users = User::all();
        return view('admin.users', compact('users'));
    }

    public function reversalRequests()
    {
        $reversalRequests = DB::table('reversal_requests')
            ->join('users', 'reversal_requests.user_uuid', '=', 'users.uuid') // Relaciona com a tabela users
            ->select('reversal_requests.*', 'users.name as user_name') // Seleciona os campos necessários
            ->get();

        return view('admin.reversal-requests', compact('reversalRequests'));
    }



    // public function index()
    // {
    //     // Buscar solicitações pendentes
    //     $requests = DB::table('reversal_requests')->where('status', 'pending')->get();

    //     return view('admin.reversal-requests', compact('requests'));
    // }

    public function approve($uuid)
    {
        // Buscar a solicitação de reversão
        $reversalRequest = DB::table('reversal_requests')->where('uuid', $uuid)->first();

        if (!$reversalRequest) {
            return redirect()->back()->with('error', 'Solicitação não encontrada.');
        }

        // Buscar a transação associada
        $transaction = DB::table('transactions')->where('uuid', $reversalRequest->transaction_uuid)->first();

        if (!$transaction) {
            return redirect()->back()->with('error', 'Transação não encontrada.');
        }

        // Atualizar status da solicitação para "approved"
        DB::table('reversal_requests')->where('uuid', $uuid)->update(['status' => 'approved', 'updated_at' => now()]);

        // Buscar o usuário associado à transação
        $user = DB::table('users')->where('id', $transaction->user_id)->first();

        if (!$user) {
            return redirect()->back()->with('error', 'Usuário associado à transação não encontrado.');
        }

        // Reverter o valor para o saldo do usuário
        $newBalance = $user->balance + abs($transaction->amount);
        DB::table('users')->where('id', $user->id)->update(['balance' => $newBalance]);

        // Retornar mensagem de sucesso com o valor da transação
        return redirect()->back()->with(
            'success',
            'Solicitação aprovada. O valor de R$ ' . number_format(abs($transaction->amount), 2, ',', '.') . ' foi revertido para a conta do usuário.'
        );
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
        $recentTransactions = Transaction::join('users', 'transactions.user_id', '=', 'users.id')
            ->select('transactions.*', 'users.name as user_name')
            ->latest()
            ->limit(10)
            ->get();

        return view('admin.dashboard', compact(
            'totalUsers',
            'totalTransactions',
            'pendingReversalRequests',
            'recentTransactions'
        ));
    }
}
