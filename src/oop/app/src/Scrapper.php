<?php

namespace src\oop\app\src;

use src\oop\app\src\Models\Movie;

use src\oop\app\src\Parsers\ParserInterface;

use src\oop\app\src\Transporters\TransportInterface;

/**
 * Create Class - Scrapper with method getMovie().
 * getMovie() - should return Movie Class object.
 *
 * Note: Use next namespace for Scrapper Class - "namespace src\oop\app\src;"
 * Note: Dont forget to create variables for TransportInterface and ParserInterface objects.
 * Note: Also you can add your methods if needed.
 */

class Scrapper {

    protected TransportInterface $transport;
    protected ParserInterface $parser;

    /**
     * @param $transport
     * @param $parser
     */
    public function __construct($transport, $parser)
    {
        $this->transport = $transport;
        $this->parser = $parser;
    }

    /**
     * @param string $url
     * @return Movie
     */
    public function getMovie(string $url): Movie
    {
        $content = $this->transport->getContent($url);
        return $this->parser->parseContent($content);
    }
}