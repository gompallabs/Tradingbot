Feature:
  Setup feature set up investment universe

  Scenario: Crypto setup
    Given I load the fixtures
    Then I should have more than 1 cryptoExchange
    And I should have one account per exchange
    And I request the database for assets
    Then the crypto "bitcoin" exist and is typed as a commodity
    And the crypto "ethereum" exist ans is typed as a shitcoin

