<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class Sales extends Model
{
    use HasFactory;
    use SoftDeletes;

    protected $hidden = ['created_at', 'updated_at', 'deleted_at'];
    
    public function clients(){
        return $this->belongsTo(Client::class);
    }

    public function invoice(){
        return $this->hasOne(Invoice::class);
    }
    
    public function users(){
        return $this->belongsTo(User::class);
    }

    public function salesItem (){
        return $this->hasMany(SalesItem::class);
    }
}
