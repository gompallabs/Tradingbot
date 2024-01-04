Feature: In this app, a "repository" is a store of assets
  We need to fetch data from broker / crypto exchange

  Background: I setup a crypto exchange (Bybit)
    Given the database schema is updated
    Given I have a crypto exchange named "bybit"
    Given I have a crypto exchange named "bitget"

  Scenario: I instanciate a client. I need read-only api key
    Given I have a read-only api key for "bybit"
    Given I have a secret for api key of "bybit"
    And I instanciate a rest api client for "bybit"
    Then I shoud have a client with credentials
    And should be able to sign my "Get" requests to the Api following documentation
