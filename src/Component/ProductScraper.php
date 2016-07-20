<?php 
namespace Sainsburys\Component;

use Sainsburys\Component\Scraper\AbstractScraper;
use GuzzleHttp\Exception\ConnectException;

/**
 * Product scraper handles all aspects of scraper Product pages
 */
class ProductScraper extends AbstractScraper {

	protected $client;

	protected $parser;

	public function setParser(\Sainsburys\Component\Parser\AbstractParser $parser) {
		$this->parser = $parser;
	}

	public function setClient(\Sainsburys\Component\Client\AbstractClient $client) {
		$this->client = $client;
	}

	/**
	 * Use the intnerally set client and parser to load the response and 
	 * parse the DOM.
	 * 
	 * @param  String Url to load
	 * @return Bool true on success
	 */
	public function load(String $url) {
		try {

			$raw = $this->client->fetch($url);
			$this->parser->loadDom($raw);

		} catch (\GuzzleHttp\Exception\ConnectException $e) {
			// if we can't connect, return false so executing isn't stopped
			return false;
		}

		return true;
	}

	/**
	 * Extracts from the dom a list of URLs to Product pages
	 * 
	 * @param  String Url anchor tag tager
	 * @return Array
	 */
	public function getProductUrls(String $target) : Array {

		$result = $this->parser->extract($target);

		$urls = array(); // for return

		foreach ($result as $key => $productUrl) {
			$url = $productUrl->getAttribute('href');
			array_push($urls, $url);
		}

		return $urls;
	}

	/**
	 * Extract a Product title, and remove HTML formatting
	 * 
	 * @param  String
	 * @return String
	 */
	public function getProductTitle(String $target) : String {
		$rawTitle = $this->parser->extract($target);
		return strip_tags($rawTitle);
	}

	/**
	 * Extract the Product price per unit
	 * 
	 * @param  String
	 * @return String
	 */
	public function getProductPrice(String $target) : String {
		// extract raw price
		$rawPrice = $this->parser->extract($target);

		// remove HTML formatting
		$strip = strip_tags($rawPrice);

		// use filter var to strip out float value for unit price
		$price = (float) filter_var($strip, FILTER_SANITIZE_NUMBER_FLOAT, FILTER_FLAG_ALLOW_FRACTION);

		return $price;
	}

	/**
	 * Extract Product description. Following tag stripping, we trim to
	 * remove whitespace in case of empty tags.
	 * 
	 * @param  String
	 * @return String
	 */
	public function getProductDescription(String $target) : String {
		$rawDescription = $this->parser->extract($target);

		// strip non-breaking spaces if added
		$rawDescription = str_replace('&nbsp;', '', $rawDescription);

		// strip tags
		$strip = strip_tags($rawDescription);
		return trim($strip);
	}

	/**
	 * Returns size of web page (no assests) as reported by the client
	 * 
	 * @return String
	 */
	public function getProductSize() : String {
		// return size in kb
		return number_format(($this->client->getRequestSize() / 1024), 1).'kb';
	}
}