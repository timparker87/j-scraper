<?php 
// namespace sainsburys\scrape\Test;
namespace Sainsburys\Test\Model;

use PHPUnit\Framework\TestCase;
use Sainsburys\Model\Product;

class ProductTest extends TestCase {
    
    public function testCanBeCreated() {
    	$this->assertInstanceOf(Product::class, new Product);
    }

    public function testCanSetPrice() {
    	$p = new Product();

    	$p->setPrice(5.03);

    	$this->assertEquals($p->getPrice(), 5.03);
    }

    public function testCanSetUrl() {
    	$p = new Product();

    	$p->setUrl('URL');

    	$this->assertEquals($p->getUrl(), 'URL');
    }

    public function testCanSetDescription() {
    	$p = new Product();

    	$p->setDescription('desc');

    	$this->assertEquals($p->getDescription(), 'desc');
    }

    public function testCanSetTitle() {
    	$p = new Product();

    	$p->setTitle('title');

    	$this->assertEquals($p->getTitle(), 'title');
    }

    public function testCanSetSize() {
    	$p = new Product();

    	$p->setSize('1024');

    	$this->assertEquals($p->getSize(), '1024');
    }

    public function testSerialise() {
    	$p = new Product();
		$p->setPrice(5.03);
		$p->setDescription('desc');
		$p->setTitle('title');
		$p->setSize('1024');

		$json = array(
			'title' => 'title',
			'size' => '1024',
			'unit_price' => '5.03', // because we number format, will be a string in JSON
			'description' => 'desc',
		);
		$json = json_encode($json);

		$this->assertEquals($json, json_encode($p));
    }
}