<?php 
namespace Sainsburys\Test\Component;

use PHPUnit\Framework\TestCase;
use Sainsburys\Component\Collection;
use Sainsburys\Model\Product;
use \stdClass;
use \Exception;

class CollectionTest extends TestCase {
    
    public function testCanBeCreated() {
    	$this->assertInstanceOf(Collection::class, new Collection);
    }

    public function testCanAddItem() {
    	$collection = new Collection();

    	$obj = new \stdClass(); // nice blank empty obejct

    	$collection->addItem($obj);

    	$this->assertEquals($collection->itemCount(), 1);
    }

    public function testCanGetItem() {
    	$collection = new Collection();

    	$item1 = 'first';
    	$item2 = 'second';

    	$collection->addItem($item1);
    	$collection->addItem($item2);

    	$this->assertEquals($item1, $collection->getItem(0));
    	$this->assertEquals($item2, $collection->getItem(1));
    }

    public function testCanGetItemThrowsNotFoundError() {
    	$this->expectException(Exception::class);

    	$collection = new Collection();
    	$collection->getItem(0);
    }

    public function testCanGetAll() {
        $collection = new Collection();

        $item1 = 'first';
        $item2 = 'second';

        $collection->addItem($item1);
        $collection->addItem($item2);

        $this->assertEquals($collection->getAll(), array($item1, $item2));
    }

    public function testSerialize() {
        $collection = new Collection();

        $item1 = new Product();
        $item2 = new Product();

        $item1->setPrice(5);
        $item2->setPrice(7);

        $collection->addItem($item1);
        $collection->addItem($item2);

        $json = array(
            'results' => array($item1,$item2),
            'total' => '12.00'
        );
        $json = json_encode($json);

        $this->assertEquals($json, json_encode($collection));
    }
}