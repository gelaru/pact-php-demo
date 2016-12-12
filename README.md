## First try of using pact (setup from: https://github.com/andykelk/pact-php)

1. Clone the repo
2. Install pact mock service:
  - Install Ruby and Ruby Gems
  - Run bundler to get the gems: `gem install bundler && bundle install`
3. Install composer: curl -sS https://getcomposer.org/installer | php
4. Install dependencies: php composer.phar install
5. Start the mock server: `bundle exec pact-mock-service -p 1234 --pact-specification-version 2.0.0 -l log/pact.logs --pact-dir tmp/pacts`

6. Setup [Pact Broker](https://github.com/bethesque/pact_broker)
    instance with docker-compose.

    run

            docker-compose pull
            docker-compose up -d db
            docker-compose up

7. Run phpunit: `./vendor/bin/phpunit --bootstrap=vendor/autoload.php tests/`
8. Inspect the pact file in `tmp/pacts`
9. Open 'http://127.0.0.1:5000' in your browser and you will be in awe!