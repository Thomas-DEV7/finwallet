<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\User;
use App\Models\Transaction;
use Illuminate\Support\Facades\DB;

class UserController extends Controller
{
    public function dashboard()
    {
        $users = User::where('id', auth()->user()->id)->get(); // Lista todos os usuários, exceto o logado
        return view('dashboard', compact('users'));
    }

    public function transfer(Request $request)
    {
        $validated = $request->validate([
            'from_user_id' => 'required|exists:users,id',
            'to_user_id' => 'required|exists:users,id|different:from_user_id',
            'amount' => 'required|numeric|min:0.01|max:' . auth()->user()->balance,
        ]);

        $sender = User::findOrFail($validated['from_user_id']);
        $recipient = User::findOrFail($validated['to_user_id']);

        DB::transaction(function () use ($sender, $recipient, $validated) {
            // Deduzir saldo do remetente
            $sender->update(['balance' => $sender->balance - $validated['amount']]);

            // Adicionar saldo ao destinatário
            $recipient->update(['balance' => $recipient->balance + $validated['amount']]);

            // Registrar transações
            Transaction::create([
                'user_id' => $sender->id,
                'amount' => -$validated['amount'],
                'type' => 'transfer',
            ]);
            Transaction::create([
                'user_id' => $recipient->id,
                'amount' => $validated['amount'],
                'type' => 'transfer',
            ]);
        });

        return redirect()->back()->with('success', 'Transferência realizada com sucesso!');
    }
}
