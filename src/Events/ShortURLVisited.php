<?php

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
     *
     * @var ShortURL
     */
    public $shortURL;

    /**
     * Details of the visitor that visited the short URL.
     *
     * @var ShortURLVisit
     */
    public $shortURLVisit;

    /**
     * Get user, if authenticated
     *
     * @var User
     */
    public $user;

    /**
     * Create a new event instance.
     *
     * @param  ShortURL  $shortURL
     * @param  ShortURLVisit  $shortURLVisit
     */
    public function __construct(ShortURL $shortURL, ShortURLVisit $shortURLVisit, ?$user)
    {
        $this->shortURL = $shortURL;
        $this->shortURLVisit = $shortURLVisit;
        $this->user = $user;
    }
}
