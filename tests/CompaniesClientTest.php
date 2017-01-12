<?php


use Pact\Pact;
use CompaniesConsumer\CompaniesClient;

class CompaniesClientTest extends PHPUnit_Framework_TestCase
{
    public function testMyProvider()
    {
        $client = new CompaniesClient('http://localhost:1234');

        $consumerName = 'CompaniesClient';
        $providerName = 'CompaniesService';

        $companiesService = Pact::mockService([
            'consumer' => $consumerName,
            'provider' => $providerName,
            'port' => 1234
        ]);

        $companiesService
            ->given("a company with id 260883 exists")
            ->uponReceiving("a request for a company")
            ->withRequest("get", "/companies/260883", [
                "Accept" => "application/json"
            ])->willRespondWith(
                200,
                [
                    "Content-Type" => "application/json"
                ],
                [
                    "id" => 260883,
                    "name" => "IBM",
                    "industry_id" => 6,
                    "date_created" => "2015-11-17T20:54:45+0000",
                    "date_updated" => "2015-11-17T20:54:45+0000",
                    "location" => [
                        "city" => "Dallas",
                        "country_code" => "us",
                        "state" => "Texas"
                    ],
                    "profile" => [
                        "slug" => "ibm"
                    ],
                    "review_count" => [
                        "total" => 46,
                        "online" => 22
                    ],
                    "hash" => "X1RfAFpf",
                    "deleted" => false
                ]
            );

        $companiesService->run(function () use ($client) {
            $id = '260883';
            $company = $client->getCompanyById($id);
            $this->assertInstanceOf(\CompaniesConsumer\Company::class, $company);
            $this->assertEquals($id, $company->id);
        });
//
        //post the contract to pact broker
        $companiesPact = realpath(__DIR__ . '/../tmp/pacts/companiesclient-companiesservice.json');
        $client = new \Http\Adapter\Guzzle6\Client();
        $client = new \Madkom\PactBrokerClient\HttpBrokerClient('http://172.18.2.56:5000', $client, new \Madkom\PactBrokerClient\RequestBuilder());
        $response = $client->publishPact($providerName, $consumerName, '1.0.0', $companiesPact);
    }

}