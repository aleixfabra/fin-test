Feature: Get portfolio's orders
  In order to show a portfolio's orders
  Customers should be able to
  get the current orders of his portfolio

  Scenario: Get portfolio's orders
    Given I send a PUT request to "/api/portfolios/1" with body:
    """
    {
      "allocations": [
        {
          "id": 1,
          "shares": 3
        },
        {
          "id": 2,
          "shares": 4
        }
      ]
    }
    """
    And the response status code should be 200
    And the response should be empty
    When I send a POST request to "/api/orders" with body:
    """
    {
      "id": 1,
      "portfolio": 1,
      "allocation": 1,
      "shares": 3,
      "type": "buy"
    }
    """
    Then the response status code should be 201
    And the response should be empty
    When I send a GET request to "/api/portfolios/1/orders"
    Then the response status code should be 200
    And the response body should be:
    """
     {
      "id": 1,
      "orders": [
        {
          "id": 1,
          "allocation": 1,
          "shares": 3,
          "type": "buy",
          "completed": false
        }
      ]
    }
    """
  Scenario: Invalid Method
    Given I send a PATCH request to "/api/portfolios/1/orders"
    Then the response status code should be 405
    And the response should be empty
