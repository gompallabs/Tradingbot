Feature: In this app, a "repository" is a store of assets
  We need to fetch data from broker / crypto exchange
  To check if api calls are ready we try to fetch account balance from exchanges

  Background: I setup a crypto exchange
    Given the database schema is updated
    Given I have a crypto exchange named "binance"
    Given I have a crypto exchange named "bybit"
    Given I have a crypto exchange named "bitget"
    Given I have a crypto exchange named "coinbase"
    Given I have a crypto exchange named "kraken"


  Scenario: I instanciate a bybit client
    Given I have a read-only api key for "bybit"
    Given I have a secret for api key of "bybit"
    And I instanciate a rest api client for "bybit"
    Then I shoud have a client with credentials

  Scenario: I request account balance
    Given I instanciate a rest api client for "bybit"
    Then I shoud have a client with credentials
    Given I request account balance for "bybit"