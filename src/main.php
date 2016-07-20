<?php 
/**
 * Entry point to the app. 
 * 
 * Using the Product Components, the code loads from the test URL and extracts the 
 * Product URLs. These are subsequently loaded and a collection of Products is created,
 * which is then serialized to JSON.
 */
require __DIR__.'/../vendor/autoload.php';

use Sainsburys\Component\ProductScraper;
use Sainsburys\Component\ProductParser;
use Sainsburys\Component\ProductClient;
use Sainsburys\Component\Collection;
use Sainsburys\Model\Product;

if (empty($argv[1])) {
	echo "Please specify a Product Index Page URL to scrape \n";
	exit;
} else {
	$url = $argv[1];
}

$s = new ProductScraper();

/**
 * Parser and Client implementations extend relevant Abstract classes. If we wanted to
 * change these implementations we can by extending the Abstract classes.
 */
$parser = new ProductParser(new \PHPHtmlParser\Dom());
$client = new ProductClient(new \GuzzleHttp\Client());

$s->setParser($parser);
$s->setClient($client);

$collection = new Collection();

if ($s->load($url)) {

	// use the scraper to extract product URLs	
	$urls = $s->getProductUrls('.productInfo a');
	
	foreach ($urls as $key => $productUrl) {

		// foreach URL, load the page using the scraper
		$s->load($productUrl);

		// extract information and build product object
		$title       = $s->getProductTitle('.productTitleDescriptionContainer h1');
		$price       = $s->getProductPrice('.pricePerUnit');
		$description = $s->getProductDescription('.productText');
		$size        = $s->getProductSize();

		$p = new Product();
		$p->setTitle($title);
		$p->setPrice($price);
		$p->setDescription($description);
		$p->setSize($size);

		// add to the collection
		$collection->addItem($p);
	}
} else {
	// not loaded
}

// all finished at this point, serialize the result
echo json_encode($collection);
?>