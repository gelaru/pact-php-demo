<?php

namespace ZooConsumer;

use GuzzleHttp\Client;

class ZooClient
{

    public function __construct($baseUrl)
    {
        $this->client = new Client([
            // Base URI is used with relative requests
            'base_uri' => $baseUrl,
            // You can set any number of default request options.
            'timeout'  => 2.0,
        ]);
    }

    /**
     * @param $name
     * @return Alligator
     */
    public function getAlligatorByName($name)
    {
        $response = $this->client->get(sprintf('/alligators/%s', $name), [ 'headers' => ['Accept' => 'application/json']]);

        $content = $response->getBody()->getContents();

        $alligator = json_decode($content);

        return new Alligator($alligator->name);
    }

}