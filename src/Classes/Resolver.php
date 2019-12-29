<?php

namespace AshAllenDesign\ShortURL\Classes;

use AshAllenDesign\ShortURL\Models\ShortURLVisit;
use AshAllenDesign\ShortURL\Models\ShortURL;
use Illuminate\Http\Request;

class Resolver
{
    /**
     * @param  Request  $request
     * @param  ShortURL  $shortURL
     */
    public function handleVisit(Request $request, ShortURL $shortURL): void
    {
        if ($shortURL->single_use && count($shortURL->visits)) {
            abort(404);
        }

        $this->recordVisit($request, $shortURL);
    }

    /**
     * @param  Request  $request
     * @param  ShortURL  $shortURL
     */
    protected function recordVisit(Request $request, ShortURL $shortURL): void
    {
        $visit = new ShortURLVisit();

        $visit->short_url_id = $shortURL->id;
        $visit->ip_address = $request->ip();
        $visit->operating_system = 'hello123';
        $visit->operating_system_version = 'hello123';
        $visit->browser = 'hello123';
        $visit->browser_version = 'hello123';
        $visit->visited_at = now();

        $visit->save();
    }
}