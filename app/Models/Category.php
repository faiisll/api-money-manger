<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use App\Models\User;

class Category extends Model

{
    use HasFactory;

    protected $table = 'categories';
    protected $fillable = ['isDefault', 'userId', 'name', 'type', 'variant', 'icon'];

    function user()
    {
        return $this->belongsTo( User::class, "userId");
    }

}
