<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class Item extends Model
{
    protected $fillable = [
        'code', 
        'name', 
        'category', 
        'unit', 
        'stock', 
        'safety_stock'
    ];

    public function transactions()
    {
        return $this->hasMany(Transaction::class);
    }
}
