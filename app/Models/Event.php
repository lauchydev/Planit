<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class Event extends Model
{

    use HasFactory;

    protected $fillable = [
        'title',
        'description',
        'start_time',
        'end_time',
        'location',
        'capacity',
        'organiser_id',
    ];

    /* Data casting for the database */
    protected $casts = [
        'start_time' => 'datetime',
        'end_time' => 'datetime',
    ];

    /* Many-to-one relationship where many events can belong to one user */
    public function organiser(): BelongsTo {
        return $this->belongsTo(User::class, 'organiser_id');
    }
}
