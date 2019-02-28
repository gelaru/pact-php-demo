<?php

namespace Consumer\Tests;

use Consumer\CompaniesClient;
use Consumer\ReviewsClient;
use PhpPact\Consumer\InteractionBuilder;
use PhpPact\Consumer\Matcher\Matcher;
use PhpPact\Consumer\Model\ConsumerRequest;
use PhpPact\Consumer\Model\ProviderResponse;
use PHPUnit\Framework\TestCase;

use PhpPact\Standalone\MockService\MockServer;
use PhpPact\Standalone\MockService\MockServerConfig;


class ReviewsClientTest extends TestCase
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

    }


    public function testGetReviewsCompanyById()
    {
        $companyId = 1;
        $reviewTitle = 'Worst experience ever';
        $reviewRating = 3.43;

        $matcher = new Matcher();

        // how will the request look like
        $request = new ConsumerRequest();
        $request
            ->setMethod('GET')
            ->setPath('/reviews')
            ->setQuery(sprintf('company_id=%s', $companyId))
            ->addHeader('Accept', 'application/json');

        //what do we expect as response from the server?
        $response = new ProviderResponse();

        $response
            ->setStatus(200)
            ->addHeader('Content-Type', 'application/json')
            ->setBody([
                [
                    'id' => 1,
                    'title' => $matcher->regex($reviewTitle, '\w'),
                    'rating' => $matcher->somethingLike($reviewRating)
                ],
                [
                    'id' => 2,
                    'title' => $matcher->regex($reviewTitle, '\w'),
                    'rating' => $matcher->somethingLike($reviewRating)
                ],
            ]);

        //build the interaction
        $builder = new InteractionBuilder($this->mockServerConfig);
        $builder
            ->given('2 reviews exist for a company')
            ->uponReceiving('a GET request to /reviews?company_id={id}')
            ->with($request)
            ->willRespondWith($response);

        //make the request
        $client = new ReviewsClient(sprintf("http://%s:%s", self::PROVIDER_HOST, self::PROVIDER_PORT));
        $result = $client->getReviewsByCompanyId($companyId);

        //Verify that all interactions took place that were registered. This typically should be in each test,
        //that way the test that failed to verify is marked correctly.
        $this->assertTrue($builder->verify());

        $this->assertEquals($reviewTitle, $result[0]['title']);
    }

}