<!DOCTYPE html>
<html lang="en">
<?php
include("connection/connect.php");
error_reporting(0);
session_start();

if (empty($_SESSION['user_id'])) {
    header('location:login.php');
} else {
?>

    <head>
        <meta charset="utf-8">
        <meta http-equiv="X-UA-Compatible" content="IE=edge">
        <meta name="viewport" content="width=device-width, initial-scale=1, shrink-to-fit=no">
        <meta name="description" content="">
        <meta name="author" content="">
        <link rel="icon" href="#">
        <title>My Orders</title>
        <link href="css/bootstrap.min.css" rel="stylesheet">
        <link href="css/font-awesome.min.css" rel="stylesheet">
        <link href="css/animsition.min.css" rel="stylesheet">
        <link href="css/animate.css" rel="stylesheet">
        <link href="css/style.css" rel="stylesheet">
        <style type="text/css" rel="stylesheet">
            .indent-small {
                margin-left: 5px;
            }

            .form-group.internal {
                margin-bottom: 0;
            }

            .dialog-panel {
                margin: 10px;
            }

            .datepicker-dropdown {
                z-index: 200 !important;
            }

            .panel-body {
                background: #e5e5e5;
                /* Old browsers */
                background: -moz-radial-gradient(center, ellipse cover, #e5e5e5 0%, #ffffff 100%);
                /* FF3.6+ */
                background: -webkit-gradient(radial, center center, 0px, center center, 100%, color-stop(0%, #e5e5e5), color-stop(100%, #ffffff));
                /* Chrome,Safari4+ */
                background: -webkit-radial-gradient(center, ellipse cover, #e5e5e5 0%, #ffffff 100%);
                /* Chrome10+,Safari5.1+ */
                background: -o-radial-gradient(center, ellipse cover, #e5e5e5 0%, #ffffff 100%);
                /* Opera 12+ */
                background: -ms-radial-gradient(center, ellipse cover, #e5e5e5 0%, #ffffff 100%);
                /* IE10+ */
                background: radial-gradient(ellipse at center, #e5e5e5 0%, #ffffff 100%);
                /* W3C */
                filter: progid:DXImageTransform.Microsoft.gradient(startColorstr='#e5e5e5', endColorstr='#ffffff', GradientType=1);
                font: 600 15px "Open Sans", Arial, sans-serif;
            }

            label.control-label {
                font-weight: 600;
                color: #777;
            }

            /* 
table { 
	width: 750px; 
	border-collapse: collapse; 
	margin: auto;
	
	}

/* Zebra striping */
            /* tr:nth-of-type(odd) { 
	background: #eee; 
	}

th { 
	background: #404040; 
	color: white; 
	font-weight: bold; 
	
	}

td, th { 
	padding: 10px; 
	border: 1px solid #ccc; 
	text-align: left; 
	font-size: 14px;
	
	} */
            @media only screen and (max-width: 760px),
            (min-device-width: 768px) and (max-device-width: 1024px) {

                /* table { 
	  	width: 100%; 
	}

	
	table, thead, tbody, th, td, tr { 
		display: block; 
	} */


                /* thead tr { 
		position: absolute;
		top: -9999px;
		left: -9999px;
	}
	
	tr { border: 1px solid #ccc; } */

                /* td { 
		
		border: none;
		border-bottom: 1px solid #eee; 
		position: relative;
		padding-left: 50%; 
	}

	td:before { 
		
		position: absolute;
	
		top: 6px;
		left: 6px;
		width: 45%; 
		padding-right: 10px; 
		white-space: nowrap;
		
		content: attr(data-column);

		color: #000;
		font-weight: bold;
	} */

            }
        </style>

    </head>

    <body>


        <header id="header" class="header-scroll top-header headrom">

            <nav class="navbar navbar-dark">
                <div class="container">
                    <button class="navbar-toggler hidden-lg-up" type="button" data-toggle="collapse" data-target="#mainNavbarCollapse">&#9776;</button>
                    <a class="navbar-brand" href="index.php"> <img class="img-rounded" src="images/logo.png" alt="" width="18%"> </a>
                    <div class="collapse navbar-toggleable-md  float-lg-right" id="mainNavbarCollapse">
                        <ul class="nav navbar-nav">
                            <li class="nav-item">
                                <a class="nav-link active" href="index.php">
                                    <img class="img-icon" src="images/home.png"> Home
                                    <span class="sr-only">(current)</span>
                                </a>
                            </li>
                            <li class="nav-item">
                                <a class="nav-link active" href="restaurants.php">
                                    <img class="img-icon" src="images/rest.png"> Restaurants
                                </a>
                            </li>
                            <?php
                            if (empty($_SESSION["user_id"])) { // If user is not logged in
                                echo '<li class="nav-item">
                <a href="login.php" class="nav-link active">
                    <img class="img-icon" src="images/user.png"> Login
                </a>
              </li>
              <li class="nav-item">
                <a href="registration.php" class="nav-link active">
                    <img class="img-icon" src="images/reg.png"> Register
                </a>
              </li>';
                            } else {
                                echo '<li class="nav-item">
                <a href="your_orders.php" class="nav-link active">
                    <img class="img-icon" src="images/orders.png"> My Orders
                </a>
              </li>
              <li class="nav-item">
                <a href="logout.php" class="nav-link active">
                    <img class="img-icon" src="images/logout.png"> Logout
                </a>
              </li>';
                            }
                            ?>
                        </ul>
                    </div>
                </div>
            </nav>

        </header>
        <div class="page-wrapper">



            <div class="inner-page-hero bg-image" data-image-src="images/img/pimg.jpg">
                <div class="container"> </div>

            </div>
            <div class="result-show">
                <div class="container">
                    <div class="row">


                    </div>
                </div>
            </div>

            <section class="restaurants-page">
                <div class="container">
                    <div class="row">
                        <div class="col-xs-12">
                        </div>
                        <div class="col-xs-12">



                            <div class="bg-gray">
                                <div class="row">

                                    <table class="table table-bordered table-hover">
                                        <thead style="background: #404040; color:white;">
                                            <tr>
                                                <th>Item</th>
                                                <th>Total Quantity</th>
                                                <th>Total Price</th>
                                                <th>Status</th>
                                                <th>Date</th>
                                                <th>Action</th>
                                                <th>Generate Bill</th> <!-- New column -->
                                            </tr>
                                        </thead>
                                        <tbody>
                                            <?php
                                            $query_res = mysqli_query($db, "select * from users_orders where u_id='" . $_SESSION['user_id'] . "'");
                                            if (!mysqli_num_rows($query_res) > 0) {
                                                echo '<td colspan="7"><center>You have No orders Placed yet. </center></td>';
                                            } else {
                                                while ($row = mysqli_fetch_array($query_res)) {
                                            ?>
                                            
                                                    <tr>
                                                        <td data-column="Item"><?php echo $row['title']; ?></td>
                                                        <td data-column="Quantity"><?php echo $row['tot_qty']; ?></td>
                                                        <td data-column="Price">₹<?php echo $row['tot_price']; ?></td>
                                                        <td data-column="Status">
                                                            <?php
                                                            $status = $row['status'];
                                                            if ($status == "" or $status == "NULL") {
                                                                echo '<button type="button" class="btn btn-info"><span class="fa fa-bars" aria-hidden="true"></span> Dispatch</button>';
                                                            } elseif ($status == "in process") {
                                                                echo '<button type="button" class="btn btn-warning"><span class="fa fa-cog fa-spin" aria-hidden="true"></span> On The Way!</button>';
                                                            } elseif ($status == "closed") {
                                                                echo '<button type="button" class="btn btn-success"><span class="fa fa-check-circle" aria-hidden="true"></span> Delivered</button>';
                                                            } elseif ($status == "rejected") {
                                                                echo '<button type="button" class="btn btn-danger"><i class="fa fa-close"></i> Cancelled</button>';
                                                            }
                                                            ?>
                                                        </td>
                                                        <td data-column="Date"><?php echo $row['date']; ?></td>
                                                        <td data-column="Action">
                                                            <a href="delete_orders.php?order_del=<?php echo $row['o_id']; ?>" onclick="return confirm('Are you sure you want to cancel your order?');" class="btn btn-danger btn-flat btn-addon btn-xs m-b-10">
                                                                <i class="fa fa-trash-o" style="font-size:16px"></i>
                                                            </a>
                                                        </td>
                                                        <td data-column="Action">
                                                            <a href="generate_bill.php?order_id=<?php echo $row['o_id']; ?>" class="btn btn-primary btn-flat btn-addon btn-xs m-b-10">
                                                                <i class="fa fa-file-text-o" style="font-size:16px"></i> Generate Bill
                                                            </a>
                                                        </td>

                                                    </tr>
                                            <?php }
                                            } ?>
                                        </tbody>
                                    </table>




                                </div>

                            </div>



                        </div>



                    </div>
                </div>
        </div>
        </section>


        <?php include "include/footer.php" ?>

        </div>


        <script src="js/jquery.min.js"></script>
        <script src="js/tether.min.js"></script>
        <script src="js/bootstrap.min.js"></script>
        <script src="js/animsition.min.js"></script>
        <script src="js/bootstrap-slider.min.js"></script>
        <script src="js/jquery.isotope.min.js"></script>
        <script src="js/headroom.js"></script>
        <script src="js/foodpicky.min.js"></script>
        <script src="js/jquery.js"></script>
    </body>

</html>
<?php
}
?>