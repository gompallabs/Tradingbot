Feature: init. In order to bootstrap application environment
  As an investor,
  I add defined api keys to request info about assets i own, my portfolio and quotes

  Scenario: try connexion test to public endpoint of "bitget" crypto exchange
    Given I measure time in milliseconds
    Given I request the rest endpoint "time" of "bitget" to check if my connexion is alive and my computer is sync

  Scenario: try connexion test to public endpoint of "bybit" crypto exchange
    Given I measure time in milliseconds
    Given I request the rest endpoint "time" of "bybit" to check if my connexion is alive and my computer is sync