<?php

declare(strict_types=1);

namespace AshAllenDesign\ShortURL\Events;

use AshAllenDesign\ShortURL\Models\ShortURL;
use AshAllenDesign\ShortURL\Models\ShortURLVisit;
use Illuminate\Broadcasting\InteractsWithSockets;
use Illuminate\Foundation\Events\Dispatchable;
use Illuminate\Queue\SerializesModels;

class ShortURLVisited
{
    use Dispatchable, InteractsWithSockets, SerializesModels;

    /**
     * The short URL that was visited.
     */
    public ShortURL $shortURL;

    /**
     * Details of the visitor that visited the short URL.
     */
    public ShortURLVisit $shortURLVisit;

    public function __construct(ShortURL $shortURL, ShortURLVisit $shortURLVisit)
    {
        $this->shortURL = $shortURL;
        $this->shortURLVisit = $shortURLVisit;
    }
}
