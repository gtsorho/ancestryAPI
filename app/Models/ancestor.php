<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class ancestor extends Model
{
    use HasFactory;

    public $table = "ancestors";

    protected $fillable = [
        'firstname',
        'lastname',
        'othernames',
        'dob',
        'dod',
        'placeofBirth',
        'finalResidence',
        'hometown',
        'FamilyName',
        'territories',
        'occupation',
        'biography',
        'causeofDeath',
        
    ];

    /**
     * Get all of the memories for the ancestor
     *
     * @return \Illuminate\Database\Eloquent\Relations\HasMany
     */
    public function memories()
    {
        return $this->hasMany(memories::class);
    }

    // public function users()
    // {
    //     return $this->(memories::class);
    // }

    /**
     * Get the user that owns the ancestor
     *
     * @return \Illuminate\Database\Eloquent\Relations\BelongsTo
     */
    public function user()
    {
        return $this->belongsTo(User::class);
    }

    /**
     * Get all of the relations for the ancestor
     *
     * @return \Illuminate\Database\Eloquent\Relations\HasMany
     */
    public function relations()
    {
        return $this->hasMany(relations::class);
    }
}
