<?php
namespace Sainsburys\Component;

use Sainsburys\Component\Client\AbstractClient;
use GuzzleHttp\Client;

/**
 * ProductClient is responsible for loading a webpage. This implementation
 * uses the Guzzle library to fetch the data.
 */
class ProductClient extends AbstractClient {

	protected $client;

	protected $size;

	public function __construct(\GuzzleHttp\Client $client) {
		$this->client = $client;
	}

	/**
	 * Perform request to load web page data.
	 * 
	 * @param  String
	 * @return String
	 */
	public function fetch(String $url) {
		
		// Guzzle get request
		$response = $this->client->get($url);

		// store request size internally
		$sizeHeader = $response->getHeader('Content-Length');
		$this->size = $sizeHeader[0] ?? 0; // very nice new php7 feature :)

		// return page data
		return $response->getBody();
	}

	/**
	 * Get request size
	 * 
	 * @return String
	 */
	public function getRequestSize() {
		return $this->size;
	}
}