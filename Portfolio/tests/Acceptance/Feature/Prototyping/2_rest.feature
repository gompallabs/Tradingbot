Feature: make basic call to rest api

  Background: I setup a crypto exchange
    Given the database schema is updated
    Given I have a crypto exchange named "bybit"
    Given I have a crypto exchange named "bitget"

  # bybit
  Scenario: I instanciate a bybit client
  Given I have a read-only api key for "bybit"
  Given I have a secret for api key of "bybit"
  And I instanciate a rest api client for "bybit"
  Then I shoud have a client with credentials

  Scenario: I request account balance for bybit
  Given I instanciate a rest api client for "bybit"
  Then I shoud have a client with credentials
  Given I request account balance for "bybit"
  Then the response should be an array with several balances

  # bitget
  Scenario: I instanciate a bitget client
  Given I have a read-only api key for "bitget"
  Given I have a secret for api key of "bitget"
  And I instanciate a rest api client for "bitget"
  Then I shoud have a client with credentials

  Scenario: I request account balance for bitget
  Given I instanciate a rest api client for "bitget"
  Then I shoud have a client with credentials
  Given I request account balance for "bitget"
  Then the response should be an array with several balances