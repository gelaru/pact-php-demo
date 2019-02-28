<?php

namespace Consumer\Tests;

use Consumer\CompaniesClient;
use PhpPact\Consumer\InteractionBuilder;
use PhpPact\Consumer\Matcher\Matcher;
use PhpPact\Consumer\Model\ConsumerRequest;
use PhpPact\Consumer\Model\ProviderResponse;
use PHPUnit\Framework\TestCase;

use PhpPact\Standalone\MockService\MockServer;
use PhpPact\Standalone\MockService\MockServerConfig;


class CompaniesClientTest extends TestCase
{
    const PROVIDER_PORT = 7200;
    const PROVIDER_HOST = 'localhost';
    /** @var  MockServer */
    private $server;
    private $mockServerConfig;

    public function setUp()
    {
        // Create your basic configuration. The host and port will need to match
        // whatever your Http Service will be using to access the providers data.
        // it's also possible to provide all this with env vars
        $config = new MockServerConfig();
        $config->setHost(self::PROVIDER_HOST);
        $config->setPort(self::PROVIDER_PORT);
        $config->setConsumer('CompaniesConsumer');
        $config->setProvider('CompaniesProvider');
        $config->setCors(true);

        $this->mockServerConfig = $config;

        // Instantiate the mock server object with the config. This can be any
        // instance of MockServerConfigInterface.
        $this->server = new MockServer($config);

        // Create the process.
        $this->server->start();
    }

    public function tearDown()
    {
        // Stop the process.
        $this->server->stop();
    }

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
        $builder = new InteractionBuilder($this->mockServerConfig);
        $builder
            ->given('a company exists')
            ->uponReceiving('a GET request to /companies/{id}')
            ->with($request)
            ->willRespondWith($response);


        //make the request
        $client = new CompaniesClient(sprintf("http://%s:%s", self::PROVIDER_HOST, self::PROVIDER_PORT));
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
        $builder = new InteractionBuilder($this->mockServerConfig);
        $builder
            ->given('a company exists')
            ->uponReceiving('a GET request to /companies?profile_id{id}')
            ->with($request)
            ->willRespondWith($response);


        //make the request
        $client = new CompaniesClient(sprintf("http://%s:%s", self::PROVIDER_HOST, self::PROVIDER_PORT));
        $result = $client->getCompaniesByProfileId($profileId);

        //Verify that all interactions took place that were registered. This typically should be in each test,
        //that way the test that failed to verify is marked correctly.
        $builder->verify();

        $this->assertEquals($expectedName, $result[0]['name']);
    }

}