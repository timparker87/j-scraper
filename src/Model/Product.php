<?php 
namespace Sainsburys\Model;

use \JsonSerializable;

/**
 * Models a Product object
 */
class Product implements JsonSerializable {

	/**
	 * Price of the product
	 * @var float
	 */
	private $price;

	/**
	 * URL of the product page
	 * @var String
	 */
	private $url;

	/**
	 * Description of the product
	 * @var String
	 */
	private $description;

	/**
	 * Product title
	 * @var String
	 */
	private $title;

	/**
	 * Size of product page
	 * @var String
	 */
	private $size;

	/**
	 * Products are instantiated with blank defaults
	 */
	public function __construct() {
		$this->price = 0;
		$this->url = '';
		$this->description = '';
		$this->title = '';
		$this->size = 0;
	}

	public function setPrice(float $price = 0) : Product {
		$this->price = $price;
		return $this;
	}

	public function getPrice() {
		return number_format($this->price, 2);
	}

	public function setUrl(String $url = null) : Product{
		$this->url = $url;
		return $this;
	}

	public function getUrl() : String {
		return $this->url;
	}

	public function setDescription(String $desc = '') : Product {
		$this->description = $desc;
		return $this;
	}

	public function getDescription() : String {
		return $this->description;
	}

	public function setTitle(String $title = '') : Product {
		$this->title = $title;
		return $this;
	}

	public function getTitle() : String {
		return $this->title;
	}

	public function setSize(String $size = '') : Product {
		$this->size = $size;
		return $this;
	}

	public function getSize() : String {
		return $this->size;
	}

	/**
	 * Formatted array of Product for conversion to JSON
	 * 
	 * @return Array
	 */
	public function jsonSerialize() {
        $json = array(
        	'title'       => $this->getTitle(),
        	'size'        => $this->getSize(),
        	'unit_price'  => $this->getPrice(),
        	'description' => $this->getDescription(),
    	);
        return $json;
    }
}