<?php

namespace Consumer\Tests;

use Consumer\CompaniesClient;
use PhpPact\Consumer\InteractionBuilder;
use PhpPact\Consumer\Matcher\Matcher;
use PhpPact\Consumer\Model\ConsumerRequest;
use PhpPact\Consumer\Model\ProviderResponse;
use PhpPact\Standalone\MockService\MockServerEnvConfig;
use PHPUnit\Framework\TestCase;


class CompaniesClientTest extends TestCase
{
    public function testGetCompanyById()
    {
        $companyId = 1;
        $expectedName = 'kununu GmbH';

        $matcher = new Matcher();

        // how will the request look like
        $request = new ConsumerRequest();
        $request
            ->setMethod('GET')
            ->setPath('/companies/'.$companyId)
            ->addHeader('Accept', 'application/json');

        //what do we expect as response from the server?
        $response = new ProviderResponse();

        $response
            ->setStatus(200)
            ->addHeader('Content-Type', 'application/json')
            ->setBody([
                'name' => $matcher->regex($expectedName, '\w')
            ]);

        //build the interaction
        $config = new MockServerEnvConfig();
        $builder = new InteractionBuilder($config);
        $builder
            ->given('a company exists')
            ->uponReceiving('a GET request to /companies/{id}')
            ->with($request)
            ->willRespondWith($response);


        //make the request
        $client = new CompaniesClient(sprintf("http://%s:%s", $config->getHost(), $config->getPort()));
        $result = $client->getCompanyById($companyId);

        //Verify that all interactions took place that were registered. This typically should be in each test,
        //that way the test that failed to verify is marked correctly.
        $builder->verify();

        $this->assertEquals($expectedName, $result['name']);
    }

    public function testGetCompaniesByProfileId()
    {
        $profileId = 1;
        $expectedName = 'kununu GmbH';

        $matcher = new Matcher();

        // how will the request look like
        $request = new ConsumerRequest();
        $request
            ->setMethod('GET')
            ->setPath('/companies')
            ->setQuery('profile_id='.$profileId)
            ->addHeader('Accept', 'application/json');

        //what do we expect as response from the server?
        $response = new ProviderResponse();

        $response
            ->setStatus(200)
            ->addHeader('Content-Type', 'application/json')
            ->setBody([
                [
                    'id' => 1,
                    'name' => $matcher->regex($expectedName, '\w')
                ],
                [
                    'id' => 2,
                    'name' => $matcher->regex($expectedName, '\w')
                ]
            ]);

        //build the interaction
        $config = new MockServerEnvConfig();
        $builder = new InteractionBuilder($config);
        $builder
            ->given('a company exists')
            ->uponReceiving('a GET request to /companies?profile_id{id}')
            ->with($request)
            ->willRespondWith($response);


        //make the request
        $client = new CompaniesClient(sprintf("http://%s:%s", $config->getHost(), $config->getPort()));
        $result = $client->getCompaniesByProfileId($profileId);

        //Verify that all interactions took place that were registered. This typically should be in each test,
        //that way the test that failed to verify is marked correctly.
        $builder->verify();

        $this->assertEquals($expectedName, $result[0]['name']);
    }

}