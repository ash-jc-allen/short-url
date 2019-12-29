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
     * @param  Agent  $agent
     * @param  Validation  $validation
     * @throws ValidationException
     */
    public function __construct(Agent $agent, Validation $validation = null)
    {
        if (! $validation) {
            $validation = new Validation();
        }

        $validation->validateConfig();

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

        if ($shortURL->track_visits) {
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
        $visit->ip_address = config('short-url.tracking.fields.ip_address') ? $request->ip() : null;
        $visit->operating_system = config('short-url.tracking.fields.operating_system') ? $this->agent->platform() : null;
        $visit->operating_system_version = config('short-url.tracking.fields.operating_system_version') ? $this->agent->version($this->agent->platform()) : null;
        $visit->browser = config('short-url.fields.tracking.browser') ? $this->agent->browser() : null;
        $visit->browser_version = config('short-url.tracking.fields.browser_version') ? $this->agent->version($this->agent->browser()) : null;
        $visit->visited_at = now();

        $visit->save();
    }
}
