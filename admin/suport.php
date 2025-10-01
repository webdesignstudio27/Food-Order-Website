<?php
include("../connection/connect.php");

error_reporting(0);
session_start();

// Check if the admin is logged in (you can replace this check based on your authentication method)
if (empty($_SESSION["adm_id"])) {
    header('location:index.php');
    exit();
// Check if u_id is passed in the URL
if (isset($_GET['u_id'])) {
    // Retrieve the user ID from the URL
    $u_id = $_GET['u_id'];

    // Fetch messages associated with this user
    $sql = "SELECT * FROM user_messages WHERE u_id = '$u_id' ORDER BY timestamp DESC";
    $query = mysqli_query($db, $sql);

    if (mysqli_num_rows($query) > 0) {
        // Display the messages
        while ($message = mysqli_fetch_array($query)) {
            echo "<p>" . $message['message'] . " - " . date("Y-m-d H:i:s", strtotime($message['timestamp'])) . "</p>";
        }
    } else {
        echo "<p>No messages found for this user.</p>";
    }
} else {
    echo "<p>No user selected.</p>";
}

}

?>

<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="utf-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <meta name="description" content="">
    <meta name="author" content="">
    <link rel="icon" type="image/png" sizes="16x16" href="images/favicon.png">
    <title>All Orders</title>
    <link href="css/lib/bootstrap/bootstrap.min.css" rel="stylesheet">
    <link href="css/helper.css" rel="stylesheet">
    <link href="css/style.css" rel="stylesheet">
    <style>
        #replyButton {
    position: fixed;
    left: 10px;  /* Position 10px from the left of the screen */
    top: 10px;   /* Position 10px from the top of the screen */
    z-index: 1000; /* Ensure the button stays above other content */
}

    </style>

</head>

<body class="fix-header fix-sidebar">

    <div class="preloader">
        <svg class="circular" viewBox="25 25 50 50">
            <circle class="path" cx="50" cy="50" r="20" fill="none" stroke-width="2" stroke-miterlimit="10" />
        </svg>
    </div>

    <div id="main-wrapper">
        <div class="header">
            <nav class="navbar top-navbar navbar-expand-md navbar-light">
                <div class="navbar-header">
                    <a class="navbar-brand" href="dashboard.php">
                        <span><img src="images/kitchen.png" alt="homepage" class="dark-logo" /></span>
                    </a>
                </div>
            </nav>
        </div>

        <div class="left-sidebar">
            <div class="scroll-sidebar">
                <nav class="sidebar-nav">
                    <ul id="sidebarnav">
                        <li class="nav-devider"></li>
                        <li class="nav-label">Home</li>
                        <li> <a href="dashboard.php"><i class="fa fa-tachometer"></i><span>Dashboard</span></a></li>
                        <li class="nav-label">Log</li>
                        <li> <a href="all_users.php"> <span><i class="fa fa-user f-s-20 "></i></span><span>Users</span></a></li>
                        <li> <a class="has-arrow  " href="#" aria-expanded="false"><i class="fa fa-archive f-s-20 color-warning"></i><span class="hide-menu">Restaurant</span></a>
                            <ul aria-expanded="false" class="collapse">
                                <li><a href="all_restaurant.php">All Restaurants</a></li>
                                <li><a href="add_category.php">Add Category</a></li>
                                <li><a href="add_restaurant.php">Add Restaurant</a></li>

                            </ul>
                        </li>
                        <li> <a class="has-arrow  " href="#" aria-expanded="false"><i class="fa fa-cutlery" aria-hidden="true"></i><span class="hide-menu">Menu</span></a>
                            <ul aria-expanded="false" class="collapse">
                                <li><a href="all_menu.php">All Menues</a></li>
                                <li><a href="add_menu.php">Add Menu</a></li>



                            </ul>
                        </li>
                        <li> <a href="all_orders.php"><i class="fa fa-shopping-cart" aria-hidden="true"></i><span>Orders</span></a></li>
                        <li> <a href="last_visit.php"><i class="fa fa-eye" aria-hidden="true"></i><span>Last Visit</span></a></li>
                        <li> <a href="suport.php"><i class="fa fa-comment" aria-hidden="true"></i><span>Messages</span></a></li>

                    
                    </ul>
                </nav>
            </div>
        </div>

        <div class="page-wrapper">
            <div style="padding-top: 10px;">
                <marquee onMouseOver="this.stop()" onMouseOut="this.start()"> <a href="https://www.instagram.com/webdesign_studio_">Web Design Studio</a></marquee>
            </div>

            <div class="container-fluid">
                <div class="row">
                    <div class="col-12">
                        <div class="col-lg-12">
                            <div class="card card-outline-primary">
                                <div class="card-header">
                                    <h4 class="m-b-0 text-white">Customer Support - View All Messages</h4>
                                </div>

                                <div class="table-responsive m-t-40">
                                    <table id="myTable" class="table table-bordered table-striped">
                                        <thead class="thead-dark">
                                            <tr>
                                                <th>User ID</th>
                                                <th>User</th>
                                                <th>Last Message Date</th>
                                                <th>Action</th>
                                            </tr>
                                        </thead>
                                        <tbody>
                                            <?php
                                            // Fetch user data along with the latest message timestamp
                                            $sql = "SELECT users.*, MAX(user_messages.timestamp) as latest_message
                                                    FROM users 
                                                    LEFT JOIN user_messages ON users.u_id = user_messages.u_id 
                                                    GROUP BY users.u_id
                                                    ORDER BY latest_message DESC";
                                            $query = mysqli_query($db, $sql);

                                            if (mysqli_num_rows($query) == 0) {
                                                echo '<tr><td colspan="4"><center>No Messages</center></td></tr>';
                                            } else {
                                                while ($rows = mysqli_fetch_array($query)) {
                                                    echo '<tr>';
                                                    echo '<td>' . $rows['u_id'] . '</td>';
                                                    echo '<td>' . $rows['username'] . '</td>';
                                                    // Display the latest message timestamp (formatted)
                                                    echo '<td>' . date("Y-m-d H:i:s", strtotime($rows['latest_message'])) . '</td>';
                                                    // The Message link should correctly redirect to the reply.php page with the u_id as a GET parameter
                                                    echo '<td><a href="reply.php?u_id=' . $rows['u_id'] . '" class="btn btn-primary">
                                                    <i class="fa fa-eye" aria-hidden="true"></i><span> Messages</span>
                                                    </a></td>';


                                                    echo '</tr>';
                                                }
                                            }
                                            ?>
                                        </tbody>
                                    </table>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
    <script>
   



    </script>

    <?php include "include/footer.php" ?>

    <script src="js/lib/jquery/jquery.min.js"></script>
    <script src="js/lib/bootstrap/js/popper.min.js"></script>
    <script src="js/lib/bootstrap/js/bootstrap.min.js"></script>
    <script src="js/jquery.slimscroll.js"></script>
    <script src="js/sidebarmenu.js"></script>
    <script src="js/lib/sticky-kit-master/dist/sticky-kit.min.js"></script>
    <script src="js/custom.min.js"></script>

</body>

</html>