 <?php include_once('functions.php'); ?>
 <table id="table" class="table table-hover table-main">
  <thead>
    <tr>
      <th></th>
      <th>ID</th>
      <th>Datum</th>
      <th>Naročnik</th>
			<th>Država</th>
      <th>Email</th>
      <th><i class="fa fa-file-text"></i></th>
      <th>Plačilo</th>
      <th >Znesek</th>
      <th>Status</th>
    </tr>
  </thead>
  <tbody>
    <?php				
		$itemsperpage = 10;
		$page = 0;
		if(isset($_GET["page"])){$page = $_GET["page"] * $itemsperpage;}

    $sql = "SELECT * FROM Orders WHERE Status not like '%trash%' ORDER BY OrderID DESC LIMIT $page,$itemsperpage";	
    $result = mysqli_query($conn,$sql);
    while($row = mysqli_fetch_array($result)){
      ?>

    <tr onclick="show_hide_row('<?php echo $row['OrderID']?>');" id="<?php echo $row['OrderID']?>">
      <td onclick="show_hide_row('<?php echo $row['OrderID']?>');" id="<?php echo $row['OrderID']?>"><div class="checkbox"><label><input type="checkbox" name="narocilo[]" value="<?php echo $row['OrderID']?>"></label></div></td>
      <td><a href = "<?php echo URL . "/wp-admin/post.php?post=" . $row['OrderID'] . "&action=edit"?>" target = "_blank">#<?php echo $row['OrderID']?></a></td>
      <td><?php echo date("d.m.Y H:i", strtotime($row['Date']))?></td>
      <td><?php echo $row['Name'] . " " . $row['Surname']; ?></td>
      <td><?php echo $row['Country']?></td>
			<td><?php echo $row['Email']?></td>
      <td> <?php if (!empty($row['CustomerNote'])){echo "<i class='fa fa-file-text-o'></i>";}else{echo "-";}?></td>
      <td><?php if ($row['PaymentMethod'] == "PayPal"){echo " <p class='text-primary'><i class='fa fa-paypal fa-lg' aria-hidden='true'></i></p>";}elseif ($row['PaymentMethod'] == "Prednakazilo"){echo "<mark>Prednakazilo</mark>";}else{echo $row['PaymentMethod'];} ?></p></td>           
      <td><?php echo $row['Total']?>€</td>
      <td><?php switch ($row['Status']) {case 'cancelled': echo "<p class='text-danger'>Preklicano</p>"; break; case 'completed': echo "<p class='text-primary'>Zaključeno</p>"; break; case 'processing': echo "<p class='text-success'>V obdelavi</p>"; break;case 'on-hold': echo "<p class='text-warning'>Zadržano</p>"; break; case 'pending': echo "<p class='text-warning'>V čakanju plačila</p>"; break; }?></p></td>
    </tr>
    <tr id="expand<?php echo $row['OrderID']?>" class="hidden_row">
      <td colspan=12>
        <div class="row">
          <div class="col-md-6">
            <h5>Podrobnosti naročila</h5>
            <table class="table table-sub">
              <thead>
                <tr>
                  <th>Izdelek</th>
                  <th>Količina</th>
                  <th>Cena</th>
									<th>DDV</th>	
                  <th>Znesek</th>
                </tr>
              </thead>
              <tbody>


                <?php
                $sql2 = "SELECT * FROM PurchasedItems WHERE OrderID = $row[OrderID]";
                $result2 = mysqli_query($conn,$sql2);
                while($row2 = mysqli_fetch_array($result2)){
                ?>
                <tr>
                  <td><?php print mysqli_fetch_row(mysqli_query($conn,"SELECT ProductName FROM Inventory WHERE ProductID = $row2[ProductSKU]"))[0]?>  </td>
                  <td><?php echo $row2['Quantity']?></td>
                  <td><?php echo $value = ($row2['TotalPrice']) / (float) $row2['Quantity']?> €</td>
                  <td><?php echo $value = ($row2['TotalTax']) / (float) $row2['Quantity']?> €</td>
									<td><?php echo $row2['TotalPrice'] + $row2['TotalTax']?> €</td>
                </tr>
                <?php
                }
                ?>
                <tr>
                  <td>Dostava</td>
                  <td>1</td>
                  <td><?php echo $shipping = number_format($row['Shipping'], 2)?> €</td>
									 <td><?php echo $shipping = number_format($row['ShippingTax'], 2)?> €</td>
                  <td><?php echo $shipping = number_format($row['Shipping'] + $row['ShippingTax'], 2)?> €</td>
                </tr>
                <tr>
                  <td><b>Skupaj</b></td>
                  <td></td>
                  <td></td>
									<td></td>
                  <td><b><?php echo $row['Total']?> €</b></td>
                </tr>
              </tbody>
            </table>
          </div>
          <div class="col-md-6">
            <div class="" style="padding-top: 5px; margin-bottom: 10px;">
            <?php
            if (is_null($row['Invoice'])){echo "<button type=\"button\" class=\"btn btn-primary disabled\" onclick=\"changeclass(this);\"><i class=\"fa fa-plus\" aria-hidden=\"true\"></i> Ustvari račun</button> ";}else{echo " <button type=\"button\" class=\"btn btn-primary disabled\"><i class=\"fa fa-print\" aria-hidden=\"true\"></i> Natisni račun</button> ";}

            switch ($row['Status']){

            case 'on-hold': echo "<button type=\"button\" class=\"btn btn-primary\" onclick=\"changeorderstatus('{$row['OrderID']}','processing', this);\"> <i class=\"fa fa-ellipsis-h\" aria-hidden=\"true\"></i> Označi kot plačano</button> <button type=\"button\" class=\"btn btn-danger\" onclick=\"changeorderstatus('{$row['OrderID']}','cancelled', this);\"> <i class=\"fa fa-times\" aria-hidden=\"true\"></i> Prekliči naročilo</button>"; break;

            case 'processing': echo "<button type=\"button\" class=\"btn btn-success\" onclick=\"changeorderstatus('{$row['OrderID']}','completed', this);\"> <i class=\"fa fa-check\" aria-hidden=\"true\"></i> Zaključi naročilo</button> <button type=\"button\" class=\"btn btn-danger\" onclick=\"changeorderstatus('{$row['OrderID']}','cancelled', this);\"> <i class=\"fa fa-times\" aria-hidden=\"true\"></i> Prekliči naročilo</button>";break;
            }

            ?>

            </div>
            <h5><?php if (!empty($row['CustomerNote'])){echo "Sporočilo Stranke";}else{}?></h5>
            <p><?php echo $row['CustomerNote']?></p>
						<p>
						<strong>Naslov:</strong>
						<br>
						<?php if(!empty($row['Company'])){ echo $row['Company'] . " <br>";} ?>  
						<?php echo $row['Name'] . " " . $row['Surname'];?>
						<br>
						<?php echo $row['Address'];?> 
						<br>
						<?php echo $row['PostCode'] . " " . $row['City'];?> 
						</p>
						<p><?php if(!empty($row['Address2'])){ echo "<strong>Opomba: </strong> </br>" . $row['Address2'] . " </br><br>";} ?>
						<strong>Telefon: </strong> <?php echo $row['Phone'];?> </br>
						<strong>Email: </strong> <?php echo $row['Email'];?></p>
						
						<?php if(!empty($row['InvoiceID'])){
						echo "<strong>Številka računa: </strong>" . $row['InvoiceID'];
						}
						else{
						?>	
						
						<form class="form-inline addinvoice">
  						<input type="text" id="order<?php echo $row['OrderID'];?>"  class="form-control mb-2 mr-sm-2 mb-sm-0" name="invoiceID"  placeholder="Številka računa">
							<button type="button" class="btn btn-primary" onclick="AddInvoice(<?php echo $row['OrderID'];?>)">Vnesi</button>
						</form>	
					
						<?php } ?>


          </div>
        </div>
      </td>
    </tr>
        <?php
          }
          $conn->close();
        ?>

  </tbody>
</table>
