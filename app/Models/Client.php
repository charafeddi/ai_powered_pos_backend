<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class Client extends Model
{
    use HasFactory;
    use SoftDeletes;
    
    protected $hidden = ['created_at', 'updated_at'];

    protected $fillable = [
        'name' ,
        'email',
        'phone',
        'address',
        'city',
        'country',
        'postal_code',
        'user_id',
    ];

    public function sale():HasMany
    {
        return $this->hasMany(Sale::class);
    }
    
    public function user():BelongsTo
    {
        return $this->belongsTo(User::class);
    }

}
