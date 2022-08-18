<?php

namespace src\oop\app\src\Parsers;

use src\oop\app\src\Models\Movie;

use Symfony\Component\DomCrawler\Crawler;

class KinoukrDomCrawlerParserAdapter implements ParserInterface
{

    private string $title = '';
    private string $poster = '';
    private string $description = '';

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
        foreach ($titleCrawler as $item) {
            $this->title = $this->title . $item->textContent;
        }
        $movieObj->setTitle($this->title);

        $posterCrawler = $crawler->filter('.fposter img')->eq(0)->attr('src');
        $this->poster = 'https://kinoukr.com/' . $posterCrawler;
        $movieObj->setPoster($this->poster);

        $descriptionCrawler = $crawler->filter('.fdesc');
        foreach ($descriptionCrawler as $item) {
            $this->description = $this->description . $item->textContent;
        }
        $movieObj->setDescription($this->description);

        return $movieObj;
    }
}