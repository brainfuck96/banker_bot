<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class DataArh extends Model
{
    //
    protected $fillable = [
        'user_id', 'day', 'month', 'year',
    ];

    public function user()
    {
        return $this->belongsTo('App\User');
    }
}
