Feature: Get shouts by author
  In order to see shouts
  As a User Api
  I want to be able to view a collection of shouts filtering by author

  Scenario: Bad request
    Given I send a GET request to "/shout/x-x-x?limit=50"
    Then the response content should be:
    """
    {
      "error": {
        "code": "BAD_REQUEST",
        "message": "Limit must be between 1 and 10. Got(50)"
      }
    }
    """
    Then the response status code should be 400

  Scenario: Author not found
    Given I send a GET request to "/shout/x-x-x?limit=10"
    Then the response content should be:
    """
    {
      "error": {
        "code": "AUTHOR_NOT_FOUND",
        "message": "Author x-x-x not found"
      }
    }
    """
    Then the response status code should be 404

  Scenario: A valid author without indicate limit of items
    Given I send a GET request to "/shout/steve-jobs"
    Then the response content should be:
    """
    [
          "THE ONLY WAY TO DO GREAT WORK IS TO LOVE WHAT YOU DO!",
          "YOUR TIME IS LIMITED, SO DON\u2019T WASTE IT LIVING SOMEONE ELSE\u2019S LIFE!"
    ]
    """
    Then the response status code should be 200

  Scenario: A valid author indicating limit is one item
    Given I send a GET request to "/shout/steve-jobs?limit=1"
    Then the response content should be:
    """
    [
          "THE ONLY WAY TO DO GREAT WORK IS TO LOVE WHAT YOU DO!"
    ]
    """
    Then the response status code should be 200
