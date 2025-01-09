<?php

namespace App\Http\Controllers;

use App\Models\ReversalRequest;
use App\Models\Transaction;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Str;


class WalletController extends Controller
{
    public function index()
    {
        try {
            $allUsers = User::where('id', '!=', auth()->id())
                ->where('role', '!=', 'admin')
                ->get(['id', 'name', 'email']);

            return view('dashboard', compact('allUsers'));
        } catch (\Exception $e) {
            Log::error('Error fetching users for dashboard', [
                'error' => $e->getMessage(),
                'user_id' => auth()->id(),
            ]);

            // Retornar uma mensagem amigável ao usuário
            return redirect()->route('dashboard')->with('error', 'Ocorreu um erro ao carregar os dados do painel. Tente novamente mais tarde.');
        }
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

        try {
            $sender = auth()->user();
            $recipient = User::where('email', $request->input('to_user_email'))->firstOrFail();
            $amount = $request->input('amount');

            if ($sender->balance < $amount) {
                return redirect()->back()->withErrors(['amount' => 'Saldo insuficiente para realizar a transferência.']);
            }

            DB::transaction(function () use ($sender, $recipient, $amount) {
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
            });

            return redirect()
                ->route('dashboard')
                ->with('success', 'Transferência realizada com sucesso! Saldo atualizado: R$ ' . number_format($sender->balance, 2, ',', '.'));
        } catch (\Illuminate\Database\Eloquent\ModelNotFoundException $e) {
            return redirect()->back()->withErrors(['to_user_email' => 'O destinatário não foi encontrado. Verifique o e-mail informado.']);
        } catch (\Exception $e) {
            Log::error('Error during transfer: ' . $e->getMessage());
            return back()->withErrors(['error' => 'Ocorreu um erro ao processar a transferência.']);
        }
    }



    public function storeReversalRequest(Request $request)
    {
        try {
            // Validar os dados recebidos
            $request->validate([
                'transaction_id' => 'required|exists:transactions,id',
                'comment' => 'required|string|max:500',
            ]);

            // Buscar a transação associada
            $transaction = Transaction::find($request->transaction_id);

            if (!$transaction) {
                // Logar o erro e retornar uma mensagem amigável
                Log::warning('Transaction not found for reversal request', [
                    'transaction_id' => $request->transaction_id,
                    'user_id' => auth()->id(),
                ]);

                return redirect()->back()->with('error', 'Transação não encontrada.');
            }

            // Verificar se já existe uma solicitação de reversão para esta transação
            $existingRequest = ReversalRequest::where('transaction_uuid', $transaction->uuid)->first();
            if ($existingRequest) {
                Log::info('Reversal request already exists for transaction', [
                    'transaction_uuid' => $transaction->uuid,
                    'user_id' => auth()->id(),
                ]);

                return redirect()->back()->with('info', 'Já existe uma solicitação de reversão para esta transação.');
            }

            // Salvar a solicitação de reversão na tabela `reversal_requests`
            ReversalRequest::create([
                'uuid' => (string) Str::uuid(),
                'user_uuid' => auth()->user()->uuid,
                'transaction_uuid' => $transaction->uuid,
                'comment' => $request->comment,
                'status' => 'pending',
            ]);

            return redirect()->back()->with('success', 'Solicitação de reversão enviada com sucesso.');
        } catch (\Exception $e) {
            // Logar o erro
            Log::error('Error while storing reversal request', [
                'error' => $e->getMessage(),
                'transaction_id' => $request->transaction_id ?? null,
                'user_id' => auth()->id(),
            ]);

            // Retornar uma mensagem amigável ao usuário
            return redirect()->back()->with('error', 'Ocorreu um erro ao processar sua solicitação. Tente novamente mais tarde.');
        }
    }



    public function reverse($id)
    {
        try {
            // Buscar a transação
            $transaction = Transaction::find($id);

            if (!$transaction) {
                // Logar erro de transação não encontrada
                Log::warning('Transaction not found for reversal', ['transaction_id' => $id, 'user_id' => auth()->id()]);
                return back()->with('error', 'Transação não encontrada.');
            }

            // Verificar se a transação é reversível
            if ($transaction->type !== 'transfer' || $transaction->amount >= 0) {
                Log::info('Transaction is not reversible', ['transaction_id' => $transaction->id]);
                return back()->with('error', 'Esta transação não pode ser revertida.');
            }

            // Recuperar remetente e destinatário
            $sender = $transaction->sender;
            $recipient = $transaction->recipient;

            if (!$sender || !$recipient) {
                Log::error('Sender or recipient not found for transaction', [
                    'transaction_id' => $transaction->id,
                    'sender_id' => $transaction->sender_id,
                    'recipient_id' => $transaction->recipient_id,
                ]);

                return back()->with('error', 'Erro ao processar a reversão. Verifique os dados da transação.');
            }

            // Atualizar saldos com verificação adicional
            if ($recipient->balance < abs($transaction->amount)) {
                Log::warning('Recipient balance insufficient for reversal', [
                    'recipient_id' => $recipient->id,
                    'required_balance' => abs($transaction->amount),
                    'current_balance' => $recipient->balance,
                ]);

                return back()->with('error', 'O destinatário não tem saldo suficiente para completar a reversão.');
            }

            DB::transaction(function () use ($transaction, $sender, $recipient) {
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
            });

            return back()->with('success', 'Transação revertida com sucesso.');
        } catch (\Exception $e) {
            // Logar erro e retornar mensagem genérica ao usuário
            Log::error('Error during transaction reversal', [
                'error' => $e->getMessage(),
                'transaction_id' => $id,
                'user_id' => auth()->id(),
            ]);

            return back()->with('error', 'Ocorreu um erro ao tentar reverter a transação. Tente novamente mais tarde.');
        }
    }
}
