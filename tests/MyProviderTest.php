<?php


use Pact\Pact;
use ZooConsumer\ZooClient;

class AlligatorProviderTest extends PHPUnit_Framework_TestCase
{
    public function testMyProvider() {
        $client = new ZooClient('http://localhost:1234');

        $consumerName = 'Alligator Consumer';
        $providerName = 'Alligator Provider';
        $alligatorProvider = Pact::mockService([
            'consumer' => $consumerName,
            'provider' => $providerName,
            'port' => 1234
        ]);

        $alligatorProvider
            ->given("an alligator with the name Mary exists")
            ->uponReceiving("a request for an alligator")
            ->withRequest("get", "/alligators/Mary", [
                "Accept" => "application/json"
            ])->willRespondWith(200, [
                "Content-Type" => "application/json"
            ], [
                "name" => "Mary"
            ]);

        $alligatorProvider->run(function() use ($client) {
            $alligator = $client->getAlligatorByName('Mary');
            $this->assertInstanceOf(\ZooConsumer\Alligator::class, $alligator);
            $this->assertEquals("Mary", $alligator->getName());
        });

        //post the contract to pact broker

        $alligatorPact =  realpath(__DIR__ . '/../tmp/pacts/alligator_consumer-alligator_provider.json');
        $client  = new \Http\Adapter\Guzzle6\Client();
        $client = new \Madkom\PactBrokerClient\HttpBrokerClient('127.0.0.1:5000', $client, new \Madkom\PactBrokerClient\RequestBuilder());
        $response = $client->publishPact($providerName, $consumerName, '1.0.5', $alligatorPact);
    }

}