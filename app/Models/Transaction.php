<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Transaction extends Model
{
    use HasFactory;
    protected $guarded = ['id'];

    public function customer(){
        return $this->belongsTo(\App\Models\User::class, 'customerId');
    }

    public function user(){
        return $this->belongsTo(\App\Models\User::class, 'userId');
    }

    public function product(){
        return $this->belongsTo(\App\Models\Product::class, 'productId');
    }


}
