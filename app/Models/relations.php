<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class relations extends Model
{
    use HasFactory;

    public $table = "relations";
    
    protected $fillable = [
        'ancestor_id',
        'relativeName',
        'relation',
    ];
    public function ancestor()
    {
        return $this->belongsTo(ancestor::class);
    }
}
