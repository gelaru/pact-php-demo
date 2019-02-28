<?php

namespace Consumer;

use GuzzleHttp\Client;

class CompaniesClient
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
    public function getCompanyById($id)
    {
        $response = $this->client->get(sprintf('/companies/%s', $id), [ 'headers' => ['Accept' => 'application/json']]);

        $content = $response->getBody()->getContents();

        return json_decode($content, true);
    }

    /**
     * @param $id
     * @return array
     */
    public function getCompaniesByProfileId($id)
    {
        $response = $this->client->get(sprintf('/companies?profile_id=%s', $id), [ 'headers' => ['Accept' => 'application/json']]);

        $content = $response->getBody()->getContents();

        return json_decode($content, true);
    }
}