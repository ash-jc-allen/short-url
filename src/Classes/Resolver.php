<?php

namespace AshAllenDesign\ShortURL\Classes;

use AshAllenDesign\ShortURL\Models\ShortURLVisit;
use AshAllenDesign\ShortURL\Models\ShortURL;
use Illuminate\Http\Request;
use Jenssegers\Agent\Agent;

class Resolver
{
    /**
     * A class that can be used to try and detect the
     * browser and operating system of the visitor.
     *
     * @var Agent
     */
    private $agent;

    /**
     * Resolver constructor.
     *
     * @param  Agent  $agent
     */
    public function __construct(Agent $agent)
    {
        $this->agent = $agent;
    }

    /**
     * @param  Request  $request
     * @param  ShortURL  $shortURL
     */
    public function handleVisit(Request $request, ShortURL $shortURL): void
    {
        if ($shortURL->single_use && count($shortURL->visits)) {
            abort(404);
        }

        if($shortURL->track_visits) {
            $this->recordVisit($request, $shortURL);
        }
    }

    /**
     * Record the visit in the database.
     *
     * @param  Request  $request
     * @param  ShortURL  $shortURL
     */
    protected function recordVisit(Request $request, ShortURL $shortURL): void
    {
        $visit = new ShortURLVisit();

        $visit->short_url_id = $shortURL->id;

        // TODO CHECK EACH INDIVIDUAL PIECE FOR TRACKING.
        $visit->ip_address = $request->ip();
        $visit->operating_system = $this->agent->platform();
        $visit->operating_system_version = $this->agent->version($this->agent->platform());
        $visit->browser = $this->agent->browser();
        $visit->browser_version = $this->agent->version($this->agent->browser());
        $visit->visited_at = now();

        $visit->save();
    }
}