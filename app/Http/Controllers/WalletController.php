<?php

namespace App\Http\Controllers;

use App\Models\Transaction;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Str;


class WalletController extends Controller
{
    public function index()
    {
        $allUsers = User::where('id', '!=', auth()->id())
            ->where('role', '!=', 'admin') // Exclui administradores
            ->get(['id', 'name', 'email']); // Seleciona apenas as colunas necessárias

        return view('dashboard', compact('allUsers'));
    }

    public function deposit(Request $request)
    {
        $request->validate([
            'amount' => 'required|numeric|min:1',
        ]);

        $user = auth()->user();
        $amount = $request->input('amount');

        // Atualizar saldo do usuário
        $user->balance += $amount;
        $user->save();

        // Registrar transação de depósito
        Transaction::create([
            'user_id' => $user->id,
            'amount' => $amount,
            'type' => 'deposit',
        ]);

        return redirect()
            ->route('dashboard')
            ->with('success', 'Pagamento efetuado com sucesso! Valor de R$ ' . number_format($amount, 2, ',', '.') . ' adicionado à carteira.');
    }


    public function transferSubmit(Request $request)
    {
        $request->validate([
            'recipient_id' => 'required|exists:users,id',
            'amount' => 'required|numeric|min:1',
        ]);

        $sender = auth()->user();
        $recipient = User::findOrFail($request->input('recipient_id'));
        $amount = $request->input('amount');

        if ($sender->balance < $amount) {
            return back()->with('error', 'Saldo insuficiente.');
        }

        // Atualizar saldos
        $sender->balance -= $amount;
        $recipient->balance += $amount;
        $sender->save();
        $recipient->save();

        // Registrar transações
        Transaction::create([
            'user_id' => $sender->id, // Dono da transação
            'sender_id' => $sender->id,
            'recipient_id' => $recipient->id,
            'amount' => -$amount, // Valor negativo para o remetente
            'type' => 'transfer',
        ]);

        Transaction::create([
            'user_id' => $recipient->id, // Dono da transação
            'sender_id' => $sender->id,
            'recipient_id' => $recipient->id,
            'amount' => $amount, // Valor positivo para o destinatário
            'type' => 'transfer',
        ]);

        return redirect()->route('dashboard')->with('success', 'Transferência realizada com sucesso!');
    }

    public function transfer(Request $request)
    {
        $request->validate([
            'amount' => 'required|numeric|min:1',
            'to_user_email' => 'required|email|exists:users,email',
        ]);

        $sender = auth()->user(); // Corrige a chamada para auth()->user()
        $recipient = User::where('email', $request->input('to_user_email'))->firstOrFail();
        $amount = $request->input('amount');

        // Verifica se o saldo é suficiente
        if ($sender->balance < $amount) {
            return redirect()->back()->withErrors(['amount' => 'Saldo insuficiente para realizar a transferência.']);
        }

        // Atualizar saldos
        $sender->balance -= $amount;
        $recipient->balance += $amount;
        $sender->save();
        $recipient->save();

        // Registrar transações
        $transfer = Transaction::create([
            'user_id' => $sender->id,
            'sender_id' => $sender->id,
            'recipient_id' => $recipient->id,
            'amount' => -$amount,
            'type' => 'transfer',
        ]);

        Transaction::create([
            'user_id' => $recipient->id,
            'sender_id' => $sender->id,
            'recipient_id' => $recipient->id,
            'amount' => $amount,
            'type' => 'transfer',
            'related_transaction_id' => $transfer->id,
        ]);

        return redirect()
            ->route('dashboard')
            ->with('success', 'Transferência realizada com sucesso! Saldo atualizado: R$ ' . number_format($sender->balance, 2, ',', '.'));
    }



    public function storeReversalRequest(Request $request)
    {
        // Validar os dados recebidos
        $request->validate([
            'transaction_id' => 'required|exists:transactions,id',
            'user_id' => 'required|exists:users,uuid',
            'comment' => 'required|string|max:500',
        ]);

        $transaction = Transaction::findOrFail($request->transaction_id);

        // Salvar a solicitação de reversão na tabela `reversal_requests`
        DB::table('reversal_requests')->insert([
            'uuid' => (string) Str::uuid(),
            'user_uuid' => $request->user_id,
            'transaction_uuid' => $transaction->uuid,
            'comment' => $request->comment,
            'status' => 'pending',
            'created_at' => now(),
            'updated_at' => now(),
        ]);

        return redirect()->back()->with('success', 'Solicitação de reversão enviada com sucesso.');
    }


    public function reverse($id)
    {
        $transaction = Transaction::findOrFail($id);

        // Verificar se a transação é reversível
        if ($transaction->type !== 'transfer' || $transaction->amount >= 0) {
            return back()->with('error', 'Esta transação não pode ser revertida.');
        }

        // Recuperar remetente e destinatário
        $sender = $transaction->sender;
        $recipient = $transaction->recipient;

        // Atualizar saldos
        $sender->balance += abs($transaction->amount);
        $recipient->balance -= abs($transaction->amount);

        $sender->save();
        $recipient->save();

        // Registrar a reversão como uma nova transação
        Transaction::create([
            'uuid' => (string) Str::uuid(),
            'user_id' => $transaction->user_id,
            'sender_id' => $transaction->recipient_id,
            'recipient_id' => $transaction->sender_id,
            'amount' => abs($transaction->amount),
            'type' => 'reversal',
        ]);

        return back()->with('success', 'Transação revertida com sucesso.');
    }
}
