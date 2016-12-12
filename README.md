First try of using pact (setup from: https://github.com/andykelk/pacStart the mock server: bundle exec pact-mock-service -p 1234 --pact-specification-version 2.0.0 -l log/pact.logs --pact-dir tmp/pacts

Run phpunit: ./vendor/bin/phpunit
Inspect the pact file in tmp/pactst-php) 

1. Clone the repo
2. Install pact mock service:
  - Install Ruby and Ruby Gems
  - Run bundler to get the gems: `gem install bundler && bundle install`
3. Install composer: curl -sS https://getcomposer.org/installer | php
4. Install dependencies: php composer.phar install
5. Start the mock server: `bundle exec pact-mock-service -p 1234 --pact-specification-version 2.0.0 -l log/pact.logs --pact-dir tmp/pacts`
6. Run phpunit: `./vendor/bin/phpunit`
7. Inspect the pact file in `tmp/pacts`
