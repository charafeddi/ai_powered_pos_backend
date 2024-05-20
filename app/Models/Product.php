<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasOne;

class Product extends Model
{
    use HasFactory; 
    use SoftDeletes;
    protected $fillable = [
        'designation',
        'product_code',
        'quantity',
        'prix_achat',
        'prix_vente',
        'discount',
        'product_type_id',
        'supplier_id',
        'product_unit_id',
        'user_id'
    ];
    
    protected $hidden = ['created_at', 'updated_at', 'deleted_at'];

    public function productUnit():BelongsTo
    {
        return $this->belongsTo(ProductUnit::class);
    }

    public function productType():BelongsTo
    {
        return $this->belongsTo(ProductType::class);
    }

    public function supplier():BelongsTo
    {
        return $this->belongsTo(Supplier::class);
    }
    public function salesItem():HasOne
    {
        return $this->hasOne(SalesItem::class);
    }
    
    public function user():BelongsTo
    {
        return $this->belongsTo(User::class);
    }
}
