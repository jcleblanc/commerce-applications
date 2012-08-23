<?php
require_once("finding.php");

$ebay = new ebay();

//search by keyword - keywords can be space separated list of searches
print_r($ebay->findProduct('findItemsByKeywords', 'Dresses Pants', 2));

//search by category - dresses = 63861, pants = 63863
print_r($ebay->findProduct('findItemsByCategory', '63861', 2));

//search by product id - little mermaid = 53039031
print_r($ebay->findProduct('findItemsByProduct', '53039031'));

//get histogram data by category
print_r($ebay->getHistograms('63861'));

//get keyword search recommendations
print_r($ebay->getKeywordRecommendations('acordian'));
?>