<?php

namespace Consumer;

use GuzzleHttp\Client;

class ReviewsClient
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
     * @param $id
     * @return array
     */
    public function getReviewsByCompanyId($id)
    {
        $response = $this->client->get(sprintf('/reviews?company_id=%s', $id), [ 'headers' => ['Accept' => 'application/json']]);

        $content = $response->getBody()->getContents();

        return json_decode($content, true);
    }

}