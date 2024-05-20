<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasOne;


class Supplier extends Model
{
    use HasFactory;
    use SoftDeletes;
    
    protected $hidden = ['created_at', 'updated_at', 'deleted_at'];

    protected $fillable = [
        'name',
        'email',
        'phone',
        'address',
        'city',
        'country',
        'fax',
        'postal_code',
        'user_id',
    ];
    
    public function product():HasOne
    {
        return $this->hasMany(Product::class);
    }

    public function user():BelongsTo
    {
        return $this->belongsTo(User::class);
    }
}
