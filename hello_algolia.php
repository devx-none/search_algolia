<?php
# hello_algolia.php
require __DIR__."/vendor/autoload.php";
use Algolia\AlgoliaSearch\SearchClient;


# Connect and authenticate with your Algolia app
$client = SearchClient::create('KV84C6LQ6L','1d2941d117d281701fdd3eebd06f8f78');

# Create a new index and add a record
$index = $client->initIndex("products");
$record = ["objectID" => 1, "name" => "product1"];
$index->saveObject($record)->wait();

# Search the index and print the results
$results = $index->search("test_record");
var_dump($results["hits"][0]);

?>