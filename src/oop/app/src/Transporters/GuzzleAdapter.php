<?php

namespace src\oop\app\src\Transporters;

use GuzzleHttp\Client as Client;

class GuzzleAdapter implements TransportInterface
{

    /**
     * @param string $url
     * @return string
     * @throws \Exception
     */
    public function getContent(string $url): string
    {
        $httpClient = new Client();
        $responce = $httpClient->request('GET', $url);
        return $responce->getBody();
    }
}