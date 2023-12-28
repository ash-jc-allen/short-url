<?php

namespace AshAllenDesign\ShortURL\Models;

use Carbon\Carbon;
use Illuminate\Database\Eloquent\Factories\Factory;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

/**
 * Class ShortURLVisit.
 *
 * @property int $id
 * @property int $short_url_id
 * @property string $ip_address
 * @property string $operating_system
 * @property string $operating_system_version
 * @property string $browser
 * @property string $browser_version
 * @property string $device_type
 * @property Carbon $visited_at
 * @property string $referer_url
 * @property Carbon $created_at
 * @property Carbon $updated_at
 */
class ShortURLVisit extends Model
{
    use HasFactory;

    const DEVICE_TYPE_MOBILE = 'mobile';

    const DEVICE_TYPE_DESKTOP = 'desktop';

    const DEVICE_TYPE_TABLET = 'tablet';

    const DEVICE_TYPE_ROBOT = 'robot';

    /**
     * The table associated with the model.
     *
     * @var string
     */
    protected $table = 'short_url_visits';

    /**
     * The attributes that are mass assignable.
     *
     * @var string[]
     */
    protected $fillable = [
        'short_url_id',
        'ip_address',
        'operating_system',
        'operating_system_version',
        'browser',
        'browser_version',
        'visited_at',
        'referer_url',
        'device_type',
    ];

    /**
     * The attributes that should be mutated to dates.
     *
     * @deprecated This field is no longer used in Laravel 10 and above.
     *             It will be removed in a future release.
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
     * @var array<string, string>
     */
    protected $casts = [
        'short_url_id' => 'integer',
        'visited_at' => 'datetime',
    ];

    public function __construct(array $attributes = [])
    {
        parent::__construct($attributes);

        if (config('short-url.connection')) {
            $this->setConnection(config('short-url.connection'));
        }
    }

    /**
     * @return Factory<ShortURLVisit>
     */
    protected static function newFactory()
    {
        $factoryConfig = config('short-url.factories');

        $modelFactory = app($factoryConfig[__CLASS__]);

        return $modelFactory::new();
    }

    /**
     * A URL visit belongs to one specific shortened URL.
     *
     * @return BelongsTo<ShortURL, ShortURLVisit>
     */
    public function shortURL(): BelongsTo
    {
        return $this->belongsTo(ShortURL::class, 'short_url_id');
    }
}
