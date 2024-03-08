<?php

namespace App\Models;

use App\Models\Product;
use App\Models\Category;
use App\Models\Supplier;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Factories\HasFactory;


class Product extends Model
{
    use HasFactory;

    protected $guarded = ['id'];

    public function supplier(){
        return $this->belongsTo(Supplier::class, 'supplierId');
    }

    public function category(){
        return $this->belongsTo(Category::class, 'categoryId');
    }

}
