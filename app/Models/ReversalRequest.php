<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class ReversalRequest extends Model
{
    use HasFactory;

    protected $table = 'reversal_requests';

    protected $fillable = [
        'uuid',
        'user_uuid',
        'transaction_uuid',
        'comment',
        'status',
    ];

    public $incrementing = false; // Para usar UUIDs como chave primária
    protected $keyType = 'string';

    public function user()
    {
        return $this->belongsTo(User::class, 'user_uuid', 'uuid');
    }

    public function transaction()
    {
        return $this->belongsTo(Transaction::class, 'transaction_uuid', 'uuid');
    }
}
