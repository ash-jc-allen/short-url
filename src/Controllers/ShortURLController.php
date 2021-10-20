<?php

namespace AshAllenDesign\ShortURL\Controllers;

use AshAllenDesign\ShortURL\Classes\Resolver;
use AshAllenDesign\ShortURL\Models\ShortURL;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;

class ShortURLController
{
    /**
     * Redirect the user to the intended destination
     * URL. If the default route has been disabled
     * in the config but the controller has been
     * reached using that route, return HTTP
     * 404.
     *
     * @param  Request  $request
     * @param  Resolver  $resolver
     * @param  string  $shortURLKey
     * @return RedirectResponse
     */
    public function __invoke(Request $request, Resolver $resolver, string $shortURLKey): RedirectResponse
    {
        if ($request->route()->getName() === 'short-url.invoke'
            && config('short-url.disable_default_route')) {
            abort(404);
        }

        $shortURL = ShortURL::where('url_key', $shortURLKey)->firstOrFail();

        $resolver->handleVisit(request(), $shortURL);

        // If the short url should forward query params, rebuild destination url.
        if($shortURL->forward_query_params) {
            return redirect($this->forwardQueryParams($request, $shortURL), $shortURL->redirect_status_code);
        }

        return redirect($shortURL->destination_url, $shortURL->redirect_status_code);
    }

    /**
     * Rebuild the destination url by merging source query params
     * (ie: query params passed to the short url)
     * and destination query params.
     *
     * @param Request $request
     * @param ShortURL $shortURL
     * @return string
     */
    private function forwardQueryParams(Request $request, ShortURL $shortURL): string
    {
        // Get destination url
        $destinationParsedUrl = parse_url($shortURL->destination_url);

        // Rebuild destination without query string
        $rebuiltDestinationUrl = $destinationParsedUrl['scheme'].'://'.$destinationParsedUrl['host'].($destinationParsedUrl['path'] ?? '');

        // Parse destination query string and put it into an array
        parse_str($destinationParsedUrl['query'] ?? '', $destinationQuery);

        // Get source query parameters (passed to short url)
        $sourceQuery = $request->query();

        // Merge the two query parameters arrays
        // (by default, the destination query parameters will override the source ones
        // because a parameters passed to the short url should never modify the destination)
        $rebuiltQuery = array_merge($sourceQuery, $destinationQuery);

        // Rebuild destination query string
        $rebuiltQueryString = http_build_query($rebuiltQuery);

        // Add query string to url if exists
        if(!empty($rebuiltQuery)) {
            $rebuiltDestinationUrl .= '?' . $rebuiltQueryString;
        }

        return $rebuiltDestinationUrl;
    }
}
