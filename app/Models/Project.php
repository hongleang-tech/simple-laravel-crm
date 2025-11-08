<?php

namespace App\Models;

use App\Enums\ProjectStatus;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Database\Eloquent\Relations\MorphMany;
use Illuminate\Database\Eloquent\Relations\MorphOne;

class Project extends Model
{
    /** @use HasFactory<\Database\Factories\ProjectFactory> */
    use HasFactory;

    /**
     * The attributes that are mass assignable.
     *
     * @var array<int, string>
     */
    protected $fillable = [
        'client_id',
        'name',
        'description',
        'status',
        'start_date',
        'end_date',
        'budget',
        'user_id',
    ];

    protected $casts = [
        'status' => ProjectStatus::class,
        'start_date' => 'date',
        'end_date' => 'date',
        'budget' => 'float'
    ];

    public function client(): BelongsTo
    {
        return $this->belongsTo(Client::class);
    }

    public function createdBy(): BelongsTo
    {
        return $this->belongsTo(User::class);
    }

    public function files(): MorphMany
    {
        return $this->morphMany(File::class, 'fileable');
    }

    public function tasks(): HasMany
    {
        return $this->hasMany(Task::class);
    }

    public function address(): MorphOne
    {
        return $this->morphOne(Address::class, 'addressable');
    }
}
