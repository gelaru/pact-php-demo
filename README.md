# Demo of how to use pact using php


## Consumer

Using a test listener, you can publish the generated pacts automatically, 
simply run the consumer tests with:
    
    ./vendor/bin/phpunit --testsuite consumer
    
All you need to do is to set your pact broker address in the `phpunit.xml`


## Broker

You can get a pact broker from https://github.com/DiUS/pact_broker-docker