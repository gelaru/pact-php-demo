<?php

namespace Consumer\Tests;

use Consumer\ReviewsClient;
use PhpPact\Consumer\InteractionBuilder;
use PhpPact\Consumer\Matcher\Matcher;
use PhpPact\Consumer\Model\ConsumerRequest;
use PhpPact\Consumer\Model\ProviderResponse;
use PhpPact\Standalone\MockService\MockServerConfigInterface;
use PhpPact\Standalone\MockService\MockServerEnvConfig;
use PHPUnit\Framework\TestCase;


class ReviewsClientTest extends TestCase
{
    private $review;
    /** @var MockServerConfigInterface */
    private $config;

    public function setUp()
    {
        $review = new \stdClass();
        $review->companyId = 1;
        $review->title = 'Worst experience ever';
        $review->rating = 3.43;
        $this->review = $review;

        $this->config = new MockServerEnvConfig();
    }

    public function testGetReviewsCompanyById()
    {
        $matcher = new Matcher();

        // how will the request look like
        $request = new ConsumerRequest();
        $request
            ->setMethod('GET')
            ->setPath('/reviews')
            ->setQuery(sprintf('company_id=%s', $this->review->companyId))
            ->addHeader('Accept', 'application/json');

        //what do we expect as response from the server?
        $response = new ProviderResponse();

        $response
            ->setStatus(200)
            ->addHeader('Content-Type', 'application/json')
            ->setBody([
                [
                    'id' => 1,
                    'title' => $matcher->regex($this->review->title, '\w'),
                    'rating' => $matcher->somethingLike($this->review->rating)
                ],
                [
                    'id' => 2,
                    'title' => $matcher->regex($this->review->title, '\w'),
                    'rating' => $matcher->somethingLike($this->review->rating)
                ],
            ]);

        //build the interaction

        $builder = new InteractionBuilder($this->config);
        $builder
            ->given('2 reviews exist for a company')
            ->uponReceiving('a GET request to /reviews?company_id={id}')
            ->with($request)
            ->willRespondWith($response);

        //make the request
        $client = new ReviewsClient(sprintf("http://%s:%s", $this->config->getHost(), $this->config->getPort()));
        $result = $client->getReviewsByCompanyId($this->review->companyId);

        //Verify that all interactions took place that were registered. This typically should be in each test,
        //that way the test that failed to verify is marked correctly.
        $this->assertTrue($builder->verify());

        $this->assertEquals($this->review->title, $result[0]['title']);
    }

}