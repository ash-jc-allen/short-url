<?php

namespace AshAllenDesign\ShortURL\Classes;

use AshAllenDesign\ShortURL\Events\ShortURLVisited;
use AshAllenDesign\ShortURL\Exceptions\ValidationException;
use AshAllenDesign\ShortURL\Models\ShortURL;
use AshAllenDesign\ShortURL\Models\ShortURLVisit;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Event;
use Jenssegers\Agent\Agent;
use Illuminate\Support\Carbon;

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
     * Handle the visit. Check that the visitor is allowed
     * to visit the URL. If the short URL has tracking
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
        if (! $this->shouldAllowAccess($shortURL)) {
            abort(404);
        }

        $visit = $this->recordVisit($request, $shortURL);

        Event::dispatch(new ShortURLVisited($shortURL, $visit));

        return true;
    }

    /**
     * Determine whether if the visitor is allowed access
     * to the URL. If the short URL is a single use URL
     * and has already been visited, return false. If
     * the URL is not activated yet, return false.
     * If the URL has been deactivated, return
     * false.
     *
     * @param  ShortURL  $shortURL
     * @return bool
     */
    protected function shouldAllowAccess(ShortURL $shortURL): bool
    {
        if ($shortURL->single_use && $shortURL->visits()->count()) {
            return false;
        }

        if (Carbon::now()->isBefore($shortURL->activated_at)) {
            return false;
        }

        if ($shortURL->deactivated_at && Carbon::now()->isAfter($shortURL->deactivated_at)) {
            return false;
        }

        return true;
    }

    /**
     * Record the visit in the database. We record basic
     * information of the visit if tracking even if
     * tracking is not enabled. We do this so that
     * we can check if single-use URLs have been
     * visited before.
     *
     * @param  Request  $request
     * @param  ShortURL  $shortURL
     * @return ShortURLVisit
     */
    protected function recordVisit(Request $request, ShortURL $shortURL): ShortURLVisit
    {
        $visit = new ShortURLVisit();

        $visit->short_url_id = $shortURL->id;
        $visit->visited_at = Carbon::now();

        if ($shortURL->track_visits) {
            $this->trackVisit($shortURL, $visit, $request);
        }

        $visit->save();

        return $visit;
    }

    /**
     * Check which fields should be tracked and then
     * store them if needed. Otherwise, add them
     * as null.
     *
     * @param  ShortURL  $shortURL
     * @param  ShortURLVisit  $visit
     * @param  Request  $request
     */
    protected function trackVisit(ShortURL $shortURL, ShortURLVisit $visit, Request $request): void
    {
        if ($shortURL->track_ip_address) {
            $visit->ip_address = $request->ip();
        }

        if ($shortURL->track_operating_system) {
            $visit->operating_system = $this->agent->platform();
        }

        if ($shortURL->track_operating_system_version) {
            $visit->operating_system_version = $this->agent->version($this->agent->platform());
        }

        if ($shortURL->track_browser) {
            $visit->browser = $this->agent->browser();
        }

        if ($shortURL->track_browser_version) {
            $visit->browser_version = $this->agent->version($this->agent->browser());
        }

        if ($shortURL->track_referer_url) {
            $visit->referer_url = $request->headers->get('referer');
        }

        if ($shortURL->track_device_type) {
            $visit->device_type = $this->guessDeviceType();
        }
    }

    /**
     * Guess and return the device type that was used to
     * visit the short URL.
     *
     * @return string
     */
    protected function guessDeviceType(): string
    {
        if ($this->agent->isDesktop()) {
            return ShortURLVisit::DEVICE_TYPE_DESKTOP;
        }

        if ($this->agent->isMobile()) {
            return ShortURLVisit::DEVICE_TYPE_MOBILE;
        }

        if ($this->agent->isTablet()) {
            return ShortURLVisit::DEVICE_TYPE_TABLET;
        }

        if ($this->agent->isRobot()) {
            return ShortURLVisit::DEVICE_TYPE_ROBOT;
        }

        return '';
    }
}
