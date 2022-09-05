<?php

namespace src\oop\app\src\Parsers;

use src\oop\app\src\Models\Movie;

use Symfony\Component\DomCrawler\Crawler;

class KinoukrDomCrawlerParserAdapter implements ParserInterface
{

    /**
     * @param string $siteContent
     * @return mixed
     */
    public function parseContent(string $siteContent): Movie
    {
        $movieObj = new Movie();
        $crawler = new Crawler();
        $crawler->addContent($siteContent);

        $titleCrawler = $crawler->filter('.ftitle h1');
        $title = '';
        foreach ($titleCrawler as $item) {
            $title = $title . $item->textContent;
        }
        $movieObj->setTitle($title);

        $posterCrawler = $crawler->filter('.fposter img')->eq(0)->attr('src');
        $poster = 'https://kinoukr.com/' . $posterCrawler;
        $movieObj->setPoster($poster);

        $descriptionCrawler = $crawler->filter('.fdesc');
        $description = '';
        foreach ($descriptionCrawler as $item) {
            $description = $description . $item->textContent;
        }
        $movieObj->setDescription($description);

        return $movieObj;
    }
}