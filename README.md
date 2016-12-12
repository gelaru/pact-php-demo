## First try of using pact (setup from: https://github.com/andykelk/pact-php)

1. Clone the repo
2. Install pact mock service:
  - Install Ruby and Ruby Gems
  - Run bundler to get the gems: `gem install bundler && bundle install`
3. Install composer: curl -sS https://getcomposer.org/installer | php
4. Install dependencies: php composer.phar install
5. Start the mock server: `bundle exec pact-mock-service -p 1234 --pact-specification-version 2.0.0 -l log/pact.logs --pact-dir tmp/pacts`
6. Run phpunit: `./vendor/bin/phpunit --bootstrap=vendor/autoload.php tests/`
7. Inspect the pact file in `tmp/pacts`



## Setup Pact Broker


Easily setup your own [Pact Broker](https://github.com/bethesque/pact_broker)
instance with docker-compose.

run

        docker-compose pull
        docker-compose up -d db
        docker-compose up

Done! You can configure your Pact Broker and Postgres with the environment
variables inside the docker-compose.yml file.