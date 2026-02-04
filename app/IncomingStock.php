<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class IncomingStock extends Model
{
    protected $fillable = ['item_id', 'user_id', 'qty'];

    public function item()
    {
        return $this->belongsTo(Item::class);
    }

    public function user()
    {
        return $this->belongsTo(User::class);
    }
}
