<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class memories extends Model
{
    use HasFactory;

    protected $fillable = [
        'ancestor_id',
        'images',
        'imageDescription',
    ];
}
