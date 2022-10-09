<?php 

use AshAllenDesign\ShortURL\Classes\Builder;

if(!function_exists('short_url')) {    
    /**
     * Create a new Short URL Builder instance from the given url.
     *
     * @param  string $url
     * @return \AshAllenDesign\ShortURL\Classes\Builder
     * 
     * @throws \AshAllenDesign\ShortURL\Exceptions\ShortURLException
     */
    function short_url(string $url) {
        return (new Builder())->destinationUrl($url);
    }
}