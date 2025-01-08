<?php

namespace Tests\Feature;

use Tests\TestCase;
use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Support\Facades\Hash;

class UserTest extends TestCase
{
    use RefreshDatabase;

    /** @test */
    public function it_creates_a_user_successfully()
    {
        // Dados simulados de um usuário
        $userData = [
            'uuid' => (string) \Illuminate\Support\Str::uuid(),
            'name' => 'Test User',
            'email' => 'testuser@example.com',
            'password' => Hash::make('password123'), // Usando Hash::make
            'role' => 'user',
            'balance' => 500.00,
        ];

        // Criar o usuário
        $user = User::create($userData);

        // Verificar se o usuário foi criado corretamente no banco de dados
        $this->assertDatabaseHas('users', [
            'email' => 'testuser@example.com',
            'name' => 'Test User',
        ]);

        // Verificar se a senha foi armazenada como um hash
        $this->assertTrue(Hash::check('password123', $user->password));
    }
}
