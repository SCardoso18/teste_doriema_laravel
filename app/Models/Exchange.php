<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Exchange extends Model
{
    protected $table = 'exchange_tb';

    public $timestamps = false;

    protected $fillable = [
        'exchange'
    ];
}
