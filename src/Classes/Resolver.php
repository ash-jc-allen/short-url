<?php

namespace AshAllenDesign\ShortURL\Classes;

use AshAllenDesign\ShortURL\Exceptions\ValidationException;
use AshAllenDesign\ShortURL\Models\ShortURL;
use AshAllenDesign\ShortURL\Models\ShortURLVisit;
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
     * When constructing this class, ensure that the
     * config variables are validated.
     *
     * @param  Agent|null  $agent
     * @param  Validation|null  $validation
     * @throws ValidationException
     */
    public function __construct(Agent $agent = null, Validation $validation = null)
    {
        if (! $validation) {
            $validation = new Validation();
        }

        $this->agent = $agent ?? new Agent();

        $validation->validateConfig();
    }

    /**
     * Handle the visit. If the short URL is a single
     * use URL and has already been visited, abort
     * the request. If the short URL has tracking
     * enabled, track the visit in the database.
     * If this method is executed successfully,
     * return true.
     *
     * @param  Request  $request
     * @param  ShortURL  $shortURL
     * @return bool
     */
    public function handleVisit(Request $request, ShortURL $shortURL): bool
    {
        if ($shortURL->single_use && count($shortURL->visits)) {
            abort(404);
        }

        if ($shortURL->track_visits) {
            $this->recordVisit($request, $shortURL);
        }

        return true;
    }

    /**
     * Record the visit in the database.
     *
     * @param  Request  $request
     * @param  ShortURL  $shortURL
     * @return ShortURLVisit
     */
    protected function recordVisit(Request $request, ShortURL $shortURL): ShortURLVisit
    {
        $visit = new ShortURLVisit();

        $visit->short_url_id = $shortURL->id;
        $visit->ip_address = config('short-url.tracking.fields.ip_address') ? $request->ip() : null;
        $visit->operating_system = config('short-url.tracking.fields.operating_system') ? $this->agent->platform() : null;
        $visit->operating_system_version = config('short-url.tracking.fields.operating_system_version') ? $this->agent->version($this->agent->platform()) : null;
        $visit->browser = config('short-url.tracking.fields.browser') ? $this->agent->browser() : null;
        $visit->browser_version = config('short-url.tracking.fields.browser_version') ? $this->agent->version($this->agent->browser()) : null;
        $visit->visited_at = now();

        $visit->save();

        return $visit;
    }
}
