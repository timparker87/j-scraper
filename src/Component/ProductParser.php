<?php
namespace Sainsburys\Component;

use Sainsburys\Component\Parser\AbstractParser;

/**
 * This implementation of Parser parses a Product webpage and
 * uses the PHPHtmlParser library internally.
 */
class ProductParser extends AbstractParser {

	var $domparser;

	public function __construct(\PHPHtmlParser\Dom $parser) {
		$this->domparser = $parser;
	}

	/**
	 * Load the parser object with the result from our client
	 */
	public function loadDom(String $content) {
		try {
			$this->domparser->load($content);			
		} catch (Exception $e) {
			return false;
		}
		return true;
	}

	/**
	 * Extracts a specific element, based on jQuery style accessors
	 * 
	 * @param  String
	 * @return String
	 */
	public function extract(String $target) {
		$results = $this->domparser->find($target);
		return $results;
	}
}