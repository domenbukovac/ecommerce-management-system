<?php require('functions.php'); ?>

<!DOCTYPE html>
<html lang="en">
<head>
	<title>Inventory</title>
	<meta charset="utf-8">
	<meta name="viewport" content="width=device-width, initial-scale=1">
	<link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/4.7.0/css/font-awesome.min.css">
	<link rel="stylesheet" href="https://maxcdn.bootstrapcdn.com/bootstrap/4.0.0-beta.2/css/bootstrap.min.css">
	<script src="https://ajax.googleapis.com/ajax/libs/jquery/3.2.1/jquery.min.js"></script>
	<script src="https://cdnjs.cloudflare.com/ajax/libs/popper.js/1.12.6/umd/popper.min.js"></script>
	<script src="https://maxcdn.bootstrapcdn.com/bootstrap/4.0.0-beta.2/js/bootstrap.min.js"></script>
	<link rel="stylesheet" href="style.css">
	<link href="https://cdnjs.cloudflare.com/ajax/libs/x-editable/1.5.0/bootstrap3-editable/css/bootstrap-editable.css" rel="stylesheet"/>
				<script src="https://cdnjs.cloudflare.com/ajax/libs/x-editable/1.5.0/bootstrap3-editable/js/bootstrap-editable.min.js"></script>
	<script src="script.js"></script>
</head>
<body>

	<nav class="navbar navbar-expand-sm navbar-dark sticky-top" style="background-color: #2d3f50;">
		<a class="navbar-brand" href="#" id="menu-toggle" ><i class="fa fa-bars fa-lg" aria-hidden="true" style="transform: scale(1.3,1);"></i> </a>
		<ul class="nav navbar-nav navbar-dark navbar-center">
			<li class="nav-item">
				<a class="nav-link brand" href="" data-target="#myModal" data-toggle="modal">Ecommerce management system</a>
			</li>
		</ul>
		<ul class="navbar-nav ml-auto">
			<li class="nav-item">
				<a class="nav-link" href="" data-target="#myModal" data-toggle="modal">User</a>
			</li>
			<li class="nav-item">
				<a class="nav-link" href="" data-target="#myModal" data-toggle="modal"><i class="fa fa-cog" aria-hidden="true"></i></a>
			</li>
		</ul>
	</nav>

	<div id="wrapper" class="toggled">
		<!-- Sidebar-menu -->
		<div id="sidebar-wrapper">
			<ul class="sidebar-nav">
				<li>
					<a href="#">Dashboard</a>
				</li>
				<li>
					<a href="orders.php">Orders <span class="badge badge-pill badge-successt">(<?php numberoforders();?>)</span></a>
				</li>
				<li>
					<a href="#">Invoices</a>
				</li>
				<li>
					<a href="inventory.php"	class="current">Inventory</a>
				</li>
			</ul>
		</div>
		<!-- /#sidebar-menu -->
	<!-- Page Content -->
		<div id="page-content-wrapper">
			<div class="container-fluid">
				<div class="row">
					<div class="col-sm-7">
						<h3>Inventory</h3>
					</div>
					<div class="col-sm-5">
					</div>

				</div>
				<div class="row">
					<div class="col-sm-12 ">
						<div class="input-group" style="margin-top:10px;">
  						<input type="text" id="myInput" onkeyup="searchInventory()" class="form-control"  placeholder="&#xF002; Išči po skladišču" style="font-family:Arial, FontAwesome">
						</div>
	
						<table id="table" class="table table-hover table-main">
							<thead>
								<tr>
									<th>Izdelek</th>
									<th>ID Izdelka</th>
									<th>Teža</th>
									<th>Vrednost</th>
									<th>EAN13</th>
									<th>Znamka</i></th>
									<th>Cena</th>
									<th>Cena s popustom</th>
									<th>Zaloga</th>
								</tr>
							</thead>
							<tbody>
								<?php
								$sql = "SELECT * FROM Inventory ORDER BY Stock";
								$result = mysqli_query($conn,$sql);
								while($row = mysqli_fetch_array($result)){
									?>

												<tr id="<?php echo $row['ProductID']?>">
									<td><a href="<?php echo URL;?>/wp-admin/post.php?post=<?php if ($row['ParentID'] == "0"){echo $row['WooID'] . "&action=edit";}else{echo $row['ParentID'] . "&action=edit";}?>" target="_blank"><p class="font-weight-normal"><?php echo $row['ProductName']?></p></a></td>
									<td><p class="font-weight-light"><?php echo $row['ProductID']?></p></td>
									<td><p class="font-weight-light"><?php echo $row['Weight']?> g</p></td>
									<td><p class="font-weight-light"><?php echo $row['Cost']?> €</p></td>
									<td><p class="font-weight-light"><?php echo $row['EAN13']?></p></td>
									<td><p class="font-weight-light"><?php echo $row['Brand']?></p></td>
									<td><p class="font-weight-light"><?php echo $row['RegularPrice']?> €</p></td>
									<td><p class="font-weight-light"><?php if(!empty($row['SalePrice'])){echo $row['SalePrice'] . " €";}else{echo $row['RegularPrice'] . " €";} ?></p></td>
									<td><?php if($row['Stock'] == 0){echo "<p class='text-danger'>0</p>";}elseif($row['Stock'] < 5){echo "<p class='text-warning'>" . $row['Stock'] . "</p>";}else{echo "<p class='text-success'>" . $row['Stock'] . "</p>";}?></td>
												</tr>
												<?php
											}
											$conn->close();
										?>

							</tbody>
						</table>
				 </div>
				</div>
			</div>
		</div>
	</div>
</body>
</html>
				 
