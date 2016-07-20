<?php 
namespace Sainsburys\Component\Client;

abstract class AbstractClient {
	abstract public function fetch(String $url);
}