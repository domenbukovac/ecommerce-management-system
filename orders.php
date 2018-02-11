<?php require('functions.php'); ?>
<!DOCTYPE html>
<html lang="en">
<head>
	<title>Orders</title>
	<meta charset="utf-8">
	<meta name="viewport" content="width=device-width, initial-scale=1">
	<link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/4.7.0/css/font-awesome.min.css">
	<link rel="stylesheet" href="https://maxcdn.bootstrapcdn.com/bootstrap/4.0.0-beta.2/css/bootstrap.min.css">
	<script src="https://ajax.googleapis.com/ajax/libs/jquery/3.2.1/jquery.min.js"></script>
	<script src="https://cdnjs.cloudflare.com/ajax/libs/popper.js/1.12.6/umd/popper.min.js"></script>
	<script src="https://maxcdn.bootstrapcdn.com/bootstrap/4.0.0-beta.2/js/bootstrap.min.js"></script>
	<link rel="stylesheet" href="style.css">
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

					<a href="orders.php" class="current">Orders <span class="badge badge-pill badge-successt">(<?php numberoforders();?>)</span></a> 
				</li>
				<li>
					<a href="#">Invoices</a>
				</li>
				<li>
					<a href="inventory.php">Inventory</a>
				</li>
			</ul>
		</div>
		<!-- /#sidebar-menu -->

		<!-- Page Content -->
		<div id="page-content-wrapper">
			<div class="container-fluid">
				<div class="row">
					<div class="col-sm-5">
						<h3>Orders</h3>
					</div>
					<div class="col-sm-7">
							<button form="narocila" type="submit" type="button" class="btn btn-primary float-right" id="csvizvoz">Izvozi za eSpremnico <i class="fa fa-envelope"></i></button>
					</div>
				</div>
				<div class="row">
					<div class="col-sm-12">
						<div id= "obvestilo"> </div>
					</div>
				</div>

				</div>
				<div class="row">
					<div class="col-sm-12 ">
					<?php include 'tabelaNarocil.php';?>
					</div>
			      
						<div class="col-3">   
            <button id="nazaj" style="display: none;"  onclick="pagination(-1)" type="button" class="btn btn-primary btn-md btn-block raised"><i class="fa fa-angle-left" aria-hidden="true"></i> Nazaj</button>
            </div>
            <div class="col-6">
            </div>
            <div class="col-3">
            <button onclick="pagination(1)" type="button" class="btn btn-primary btn-md btn-block raised">Naprej <i class="fa fa-angle-right" aria-hidden="true"></i></button>
            </div>
				</div>
			</div>
		</div>
	</div>
</body>
</html>
