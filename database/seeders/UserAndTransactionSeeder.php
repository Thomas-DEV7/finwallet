<?php

namespace Database\Seeders;

use App\Models\User;
use App\Models\Transaction;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Str;

class UserAndTransactionSeeder extends Seeder
{
    public function run()
    {
        // Criar 4 usuários
        $users = [
            User::create([
                'uuid' => (string) Str::uuid(),
                'name' => 'Admin João',
                'email' => 'admin@exemplo.com',
                'password' => Hash::make('secret'),
                'role' => 'admin',
                'balance' => 1000.00, // Saldo inicial
            ]),
            User::create([
                'uuid' => (string) Str::uuid(),
                'name' => 'Carlos Silva',
                'email' => 'carlos@exemplo.com',
                'password' => Hash::make('secret'),
                'role' => 'user',
                'balance' => 800.00, // Saldo inicial aumentado para mais transações
            ]),
            User::create([
                'uuid' => (string) Str::uuid(),
                'name' => 'Maria Oliveira',
                'email' => 'maria@exemplo.com',
                'password' => Hash::make('secret'),
                'role' => 'user',
                'balance' => 300.00,
            ]),
            User::create([
                'uuid' => (string) Str::uuid(),
                'name' => 'Ana Santos',
                'email' => 'ana@exemplo.com',
                'password' => Hash::make('secret'),
                'role' => 'user',
                'balance' => 200.00,
            ]),
        ];

        // Transações realizadas por Carlos Silva
        Transaction::create([
            'uuid' => (string) Str::uuid(),
            'user_id' => $users[1]->id, // Carlos Silva
            'sender_id' => $users[1]->id,
            'recipient_id' => $users[2]->id, // Para Maria Oliveira
            'amount' => -100.00, // Saída
            'type' => 'transfer',
        ]);

        Transaction::create([
            'uuid' => (string) Str::uuid(),
            'user_id' => $users[2]->id, // Maria Oliveira
            'sender_id' => $users[1]->id, // Carlos Silva
            'recipient_id' => $users[2]->id,
            'amount' => 100.00, // Entrada
            'type' => 'transfer',
        ]);

        Transaction::create([
            'uuid' => (string) Str::uuid(),
            'user_id' => $users[1]->id, // Carlos Silva
            'sender_id' => $users[1]->id,
            'recipient_id' => $users[3]->id, // Para Ana Santos
            'amount' => -50.00, // Saída
            'type' => 'transfer',
        ]);

        Transaction::create([
            'uuid' => (string) Str::uuid(),
            'user_id' => $users[3]->id, // Ana Santos
            'sender_id' => $users[1]->id, // Carlos Silva
            'recipient_id' => $users[3]->id,
            'amount' => 50.00, // Entrada
            'type' => 'transfer',
        ]);

        Transaction::create([
            'uuid' => (string) Str::uuid(),
            'user_id' => $users[1]->id, // Carlos Silva faz um depósito
            'sender_id' => null,
            'recipient_id' => $users[1]->id,
            'amount' => 200.00, // Depósito
            'type' => 'deposit',
        ]);

        // Outras transações para variabilidade
        Transaction::create([
            'uuid' => (string) Str::uuid(),
            'user_id' => $users[1]->id, // Carlos Silva
            'sender_id' => $users[1]->id,
            'recipient_id' => $users[0]->id, // Para Admin João
            'amount' => -150.00, // Saída
            'type' => 'transfer',
        ]);

        Transaction::create([
            'uuid' => (string) Str::uuid(),
            'user_id' => $users[0]->id, // Admin João
            'sender_id' => $users[1]->id, // Carlos Silva
            'recipient_id' => $users[0]->id,
            'amount' => 150.00, // Entrada
            'type' => 'transfer',
        ]);

        // Transação de Ana Santos para Maria Oliveira
        Transaction::create([
            'uuid' => (string) Str::uuid(),
            'user_id' => $users[3]->id, // Ana Santos envia
            'sender_id' => $users[3]->id,
            'recipient_id' => $users[2]->id, // Para Maria Oliveira
            'amount' => -30.00,
            'type' => 'transfer',
        ]);

        Transaction::create([
            'uuid' => (string) Str::uuid(),
            'user_id' => $users[2]->id, // Maria Oliveira recebe
            'sender_id' => $users[3]->id,
            'recipient_id' => $users[2]->id,
            'amount' => 30.00,
            'type' => 'transfer',
        ]);
    }
}
