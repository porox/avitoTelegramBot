<?php
/**
 * Created by PhpStorm.
 * User: yury
 * Date: 2019-10-07
 * Time: 18:39
 */

namespace App\Service;

use App\Object\AvitoItem;
use GuzzleHttp\ClientInterface;
use KubAT\PhpSimple\HtmlDomParser;
use simple_html_dom\simple_html_dom;
use simple_html_dom\simple_html_dom_node;

/**
 * Class AvitoParser
 *
 * @package App\Service
 */
class AvitoParser
{

    /**
     * @var ClientInterface
     */
    protected $client;

    /**
     * @var string
     */
    protected $region;

    /**
     * AvitoParser constructor.
     *
     * @param ClientInterface $client
     */
    public function __construct(ClientInterface $client, string $region = 'moskva')
    {
        $this->client = $client;
        $this->region = $region;
    }

    /**
     * @param string $searchQuery
     *
     * @return \Generator
     */
    public function parse(string $searchQuery)
    {
        $arr      = $this->getItems($this->getLink($searchQuery));
        $items    = $arr['items'] ?? [];
        $nextPage = $arr['nextPage'] ?? null;
        while ($nextPage != null || count($items) > 0) {
            /**
             * @var simple_html_dom_node $item
             */
            foreach ($items as $item) {
                $titleLink = $item->find('.item-description-title-link', 0);
                if ($titleLink != null) {
                    $obj = new AvitoItem();

                    $obj->setId((int) $item->attr['data-item-id'] ?? 0);
                    $obj->setLink($this->getHost() . $titleLink->href ?? '');
                    $obj->setTitle(urldecode($titleLink->title) ?? '');
                    $obj->setPrice($this->modifyPrice($item->find('.price', 0)->text()) ?? 0);
                    $obj->setDescr($this->modifyDescr($item->find('.data', 0)->text() ?? ''));
                    yield $obj;
                }
            }

            if ($arr['nextPage'] != null) {
                $arr      = $this->getItems($this->getHost() . $arr['nextPage']);
                $items    = $arr['items'] ?? [];
                $nextPage = $arr['nextPage'] ?? null;
            } else {
                $items    = [];
                $nextPage = null;
            }

        }


    }

    /**
     * @param string $price
     *
     * @return float
     */
    protected function modifyPrice(string $price): float
    {
        $price = trim($price);
        $price = str_replace(' ', '', $price);
        $price = substr($price, 0, -3);

        return (float) $price;
    }

    /**
     * @param string $descr
     *
     * @return string
     */
    protected function modifyDescr(string $descr): string
    {
        return trim(str_replace(PHP_EOL, '', $descr));
    }

    /**
     * @param string $searchQuery
     *
     * @return string
     */
    protected function getLink(string $searchQuery): string
    {
        $searchQuery = urlencode($searchQuery);

        return $this->getHost() . "/" . $this->region . "?q=" . $searchQuery;
    }

    /**
     * @return string
     */
    protected function getHost(): string
    {
        return "https://www.avito.ru";
    }

    /**
     * @param string $url
     *
     * @return array
     * @throws \GuzzleHttp\Exception\GuzzleException
     */
    protected function getItems(string $url): array
    {
        $req = $this->client->request('GET', $url);
        /**
         * @var simple_html_dom $html
         */
        $html     = HtmlDomParser::str_get_html($req->getBody()->getContents());
        $catalog  = $html->find('div .js-catalog_serp', 0);
        $items    = $catalog->find('.js-catalog-item-enum');
        $nextPage = $html->find('div .pagination-nav', 0)->find('.js-pagination-next', 0)->href ?? null;

        return [

            'items'    => $items,
            'nextPage' => $nextPage
        ];
    }

}