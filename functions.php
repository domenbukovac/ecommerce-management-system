<?php
require('config.php');

function drzave($kodadrzave){
  $json = file_get_contents('countries.json');
  $json_data = json_decode($json,true);
  foreach ($json_data as $drzava){
    if ($drzava['alpha-2'] == $kodadrzave){
      return $drzava['country-code']; 
    } 
  }
} 

function changeorderstatus($id, $status){
 global $woocommerce;
   $data = [
    'status' => "$status"
  ];

  print_r($woocommerce->put("orders/$id", $data));
  saveorder($id);
}


function deleteorder($id){
 global $woocommerce;
  print_r($woocommerce->delete("orders/$id"));
  saveorder($id);
}

function numberoforders(){
  global $conn;
  $sql = "SELECT OrderID FROM Orders WHERE Status not like '%completed%' AND Status not like '%trash%' AND Status not like '%cancelled%'";
  $result = mysqli_query($conn, $sql);
  $rows = mysqli_num_rows($result);
echo $rows;
}

function saveorder($id){
  global $woocommerce;
  global $conn;
 
if(isset($id)){
 try{
    $woocommerce->get("orders/$id");
  }
    catch (Automattic\WooCommerce\HttpClient\HttpClientException $e){   
    return;
  }
    $narocilo = $woocommerce->get("orders/$id");
    $OrderID = $narocilo['id'];
    $Date = $narocilo['date_created'];
    $PaymentMethod = $narocilo['payment_method_title'];
    $Name = ucwords(strtolower($narocilo['billing']['first_name']));
    $Surname = ucwords(strtolower($narocilo['billing']['last_name']));
    $Address = $narocilo['billing']['address_1'];
    $Address2 = $narocilo['billing']['address_2'];
    $PostalCode = $narocilo['billing']['postcode'];
    $City = $narocilo['billing']['city'];
    $Country = $narocilo['billing']['country'];
    $Email = $narocilo['billing']['email'];
    $Phone = $narocilo['billing']['phone'];
    $CustomerNote = $narocilo['customer_note'];
    $Status = $narocilo['status'];
    $Shipping = $narocilo['shipping_total'];
    $ShippingTax = $narocilo['shipping_tax'];
    $Orders =  $narocilo['line_items'];
    $Total = $narocilo['total'];
		$Company = $narocilo['billing']['company'];
		print_r($narocilo);
    $sqlUpdate = "INSERT INTO Orders (OrderID, Date, PaymentMethod, Name, Surname, Address, Address2, PostCode, City, Country, Email, Phone, CustomerNote, Status, Shipping, ShippingTax, Total, Company) VALUES ($OrderID, '$Date', '$PaymentMethod', '$Name', '$Surname', '$Address', '$Address2', '$PostalCode', '$City', '$Country', '$Email', '$Phone', '$CustomerNote', '$Status', '$Shipping', '$ShippingTax', '$Total', '$Company') ON DUPLICATE KEY UPDATE Status = '$Status'";
    mysqli_query($conn, $sqlUpdate);

    foreach ($Orders as $item) {
      $SqlItem = "INSERT INTO PurchasedItems (OrderID, ProductSKU, Quantity, SubTotalPrice, SubTotalTax, TotalPrice, TotalTax) VALUES ($OrderID,'$item[sku]', '$item[quantity]', '$item[subtotal]', '$item[subtotal_tax]', '$item[total]', '$item[total_tax]')";
      mysqli_query($conn, $SqlItem);
    }
  return;
}
}

function addinvoiceID($orderID, $invoiceID){
	global $conn;
	$sqlUpdate = "UPDATE Orders SET InvoiceID = '$invoiceID' WHERE OrderID = '$orderID'";
	mysqli_query($conn, $sqlUpdate);
}

function updateallorders(){
  global $woocommerce;
  global $conn;

  $narocila =  $woocommerce->get("orders");
	$narocila = json_decode(json_encode($narocila), True);
	$lastOrderID = $narocila[0]['id'];
  $sql = "SELECT OrderID FROM Orders ORDER BY OrderID DESC LIMIT 1";
  $result = $conn->query($sql);
  $lastSavedOrderID = mysqli_fetch_array($result);


	  $forUpdate = range(intval($lastSavedOrderID[0]),intval($lastOrderID));
 // $forUpdate=array();

 $sql = 'SELECT OrderID FROM Orders WHERE Status not like "%completed%" AND Status not like "%trash%"';
 $result2 = $conn->query($sql);
 while($row = $result2->fetch_assoc()){
   array_push($forUpdate,$row['OrderID']);
 }
 $forUpdate = array_unique($forUpdate);

print_r($forUpdate);
 foreach ($forUpdate as $id) {
  saveorder($id);  
 }
}



function updatestock($productID = ''){
  global $woocommerce;
  global $conn;
  
  if($productID != ''){
    $jsonData=$woocommerce->get('products?sku=' . $productID);
    $stock = $jsonData[0]['stock_quantity'];
    $sqlUpdate = "UPDATE Inventory SET Stock = $stock WHERE ProductID = '$productID'";
    mysqli_query($conn, $sqlUpdate);
  }else{
    $sql = 'SELECT ProductID FROM Inventory';
    $result = $conn->query($sql);
    while($row = $result->fetch_assoc())
    {
      $jsonData = $woocommerce->get('products?sku=' . $row['ProductID']);
      $jsonData = json_decode(json_encode($jsonData), True);
			$ProductID = $row['ProductID'];
			$stock = $jsonData[0]['stock_quantity'];
			$sqlUpdate = "UPDATE Inventory SET Stock = $stock WHERE ProductID = '$ProductID'";
			mysqli_query($conn, $sqlUpdate);
    }
  }
}

?>
