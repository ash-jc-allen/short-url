<?php

namespace AshAllenDesign\ShortURL\Middleware;

use AshAllenDesign\ShortURL\Models\ShortURL;
use Closure;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;

class ShortURLMiddleware
{
    /**
     * Set the ShortURL instance as an attribute on the request.
     *
     * @param  Request  $request
     * @param  Closure  $next
     * @return RedirectResponse
     */
    public function handle(Request $request, Closure $next): RedirectResponse
    {
        $shortURL = ShortURL::where('url_key', $request->route('shortURLKey'))->firstOrFail();
        $request->attributes->add(['shortURL' => $shortURL]);

        return $next($request);
    }
}
