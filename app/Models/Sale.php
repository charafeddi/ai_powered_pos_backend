<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class Sale extends Model
{
    use HasFactory;
    use SoftDeletes;
    protected $fillable =[
        'total_amount',
        'amount_paid',
        'paid',
        'user_id',
        'client_id',
    ];
    protected $hidden = ['created_at', 'updated_at', 'deleted_at'];
    
    public function client():BelongsTo
    {
        return $this->belongsTo(Client::class);
    }

    public function invoice(){
        return $this->hasOne(Invoice::class);
    }
    
    public function user():BelongsTo
    {
        return $this->belongsTo(User::class);
    }

    public function saleItems (){
        return $this->hasMany(SaleItem::class);
    }
}
