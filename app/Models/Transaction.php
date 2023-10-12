<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use App\Models\Wallet;
use App\Models\Category;

class Transaction extends Model
{
    use HasFactory;

    protected $table = 'transactions';
    protected $fillable = ['name', 'desc', 'walletId', 'categoryId', 'date', 'amount' , 'userId'];

    function wallet()
    {
        return $this->belongsTo( Wallet::class, "walletId");
    }
    function category()
    {
        return $this->belongsTo( Category::class, "categoryId");
    }
}
