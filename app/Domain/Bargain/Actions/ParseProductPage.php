<?php

namespace App\Domain\Bargain\Actions;

use App\Clients\PhantomJS\Client;
use Illuminate\Pipeline\Pipeline;
use App\Domain\Bargain\Services\HtmlMicrodataParser;
use App\Domain\Bargain\Services\JsonMicrodataParser;

class ParseProductPage
{
    public function __construct(private Client $phantomJsClient)
    {
    }

    public function execute(string $url)
    {
        $productPageHtml = $this->phantomJsClient->crawl($url);
        
        $pipes = [
            JsonMicrodataParser::class,
            HtmlMicrodataParser::class
        ];

        return app(Pipeline::class)->send($productPageHtml)->through($pipes)->thenReturn();
    }
}
