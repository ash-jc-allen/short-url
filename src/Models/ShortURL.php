<?php

namespace AshAllenDesign\ShortURL\Models;

use Carbon\Carbon;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\HasMany;

/**
 * Class ShortURL
 *
 * @property int id
 * @property string destination_url
 * @property string short_url
 * @property boolean single_use
 * @property Carbon created_at
 * @property Carbon updated_at
 *
 * @package AshAllenDesign\ShortURL\Models
 */
class ShortURL extends Model
{
    /**
     * The table associated with the model.
     *
     * @var string
     */
    protected $table = 'short_urls';

    /**
     * The attributes that are mass assignable.
     *
     * @var array
     */
    protected $fillable = [
        'destination_url',
        'short_url',
        'single_use',
    ];

    /**
     * The attributes that should be mutated to dates.
     *
     * @var array
     */
    protected $dates = [
        'created_at',
        'updated_at',
    ];

    /**
     * The attributes that should be cast to native types.
     *
     * @var array
     */
    protected $casts = [
        'single_use' => 'boolean',
    ];

    /**
     * A short URL can be visited many times.
     *
     * @return HasMany
     */
    public function visits(): HasMany
    {
        return $this->hasMany(ShortURLVisits::class);
    }
}