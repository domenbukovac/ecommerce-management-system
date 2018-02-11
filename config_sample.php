<?php


define("URL", "https://www.samplewoocommercewebsite.com");


require(__DIR__ . '/vendor/autoload.php');
use Automattic\WooCommerce\Client;


// Mysql connection to databse
$conn = new mysqli("localhost", "mysql username", "password","Database");
$conn->set_charset("utf8" );
if ($conn->connect_error) {
    die("Connection failed: " . $conn->connect_error);
}
  /*WooCommerce API connect*/
  $woocommerce = new Client(
      'your woocommerce store url',
      'woo commerce API key',
      'woo commerce API secret',
      [
          'wp_api' => true,
          'version' => 'wc/v2'
      ]
  );

?>
