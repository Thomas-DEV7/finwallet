<?php

namespace App\Http\Controllers;

use App\Models\ReversalRequest;
use App\Models\Transaction;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Str;


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
        // Usando o modelo ReversalRequest com Eloquent
        $reversalRequests = ReversalRequest::where('type', '!=', 'deposit')->with('user') // Carrega o relacionamento com o usuário
            ->select('reversal_requests.*') // Seleciona os campos da tabela reversal_requests
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
        // Buscar a solicitação de reversão usando o modelo
        $reversalRequest = ReversalRequest::where('uuid', $uuid)->first();

        if (!$reversalRequest) {
            return redirect()->back()->with('error', 'Solicitação não encontrada.');
        }

        // Buscar a transação associada usando o relacionamento
        $transaction = $reversalRequest->transaction;

        if (!$transaction) {
            return redirect()->back()->with('error', 'Transação não encontrada.');
        }

        // Atualizar o status da solicitação para "approved"
        $reversalRequest->update(['status' => 'approved', 'updated_at' => now()]);

        // Buscar o usuário associado à transação
        $user = User::find($transaction->user_id);

        if (!$user) {
            return redirect()->back()->with('error', 'Usuário associado à transação não encontrado.');
        }

        // Reverter o valor para o saldo do usuário
        $user->balance += abs($transaction->amount);
        $user->save(); // Persistir a alteração no banco

        // Verificar se o saldo foi atualizado
        if ($user->wasChanged('balance')) {
            // Registrar a transação de estorno
            Transaction::create([
                'uuid' => (string) Str::uuid(),
                'user_id' => $user->id, // Associar a transação ao usuário
                'sender_id' => null, // O estorno não tem um remetente definido
                'recipient_id' => $user->id, // O valor é devolvido ao usuário
                'amount' => abs($transaction->amount), // Valor positivo para representar a entrada
                'type' => 'refund', // Tipo de transação "refund" para identificar o estorno
                'related_transaction_id' => $transaction->id, // Relaciona com a transação original
            ]);

            return redirect()->back()->with(
                'success',
                'Solicitação aprovada. O valor de R$ ' . number_format(abs($transaction->amount), 2, ',', '.') . ' foi revertido para a conta do usuário.'
            );
        } else {
            return redirect()->back()->with('error', 'Não foi possível atualizar o saldo do usuário.');
        }
    }


    public function reject($uuid)
    {
        // Buscar a solicitação de reversão usando o modelo
        $reversalRequest = ReversalRequest::where('uuid', $uuid)->first();

        if (!$reversalRequest) {
            return redirect()->back()->with('error', 'Solicitação não encontrada.');
        }

        // Atualizar status para "rejected"
        $reversalRequest->update(['status' => 'rejected', 'updated_at' => now()]);

        return redirect()->back()->with('success', 'Solicitação rejeitada.');
    }

    public function dashboard()
    {
        // Dados para o dashboard
        $totalUsers = User::count();
        $totalTransactions = Transaction::count();
        $pendingReversalRequests = ReversalRequest::where('status', 'pending')->count();
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
