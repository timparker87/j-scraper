<?php 
namespace Sainsburys\Component\Parser;

abstract class AbstractParser {
	abstract public function loadDom(String $content);
	abstract public function extract(String $target);
}