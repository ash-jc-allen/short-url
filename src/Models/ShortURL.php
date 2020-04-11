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
 * @property bool track_ip_address
 * @property bool track_operating_system
 * @property bool track_operating_system_version
 * @property bool track_browser
 * @property bool track_browser_version
 * @property bool track_referer_url
 * @property bool track_device_type
 * @property Carbon activated_at
 * @property Carbon deactivated_at
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
        'track_ip_address',
        'track_operating_system',
        'track_operating_system_version',
        'track_browser',
        'track_browser_version',
        'track_referer_url',
        'track_device_type',
        'activated_at',
        'deactivated_at',
    ];

    /**
     * The attributes that should be mutated to dates.
     *
     * @var array
     */
    protected $dates = [
        'activated_at',
        'deactivated_at',
        'created_at',
        'updated_at',
    ];

    /**
     * The attributes that should be cast to native types.
     *
     * @var array
     */
    protected $casts = [
        'single_use'                     => 'boolean',
        'track_visits'                   => 'boolean',
        'track_ip_address'               => 'boolean',
        'track_operating_system'         => 'boolean',
        'track_operating_system_version' => 'boolean',
        'track_browser'                  => 'boolean',
        'track_browser_version'          => 'boolean',
        'track_referer_url'              => 'boolean',
        'track_device_type'              => 'boolean',
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

    /**
     * A helper method to determine whether if tracking
     * is currently enabled for the short URL.
     *
     * @return bool
     */
    public function trackingEnabled(): bool
    {
        return $this->track_visits;
    }

    /**
     * Return an array containing the fields that are
     * set to be tracked for the short URL.
     *
     * @return array
     */
    public function trackingFields(): array
    {
        $fields = [];

        if ($this->track_ip_address) {
            $fields[] = 'ip_address';
        }

        if ($this->track_operating_system) {
            $fields[] = 'operating_system';
        }

        if ($this->track_operating_system_version) {
            $fields[] = 'operating_system_version';
        }

        if ($this->track_browser) {
            $fields[] = 'browser';
        }

        if ($this->track_browser_version) {
            $fields[] = 'browser_version';
        }

        if ($this->track_referer_url) {
            $fields[] = 'referer_url';
        }

        if ($this->track_device_type) {
            $fields[] = 'device_type';
        }

        return $fields;
    }
}
