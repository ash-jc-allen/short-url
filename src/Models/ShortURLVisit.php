<?php

namespace AshAllenDesign\ShortURL\Models;

use Carbon\Carbon;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

/**
 * Class ShortURLVisit.
 *
 * @property int id
 * @property int short_url_id
 * @property string ip_address
 * @property string operating_system
 * @property string operating_system_version
 * @property string browser
 * @property string browser_version
 * @property string device_type
 * @property Carbon visited_at
 * @property Carbon referer_url
 * @property Carbon created_at
 * @property Carbon updated_at
 */
class ShortURLVisit extends Model
{
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
        'referer_url',
        'device_type',
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
     * @return BelongsTo
     */
    public function shortURL(): BelongsTo
    {
        return $this->belongsTo(ShortURL::class);
    }
}
