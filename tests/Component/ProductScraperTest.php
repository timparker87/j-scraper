<?php 
namespace Sainsburys\Test\Component;

use PHPUnit\Framework\TestCase;
use Sainsburys\Component\ProductScraper;
use \stdClass;
use \Exception;

class ProductScraperTest extends TestCase {

	var $sampleHtmlReturn = '<div class="wrapper"><span class="price">£0.53</span></div>';
    
    public function testCanBeCreated() {
    	$this->assertInstanceOf(ProductScraper::class, new ProductScraper);
    }

    // test loading via the internal client, parser implementations
    public function testCanLoad() {
    	$s = new ProductScraper();

    	// mock the parser
    	$parser = $this->getMockBuilder('\Sainsburys\Component\Parser\AbstractParser')
    				   ->setMethods(['loadDom', 'extract'])
    				   ->getMock();

    	$parser->expects($this->once())
    		->method('loadDom')
    		->will($this->returnSelf());

    	$parser->expects($this->never())
    		->method('extract');

    	// mock the client
    	$client = $this->getMockBuilder('\Sainsburys\Component\Client\AbstractClient')
    				   ->setMethods(['fetch'])
    				   ->getMock();

    	$client->expects($this->once())
    		->method('fetch')
    		->will($this->returnValue($this->sampleHtmlReturn));

    	$s->setParser($parser);
    	$s->setClient($client);

    	$this->assertEquals($s->load('http://www.google.co.uk'), true);
    }

     public function testLoadException() {
     	$this->setExpectedException('Exception');
    	$s = new ProductScraper();

    	// mock the parser
    	$parser = $this->getMockBuilder('\Sainsburys\Component\Parser\AbstractParser')
    				   ->setMethods(['loadDom', 'extract'])
    				   ->getMock();

    	$parser->expects($this->once())
    		->method('loadDom')
    		->will($this->throwException(new Exception));

    	$parser->expects($this->never())
    		->method('extract');

    	// mock the client
    	$client = $this->getMockBuilder('\Sainsburys\Component\Client\AbstractClient')
    				   ->setMethods(['fetch'])
    				   ->getMock();

    	$client->expects($this->once())
    		->method('fetch')
    		->will($this->returnValue($this->sampleHtmlReturn));

    	$s->setParser($parser);
    	$s->setClient($client);

    	$this->assertEquals($s->load('http://www.google.co.uk'), false);
    }

    public function testCanExtractProductURLs() {
    	$s = new ProductScraper();

    	$node = $this->getMockBuilder('\Node')
    				   ->setMethods(['getAttribute'])
    				   ->getMock();

		$node->expects($this->any())
    		->method('getAttribute')
    		->will($this->returnValue('url'));

    	// mock the parser
    	$parser = $this->getMockBuilder('\Sainsburys\Component\Parser\AbstractParser')
    				   ->setMethods(['loadDom', 'extract'])
    				   ->getMock();

    	$parser->expects($this->once())
    		->method('extract')
    		->will($this->returnValue([$node,$node,$node]));

    	$parser->expects($this->never())
    		->method('loadDom');


    	$s->setParser($parser);

    	$this->assertEquals($s->getProductUrls('target'), ['url','url','url']);
    }

    /**
     * @dataProvider  providerTestCanGetProductTitle
     */
    public function testCanGetProductTitle($raw, $expected) {
    	$s = new ProductScraper();

    	// mock the parser
    	$parser = $this->getMockBuilder('\Sainsburys\Component\Parser\AbstractParser')
    				   ->setMethods(['loadDom', 'extract'])
    				   ->getMock();

	    $parser->expects($this->never())
    		->method('loadDom')
    		->will($this->returnValue(''));

    	$parser->expects($this->once())
    		->method('extract')
    		->will($this->returnValue($raw));


    	$s->setParser($parser);
    	$res = $s->getProductTitle('target');

    	$this->assertEquals($res, $expected);
    }

    public function providerTestCanGetProductTitle() {
    	return [
    		['<h1>Title</h1>', 'Title'],
    		['<p>Title</p>', 'Title'],
    		['<h1><p>Title</p></h1>', 'Title'],
    	];
    }

    /**
     * @dataProvider  providerTestCanGetProductPrice
     */
    public function testCanGetProductPrice($raw, $expected) {
    	$s = new ProductScraper();

    	// mock the parser
    	$parser = $this->getMockBuilder('\Sainsburys\Component\Parser\AbstractParser')
    				   ->setMethods(['loadDom', 'extract'])
    				   ->getMock();

	    $parser->expects($this->never())
    		->method('loadDom')
    		->will($this->returnValue(''));

    	$parser->expects($this->once())
    		->method('extract')
    		->will($this->returnValue($raw));


    	$s->setParser($parser);
    	$res = $s->getProductPrice('target');

    	$this->assertEquals($res, $expected);
    }

    public function providerTestCanGetProductPrice() {
		return [
    		['<h1>£5.03</h1>', '5.03'],
    		['<p>£5.03</p>', '5.03'],
    		['<h1><p>£5.03</p></h1>', '5.03'],
    		['<h1><p>£5.1</p></h1>', '5.1'],
    		['<h1>£.03</h1>', '0.03'], // this is a good one!
    		['<h1>£0.1</h1>', '0.1'],
    	];
    }

    /**
     * @dataProvider  providerTestCanGetProductDescription
     */
    public function testCanGetProductDescription($raw, $expected) {
    	$s = new ProductScraper();

    	// mock the parser
    	$parser = $this->getMockBuilder('\Sainsburys\Component\Parser\AbstractParser')
    				   ->setMethods(['loadDom', 'extract'])
    				   ->getMock();

	    $parser->expects($this->never())
    		->method('loadDom')
    		->will($this->returnValue(''));

    	$parser->expects($this->once())
    		->method('extract')
    		->will($this->returnValue($raw));


    	$s->setParser($parser);
    	$res = $s->getProductDescription('target');

    	$this->assertEquals($res, $expected);
    }

    public function providerTestCanGetProductDescription() {
		return [
    		['<h1>Pears</h1>', 'Pears'],
    		['<p>Pears</p>', 'Pears'],
    		['<h1><p>Pears</p></h1>', 'Pears'],
    		['<p></p><p></p><p></p><p>Pears</p>', 'Pears'],
    		['<p>Pears</p><p></p>', 'Pears'], // this is a good one!
    		['<p>Pears</p><p>&nbsp;</p>', 'Pears'],
    		['<p>Pears<p></p></p><p>&nbsp;</p>', 'Pears'],
    		['<p>Pears<p></p></p><p> </p>', 'Pears'],
    	];
    }


    /**
     * @dataProvider  providerTestCanGetProductSize
     */
    public function testCanGetProductSize($raw, $expected) {
    	$s = new ProductScraper();

    	// mock the client
    	$client = $this->getMockBuilder('\Sainsburys\Component\Client\AbstractClient')
    				   ->setMethods(['fetch', 'getRequestSize'])
    				   ->getMock();

    	$client->expects($this->never())
    		->method('fetch')
    		->will($this->returnValue($this->sampleHtmlReturn));

    	$client->expects($this->once())
    		->method('getRequestSize')
    		->will($this->returnValue($raw));


    	$s->setClient($client);
    	$res = $s->getProductSize('target');

    	$this->assertEquals($res, $expected);
    }

    public function providerTestCanGetProductSize() {
		return [
    		['1024', '1.0kb'],
    		['2048', '2.0kb'],
    		[1024+512, '1.5kb'],
    		['0', '0.0kb'],
            ['39185 ', '38.3kb'],
    	];
    }
}