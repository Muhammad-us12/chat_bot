<?php

namespace App\Domain\Bargain\Services;

use Closure;
use DOMXPath;
use DOMElement;
use DOMDocument;
use DOMNodeList;

class HtmlMicrodataParser
{
    private DOMXPath $domXpath;

    public function handle($sourceCode, Closure $next)
    {
        $parsedMicrodata = $this->parse($sourceCode);
        if (\is_null($parsedMicrodata)) {
            return $next($sourceCode);
        } else {
            return $parsedMicrodata;
        }
    }

    public function parse(string $sourceCode): ?array
    {
        $this->loadHtmlDom($sourceCode);;

        $productMicrodata = $this->queryProduct();
        if (\is_null($productMicrodata)) {
            return [];
        }
        $offerNodes = $this->queryProductOffers($productMicrodata);
        if (is_null($offerNodes)) {
            return [];
        }

        $microdata =  $this->getOfferAttributes($offerNodes);

        return $this->normalizeMicrodata($microdata);
    }

    private function normalizeMicrodata(array $microdata): array
    {
        $normalized = [];
        foreach ($microdata as $dataKey => $dataPoint) {
            if (strtolower($dataKey) == 'availability') {
                $normalized[$dataKey] = \is_int(\strpos(\strtolower($dataPoint), 'instock')) ? 'InStock' : 'N/A';
            } elseif (strtolower($dataKey) == 'itemcondition') {
                $normalized[$dataKey] = \is_int(\strpos(\strtolower($dataPoint), 'new')) ? 'New' : 'N/A';
            } elseif (!empty($dataPoint)) {
                $normalized[$dataKey] = $dataPoint;
            }
        }

        return $normalized;
    }

    private function getOfferAttributes(DOMNodeList $offerNodes): array
    {
        $microdata = [];
        foreach ($offerNodes as $offerNode) {
            if ($offerNode->hasAttributes() && $offerNode->getAttribute('itemprop')) {
                $microdata[$offerNode->getAttribute('itemprop')] = trim($offerNode->getAttribute('content') ?: $offerNode->textContent);
            }
        }

        return $microdata;
    }

    private function queryProductOffers(DOMElement $productDom): ?DOMNodeList
    {
        # Missed the first character as we do not know what will be the word case, and query is case-sensitive
        $xpathQueryResult = $this->domXpath->query('.//*[contains(@itemprop, "ffers")]', $productDom);
        return $xpathQueryResult->item(0)?->childNodes;
    }

    private function queryProduct(): ?DOMElement
    {
        # Missed the first character as we do not know what will be the word case, and query is case-sensitive
        $itemPropSearchResult = $this->domXpath->query('//*[contains(@itemtype, "roduct")]');

        return $itemPropSearchResult->item(0);
    }

    private function loadHtmlDom(string $sourceCode)
    {
        $dom = new DOMDocument();
        $dom->loadHTML($sourceCode, LIBXML_NOERROR);
        $this->domXpath = new DOMXPath($dom);
    }
}
