# Sainsburys Product Scraper
## Overview
The main component of the app is the `Scraper` object, which has a `Client` and `Parser` component. The `Client` is responsible for fetching a web pages content, and the `Parser` for providing functions to extract information from the page.

The `Scraper` first loads the page looking for other Product URLs. For each of these, a new request is made, and information extracted. Specific Product information is stored inside a `Product` object, and these are added to a `Collection`.
## Requirements
 - PHP 7.0 (including CURL, MBString extensions)

## Installation & Usage
 - Clone the repository
 - Run `composer install` in root directory
 - To run, use command `php src/main.php <url>`
   - `php src/main.php http://hiring-tests.s3-website-eu-west-1.amazonaws.com/2015_Developer_Scrape/5_products.html`
 - For tests, run `vendor/bin/phpunit`

## Structure

`src` contains app code.

`tests` contains app tests. Coverage is 96%.

At the moment, the app is dependant on 2 external libraries - Guzzle for the client, and PHPHtmlParser for the parser. You could implement different methods easily based on `AbstractParser` and `AbstractClient`.