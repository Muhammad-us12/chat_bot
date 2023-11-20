<?php

namespace App\Domain\Bargain\Services;

use Closure;
use DOMXPath;
use DOMDocument;

class JsonMicrodataParser
{
    public function handle($sourceCode, Closure $next)
    {
        $parsedMicrodata = $this->parse($sourceCode);
        if (\is_null($parsedMicrodata)) {
            return $next($sourceCode);
        } else {
            return $parsedMicrodata;
        }
    }

    public function parse(string $jsonString): ?array
    {
        $dom = new DOMDocument();
        $dom->loadHTML($jsonString, LIBXML_NOERROR);
        $xpath = new DOMXPath($dom);

        $microdataScriptTagSearchResult = $xpath->query('//script[@type="application/ld+json"]');
        if (!$microdataScriptTagSearchResult->count()) {
            return [];
        }
        $microdataScriptTag = $microdataScriptTagSearchResult->item(0)->nodeValue;
        $microdata = json_decode($microdataScriptTag, true);

        return $microdata['offers'];
    }
}
