<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasOne;

class SaleItem extends Model
{
    use HasFactory;
    use SoftDeletes;
    protected $fillable =[
        'quantity',
        'unit_price',
        'subtotal',
        'sale_id',
        'product_id',
    ];
    protected $hidden = ['created_at', 'updated_at', 'deleted_at'];
    
    public function product():BelongsTo
    {
        return $this->belongsTo(Product::class);
    } 

    public function sale():BelongsTo
    {
        return $this->belongsTo(Sale::class);
    }
}