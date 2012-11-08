Feature: Henk a url
  In order to henk a url
  As a Tweakers user
  I need to be logged in and request a henk

  Scenario: Henk a Tweakers url
    Given I am at the url "http://tweakers.net/nieuws/85425/klanten-t-mobile-verstoken-bijna-240tb-per-week.html"
    And I am identified by the userId "266225"
    When I henk the current url
    Then I should get:
      """
      {"contentId":"85425","contentType":"News","henks":1}
      """