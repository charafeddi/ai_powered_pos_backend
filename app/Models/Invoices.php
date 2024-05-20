<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class Invoices extends Model
{
    use HasFactory;
    use SoftDeletes;
    
    protected $hidden = ['created_at', 'updated_at', 'deleted_at'];

    public function sale():BelongsTo
    {
        return $this->belongsTo(Sale::class);
    }
}
