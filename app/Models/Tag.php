<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Relations\BelongsToMany;

class Tag extends Model
{
    use HasFactory;

    protected $fillable = ['name'];

    /**
     * Many-to-many relationship (Many Tags can belong to many events)
     */
    public function events(): BelongsToMany
    {
        return $this->belongsToMany(Event::class);
    }
}
