<?php

namespace src\oop\app\src\Parsers;

use src\oop\app\src\Models\Movie;

class FilmixParserStrategy implements ParserInterface
{

    /**
     * @param string $siteContent
     * @return mixed
     */
    public function parseContent(string $siteContent): Movie
    {
        $movieObj = new Movie();

        preg_match_all('/="(.*?)" class="poster poster-tooltip"/', $siteContent, $poster);
        $movieObj->setPoster($poster[1][0]);

        preg_match_all('/(?<=name">)(.*)(?=h1)/', $siteContent, $title);
        $movieObj->setTitle($title[1][0] . '<br>');

        preg_match_all('/(?<="full-story">)(.*)(?=div><div)/', $siteContent, $description);
        $movieObj->setDescription($description[1][0]);

        return $movieObj;
    }
}