<?php

declare(strict_types=1);

namespace AshAllenDesign\ShortURL\Models;

use Carbon\CarbonInterface;
use Illuminate\Database\Eloquent\Collection;
use Illuminate\Database\Eloquent\Factories\Factory;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\HasMany;

/**
 * @property int $id
 * @property string $destination_url
 * @property string $default_short_url
 * @property string $url_key
 * @property bool $single_use
 * @property bool $forward_query_params
 * @property bool $track_visits
 * @property int $redirect_status_code
 * @property bool $track_ip_address
 * @property bool $track_operating_system
 * @property bool $track_operating_system_version
 * @property bool $track_browser
 * @property bool $track_browser_version
 * @property bool $track_referer_url
 * @property bool $track_device_type
 * @property CarbonInterface $activated_at
 * @property CarbonInterface|null $deactivated_at
 * @property CarbonInterface $created_at
 * @property CarbonInterface $updated_at
 */
class ShortURL extends Model
{
    /**
     * @use Factory<ShortURL>
     */
    use HasFactory;

    /**
     * The table associated with the model.
     *
     * @var string
     */
    protected $table = 'short_urls';

    /**
     * The attributes that are mass assignable.
     *
     * @var array<int,string>
     */
    protected $fillable = [
        'destination_url',
        'default_short_url',
        'url_key',
        'single_use',
        'forward_query_params',
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
     * The attributes that should be cast to native types.
     *
     * @var array<string, string>
     */
    protected $casts = [
        'single_use' => 'boolean',
        'forward_query_params' => 'boolean',
        'track_visits' => 'boolean',
        'track_ip_address' => 'boolean',
        'track_operating_system' => 'boolean',
        'track_operating_system_version' => 'boolean',
        'track_browser' => 'boolean',
        'track_browser_version' => 'boolean',
        'track_referer_url' => 'boolean',
        'track_device_type' => 'boolean',
        'activated_at' => 'datetime',
        'deactivated_at' => 'datetime',
    ];

    public function __construct(array $attributes = [])
    {
        parent::__construct($attributes);

        if (config('short-url.connection')) {
            $this->setConnection(config('short-url.connection'));
        }
    }

    /**
     * @return Factory<ShortURL>
     */
    protected static function newFactory()
    {
        $factoryConfig = config('short-url.factories');

        $modelFactory = app($factoryConfig[__CLASS__]);

        return $modelFactory::new();
    }

    /**
     * A short URL can be visited many times.
     *
     * @return HasMany<ShortURLVisit>
     */
    public function visits(): HasMany
    {
        return $this->hasMany(ShortURLVisit::class, 'short_url_id');
    }

    /**
     * A helper method that can be used for finding a ShortURL model with the
     * given URL key.
     */
    public static function findByKey(string $URLKey): ?self
    {
        return self::where('url_key', $URLKey)->first();
    }

    /**
     * A helper method that can be used for finding all the ShortURL models
     * with the given destination URL.
     *
     * @return Collection<int, ShortURL>
     */
    public static function findByDestinationURL(string $destinationURL): Collection
    {
        return self::where('destination_url', $destinationURL)->get();
    }

    /**
     * A helper method to determine whether if tracking is currently enabled
     * for the short URL.
     */
    public function trackingEnabled(): bool
    {
        return $this->track_visits;
    }

    /**
     * Return an array containing the fields that are set to be tracked for the
     * short URL.
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
