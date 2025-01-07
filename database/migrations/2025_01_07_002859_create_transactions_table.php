<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up()
    {
        Schema::create('transactions', function (Blueprint $table) {
            $table->id(); // Chave primária
            $table->uuid('uuid')->unique(); // UUID adicional
            $table->foreignId('user_id')->constrained()->onDelete('cascade'); // FK para o usuário dono da transação
            $table->foreignId('sender_id')->nullable()->constrained('users')->onDelete('cascade'); // Remetente
            $table->foreignId('recipient_id')->nullable()->constrained('users')->onDelete('cascade'); // Destinatário
            $table->decimal('amount', 10, 2); // Valor da transação
            $table->string('type'); // Tipo: deposit, transfer, reversal
            $table->unsignedBigInteger('related_transaction_id')->nullable(); // Para reversões
            $table->timestamps(); // Cria created_at e updated_at
            $table->softDeletes(); // Cria deleted_at
        });
    }



    public function down()
    {
        Schema::dropIfExists('transactions');
    }
};
