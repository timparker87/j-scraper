<?php 
namespace Sainsburys\Test\Component;

use PHPUnit\Framework\TestCase;
use Sainsburys\Component\ProductClient;
use \stdClass;
use \Exception;

class ProductClientTest extends TestCase {

	var $sampleHtmlReturn = '<div class="wrapper"><span class="price">£0.53</span></div>';
    
    public function testCanFetch() {
    	$guz = $this->getMockBuilder('GuzzleHttp\Client')
    				   ->setMethods(['get', 'getHeader', 'getBody'])
    				   ->getMock();

    	$guz->expects($this->once())
    		->method('get')
    		->will($this->returnSelf());

		$guz->expects($this->once())
    		->method('getHeader')
    		->will($this->returnValue([1024]));

    	$guz->expects($this->once())
    		->method('getBody')
    		->will($this->returnValue($this->sampleHtmlReturn));

    	$client = new ProductClient($guz);

    	$this->assertEquals($client->fetch('url'), $this->sampleHtmlReturn);
    }

	public function testCanGetSize() {
    	$guz = $this->getMockBuilder('GuzzleHttp\Client')
    				   ->setMethods(['get', 'getHeader', 'getBody'])
    				   ->getMock();

    	$guz->expects($this->once())
    		->method('get')
    		->will($this->returnSelf());

		$guz->expects($this->once())
    		->method('getHeader')
    		->will($this->returnValue([1024]));

    	$guz->expects($this->once())
    		->method('getBody')
    		->will($this->returnValue($this->sampleHtmlReturn));

    	$client = new ProductClient($guz);
    	$client->fetch('url');

    	$this->assertEquals($client->getRequestSize(), '1024');
    }    

}