<?php

declare(strict_types=1);

namespace AshAllenDesign\ShortURL\Models;

use Carbon\Carbon;
use Carbon\CarbonImmutable;
use Illuminate\Database\Eloquent\Factories\Factory;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

/**
 * @property int $id
 * @property int $short_url_id
 * @property string $ip_address
 * @property string $operating_system
 * @property string $operating_system_version
 * @property string $browser
 * @property string $browser_version
 * @property string $device_type
 * @property Carbon|CarbonImmutable $visited_at
 * @property string $referer_url
 * @property Carbon|CarbonImmutable $created_at
 * @property Carbon|CarbonImmutable $updated_at
 */
class ShortURLVisit extends Model
{
    use HasFactory;

    public const DEVICE_TYPE_MOBILE = 'mobile';

    public const DEVICE_TYPE_DESKTOP = 'desktop';

    public const DEVICE_TYPE_TABLET = 'tablet';

    public const DEVICE_TYPE_ROBOT = 'robot';

    /**
     * The table associated with the model.
     *
     * @var string
     */
    protected $table = 'short_url_visits';

    /**
     * The attributes that are mass assignable.
     *
     * @var array<int,string>
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
