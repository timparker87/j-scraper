<?php 
namespace Sainsburys\Test\Component;

use PHPUnit\Framework\TestCase;
use Sainsburys\Component\ProductParser;
use \stdClass;
use \Exception;

class ProductParserTest extends TestCase {

	var $sampleHtmlReturn = '<div class="wrapper"><span class="price">value</span></div>';

	var $sampleHtmlEmptyReturn = '';
    
    public function testCanLoadDom() {
    	$parser = $this->getMockBuilder('PHPHtmlParser\Dom')
    				   ->setMethods(['load', 'find'])
    				   ->getMock();

    	$parser->expects($this->once())
    		->method('load')
    		->will($this->returnSelf());


    	$parser = new ProductParser($parser);

    	$this->assertEquals($parser->loadDom('content'), true);
    }

	public function testCanExtract() {
    	$parser = $this->getMockBuilder('PHPHtmlParser\Dom')
    				   ->setMethods(['load', 'find'])
    				   ->getMock();

		$parser->expects($this->once())
    		->method('load')
    		->will($this->returnSelf());

    	$parser->expects($this->once())
    		->method('find')
    		->will($this->returnValue('value'));

    	$parser = new ProductParser($parser);
    	$parser->loadDom($this->sampleHtmlReturn);

    	$this->assertEquals($parser->extract('.price'), 'value');
    }    

}