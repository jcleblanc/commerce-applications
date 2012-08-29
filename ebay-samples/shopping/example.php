<?php
require_once("shopping.php");
$shopping = new eBayShopping();

//search for Half.com products by keyword
echo "<h1>Half Products</h1>";
print_r($shopping->find('FindHalfProducts', 'Harry Potter', 2));

echo "<h1>Popular Items</h1>";
print_r($shopping->find('FindPopularItems', 'Harry Potter', 2));

echo "<h1>Popular Searches</h1>";
print_r($shopping->find('FindPopularSearches', 'Harry Potter', 2));

echo "<h1>Products</h1>";
$alt = array('AvailableItemsOnly' => 'true');
print_r($shopping->find('FindProducts', 'Harry Potter', 2, $alt));

echo "<h1>Reviews and Guides</h1>";
print_r($shopping->find('FindReviewsAndGuides', 'Harry Potter', 2));

echo "<h1>Get Time</h1>";
print_r($shopping->getTime());

echo "<h1>Category Information</h1>";
print_r($shopping->get('GetCategoryInfo', '279'));

echo "<h1>Item Status</h1>";
print_r($shopping->get('GetItemStatus', "221116737034,360483973597"));

echo "<h1>Multiple Items</h1>";
print_r($shopping->get('GetMultipleItems', "221116737034,360483973597"));

echo "<h1>Shipping Costs</h1>";
$alt = array("DestinationCountryCode" => "US",
             "DestinationPostalCode" => "95128",
             "IncludeDetails" => true,
             "QuantitySold" => 1);
print_r($shopping->get('GetShippingCosts', $ids, $alt));

echo "<h1>Single Item</h1>";
print_r($shopping->get('GetSingleItem', "221116737034"));

echo "<h1>User Profile</h1>";
print_r($shopping->get('GetUserProfile', "jcleblanc"));
?>