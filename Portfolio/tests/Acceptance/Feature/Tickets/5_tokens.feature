Feature:
  Trading tokens

  Background: Janet has trading permissions on John's account
    Given I load the fixtures group "manager"
    Given I create an account management delegation from 'john.doe@example.com' to 'janet.doe@example.com' on 'bybit'
    And I dispatch the delegation event
    And I should have "1" message in the transport containing a "delegation" event
    And I should handle the "delegation" event
    Then the user "john" should have permissions "r" and "w" on "bybit"
    Then the manager "janet" should have permissions "r" and "t" on "bybit"

  # with bybit, we use the V5 api
  # https://learn.bybit.com/bybit-guide/how-to-create-a-bybit-api-key/
  # https://bybit-exchange.github.io/docs/api-explorer/v5/account/wallet
  Scenario: John gives Janet a trading token on "bybit".
  First the token is tested with a non feasible trade, and then token is added to Janet permissions
    Given I have a trading token for "bybit"
    Then I can request wallet balance for "bybit"
    And I request the latest "BTC/USDT" derivative price
    And I place a buy order in USDT of "5" USDT at limit price inferior of "50%" under market price
    And I list the current orders
    Then I should see my buy order of "5" USDT
    And I cancel the order
    And I list orders
    And the previous order should not be listed
