<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class TMatch extends Model
{
    use HasFactory;
    protected $fillable = [
            't1_id',
            't2_id',
            'weak',
            'result',
    ];
}
