Feature: See henks
  In order to see the available henks
  As a Tweakers user
  I need to be able to request the available henks for the current page

  Scenario: Get henks for a Tweakers url
    Given I am at the url "http://tweakers.net/nieuws/85425/klanten-t-mobile-verstoken-bijna-240tb-per-week.html"
    And I am identified by the userId "266225"
    When I request the number of henks
    Then I should get:
      """
      {"contentId":"85425","contentType":"News","henks":0,"hasHenked":false}
      """

  Scenario: Get henks for a henked Tweakers url
    Given I am at the url "http://tweakers.net/nieuws/85425/klanten-t-mobile-verstoken-bijna-240tb-per-week.html"
    And I am identified by the userId "266225"
    And The current url is already henked
    When I request the number of henks
    Then I should get:
      """
      {"contentId":"85425","contentType":"News","henks":1,"hasHenked":true}
      """