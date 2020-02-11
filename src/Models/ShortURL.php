<?php

namespace AshAllenDesign\ShortURL\Models;

use Carbon\Carbon;
use Illuminate\Database\Eloquent\Collection;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\HasMany;

/**
 * Class ShortURL.
 *
 * @property int id
 * @property string destination_url
 * @property string default_short_url
 * @property string url_key
 * @property bool single_use
 * @property bool track_visits
 * @property int redirect_status_code
 * @property Carbon created_at
 * @property Carbon updated_at
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
        'default_short_url',
        'url_key',
        'single_use',
        'track_visits',
        'redirect_status_code',
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
        'single_use'   => 'boolean',
        'track_visits' => 'boolean',
    ];

    /**
     * A short URL can be visited many times.
     *
     * @return HasMany
     */
    public function visits(): HasMany
    {
        return $this->hasMany(ShortURLVisit::class, 'short_url_id');
    }

    /**
     * A helper method that can be used for finding
     * a ShortURL model with the given URL key.
     *
     * @param  string  $URLKey
     * @return ShortURL|null
     */
    public static function findByKey(string $URLKey): ?self
    {
        return self::where('url_key', $URLKey)->first();
    }

    /**
     * A helper method that can be used for finding
     * all of the ShortURL models with the given
     * destination URL.
     *
     * @param  string  $destinationURL
     * @return Collection
     */
    public static function findByDestinationURL(string $destinationURL): Collection
    {
        return self::where('destination_url', $destinationURL)->get();
    }
}
