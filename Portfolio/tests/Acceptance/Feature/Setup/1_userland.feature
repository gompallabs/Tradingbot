Feature:
  Setup user and accounts, with delegation of trading

  Scenario: create a user and accounts
    Given I load the fixtures group "init"
    Then I should have 1 owner
    And the user 'john.doe@example.com' should have both 'r' and 'w' permissions on 2 accounts
    And there should be one "bybit" account
    And there should be one "bitget" account

  Scenario: create 2 users and accounts
    Given I load the fixtures group "manager"
    And I should have a "manager" squad
    Then I should have 1 owner with no squad and 1 owner member of the "manager" squad

  # Delegation
    # we create delegation flow
    @delegation
    Scenario: create a management delegation
      Given I load the fixtures group "manager"
      Given I create an account management delegation from 'john.doe@example.com' to 'janet.doe@example.com' on 'bybit'
      And I dispatch the delegation event
      And I should have "1" message in the transport containing a "delegation" event
      And I should handle the "delegation" event
      Then the user "john" should have permissions "r" and "w" on "bybit"
      Then the manager "janet" should have permissions "r" and "t" on "bybit"

    # we create delegation cancel flow
    @delegation
    Scenario: remove a management delegation
      Given I load the fixtures group "manager"
      Given I create an account management delegation from 'john.doe@example.com' to 'janet.doe@example.com' on 'bybit'
      And I dispatch the delegation event
      And I should have "1" message in the transport containing a "delegation" event
      And I should handle the "delegation" event
      Then the user "john" should have permissions "r" and "w" on "bybit"
      Then the manager "janet" should have permissions "r" and "t" on "bybit"
      And I dispatch the delegationCancellation event
      And I should have "1" message in the transport containing a "delegationCancel" event
      And I should handle the "delegationCancel" event
      Then the user "john" should have permissions "r" and "t" and "w" on "bybit"
      Then the manager "janet" should have no permissions "bybit"

  # Accounts setup
    @setup
    Scenario: create account on bybit, bitget, woox, bitpanda, mexc, bithumb for crypto
      and ibkr and saxo for stocks for
      Given I load the fixtures group "setup"
      Then the following "cryptoExchange" storages should exist:
      | name                                                            |
      | binance, bybit, bitget, kraken, woox, bitpanda, mexc, bithumb   |

      And the following "brokerage" storages should exist:
      |name|
      |ibkr, saxo|

      And I should have the user "toto@example.com"
      And I should have the user "titi@example.com"

      And user "toto@example.com" should have for each "cryptoExchange" storage a "FIAT" account:
      | cryptoExchange |
      | bybit, bitget, bitpanda |
      And user "toto@example.com" should have for each "cryptoExchange" storage a "ASSET_SPOT" account:
      | cryptoExchange |
      | bybit, bitget, bitpanda |
      And user "toto@example.com" should have for each "cryptoExchange" storage a "ASSET_FUTURES_TRADING" account:
      | cryptoExchange |
      | bybit, bitget, bitpanda |

  # Asset setup
  @setup-coins
  Scenario: create bitcoin, eth and sol spot + perp
    Given I load the fixtures group "coins"
    Then the following assets should exist:
    | spot  | name     | ticker | category  | sub-category  |
    | true  | bitcoin  | BTC    | commodity | sad           |
    | true  | ethereum | ETH    | crypto    | sad           |
    | true  | solana   | SOL    | crypto    | sad           |
