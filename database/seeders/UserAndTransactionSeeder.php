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
        // Criar usuários com saldo inicial
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
                'balance' => 800.00, // Saldo inicial
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

        // Criar transações de depósito inicial para refletir o saldo inicial
        foreach ($users as $user) {
            Transaction::create([
                'uuid' => (string) Str::uuid(),
                'user_id' => $user->id,
                'sender_id' => null,
                'recipient_id' => $user->id,
                'amount' => $user->balance,
                'type' => 'deposit',
            ]);
        }

        // Adicionar transações personalizadas
        Transaction::create([
            'uuid' => (string) Str::uuid(),
            'user_id' => $users[1]->id, // Carlos Silva
            'sender_id' => $users[1]->id,
            'recipient_id' => $users[2]->id, // Para Maria Oliveira
            'amount' => -100.00,
            'type' => 'transfer',
        ]);

        Transaction::create([
            'uuid' => (string) Str::uuid(),
            'user_id' => $users[2]->id, // Maria Oliveira
            'sender_id' => $users[1]->id, // Carlos Silva
            'recipient_id' => $users[2]->id,
            'amount' => 100.00,
            'type' => 'transfer',
        ]);

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
