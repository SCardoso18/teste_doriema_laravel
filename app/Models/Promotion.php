<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Promotion extends Model
{
    protected $table = 'promotions';

    public $timestamps = false;

    protected $fillable = [
        'product_01', 'product_02', 'number_days', 'description', 'status'
    ];
}
