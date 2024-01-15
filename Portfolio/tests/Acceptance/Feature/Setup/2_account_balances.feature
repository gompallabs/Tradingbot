Feature: In this app, a "repository" is a store of assets
  Now that we are ok that Rest client can connect
  We want to fetch all balances we can
  In theory, balance queries are not used a lot, we should use transaction history to update data
  But in a well managed app, you have to "reconciliate" the balances and the history of transactions
  because there's always rounding differences

  Background: I setup a crypto exchange
    Given the database schema is updated
    Given I have a crypto exchange named "bybit"
    Given I have a crypto exchange named "bitget"

  # Spot account info
  Scenario: Get spot account info
      Given I instanciate a rest api client for "bitget"
      Then I request spot account info
      Then I query the spot balance
      And I query the futures positions
      And I save the results, only quantities

  Scenario: Get spot account info
    Given I instanciate a rest api client for "bybit"
    Then I request spot account info
    Then I query the spot balance
    And I query the futures positions
    And I save the results, only quantities

  # Spot balance
