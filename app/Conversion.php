<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class Conversion extends Model
{
    //
    protected $fillable = [
        'user_id', 'cur', 'ask', 'temp',
    ];

    public function user()
    {
        return $this->belongsTo('App\User');
    }
}
