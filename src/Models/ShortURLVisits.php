<?php

namespace AshAllenDesign\ShortURL\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

/**
 * Class ShortURLVisits
 *
 * @property int id
 * @property int short_url_id
 * @property string ip_address
 * @property string operating_system
 * @property string operating_system_version
 * @property string browser
 * @property string browser_version
 * @property \Carbon\Carbon visited_at
 * @property \Carbon\Carbon created_at
 * @property \Carbon\Carbon updated_at
 *
 * @package AshAllenDesign\ShortURL\Models
 */
class ShortURLVisits extends Model
{
    /**
     * The attributes that are mass assignable.
     *
     * @var array
     */
    protected $fillable = [
        'short_url_id',
        'ip_address',
        'operating_system',
        'operating_system_version',
        'browser',
        'browser_version',
        'visited_at',
    ];

    /**
     * The attributes that should be mutated to dates.
     *
     * @var array
     */
    protected $dates = [
        'visited_at',
        'created_at',
        'updated_at',
    ];

    /**
     * The attributes that should be cast to native types.
     *
     * @var array
     */
    protected $casts = [
        'short_url_id' => 'integer',
    ];

    /**
     * A URL visit belongs to one specific shortened URL.
     *
     * @return \Illuminate\Database\Eloquent\Relations\BelongsTo
     */
    public function shortURL(): BelongsTo
    {
        return $this->belongsTo(ShortURL::class);
    }
}