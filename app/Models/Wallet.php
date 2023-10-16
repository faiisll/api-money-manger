<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Wallet extends Model
{
    use HasFactory;

    protected $table = 'wallets';
    protected $fillable = ['isDefault', 'userId', 'name', 'balance'];
    protected $appends = ['currentBalance', 'finalBalance'];
    protected $casts = [
        'balance' => 'integer',
        'isDefault' => 'boolean'
    ];

    function getCurrentBalanceAttribute(){
        return $this->transactions->sum('amount');
    }

    function getFinalBalanceAttribute(){
        $totalTransactions = $this->transactions()->sum('amount');
        return $this->balance + $totalTransactions;

    }

    function user()
    {
        return $this->belongsTo( User::class, "userId");
    }

    function transactions(){
        return $this->hasMany(Transaction::class, 'walletId');
    }
}
