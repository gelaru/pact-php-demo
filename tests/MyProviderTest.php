<?php

use Pact\Pact;
use ZooConsumer\ZooClient;

class AlligatorProviderTest extends PHPUnit_Framework_TestCase
{
    public function testMyProvider() {
        $client = new ZooClient('http://localhost:1234');

        $alligatorProvider = Pact::mockService([
            'consumer' => 'Alligator Consumer',
            'provider' => 'Alligator Provider',
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
    }

}