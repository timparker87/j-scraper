<?php 
namespace Sainsburys\Component;

use \Exception;
use \JsonSerializable;

/**
 * A Collection acts as a general store for data, there is no restriction
 * on the type of items that can be added.
 * 
 * This is a general Component, and other classes can extend it to add 
 * their own functionality.
 */
class Collection implements JsonSerializable {

	/**
	 * Internal data store for our Collection
	 * @var Array
	 */
	private $data;
	
	public function __construct() {
		// instatiate new empty array internally
		$this->data = array();
	}

	/**
	 * Strores a collection of items - there is no restriction on
	 * types allowed.
	 * 
	 * @param Any - You can add items of any type to a Collection
	 * @return Collection
	 */
	public function addItem($item) : Collection {
    	$this->data[] = $item;
    	return $this;
    }

    /**
     * Return a specific element of the collection 
     * 
     * @param  int - Index of item to return
     * @return Any
     */
    public function getItem(int $index) {

    	if (!isset($this->data[$index])) {
    		throw new Exception("Item not found in collection", 1);
    		
    	}
    	return $this->data[$index];
    }

    /**
     * Return the entire collection
     * 
     * @return Array
     */
    public function getAll() : Array {
    	return $this->data;
    }

    /**
     * Return the number of elements in the collection
     * 
     * @return int
     */
    public function itemCount() : int {
    	return count($this->data);
    }

    /**
     * Returns a sum of all product prices in the collection
     * 
     * @TODO this assumes all items in collection are Products
     * 
     * @return String
     */
    public function sum() {
        $sum = 0;
        foreach ($this->data as $key => $product) {
            $sum = $sum + $product->getPrice();
        }
        return number_format($sum,2);
    }

    /**
     * Returns the collection as a formatted array,
     * 
     * @return Array
     */
    public function jsonSerialize() : Array {
        $json = array(
            'results' => $this->data,
            'total' => $this->sum(),
        );
        return $json;
    }
}