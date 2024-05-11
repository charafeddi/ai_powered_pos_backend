<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class Product extends Model
{
    use HasFactory; 
    use SoftDeletes;

    protected $hidden = ['created_at', 'updated_at', 'deleted_at'];

    public function productUnit(){
        return $this->belongsTo(ProductUnit::class);
    }

    public function productType(){
        return $this->belongsTo(ProductType::class);
    }

    public function supplier(){
        return $this->belongsTo(Supplier::class);
    }
    public function salesItem(){
        return $this->belongsTo(SalesItem::class);
    }
    
    public function user(){
        return $this->belongsTo(User::class);
    }
}
