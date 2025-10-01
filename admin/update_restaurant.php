<!DOCTYPE html>
<html lang="en">
<?php
include("../connection/connect.php");
error_reporting(0);
session_start();


if (isset($_POST['submit'])) {
    // Check for empty fields
    if (
        empty(trim($_POST['c_name'])) ||
        empty(trim($_POST['res_name'])) ||
        empty(trim($_POST['email'])) ||
        empty(trim($_POST['phone'])) ||
        empty(trim($_POST['url'])) ||
        empty(trim($_POST['o_hr'])) ||
        empty(trim($_POST['c_hr'])) ||
        empty(trim($_POST['o_days'])) ||
        empty(trim($_POST['address']))
    ) {
        $error = '<div class="alert alert-danger alert-dismissible fade show">
                    <button type="button" class="close" data-dismiss="alert" aria-label="Close"><span aria-hidden="true">&times;</span></button>
                    <strong>All fields must be filled out!</strong>
                  </div>';
    } else {
        // File upload handling
        $fname = $_FILES['file']['name'];
        $temp = $_FILES['file']['tmp_name'];
        $fsize = $_FILES['file']['size'];
        $imageFieldUpdated = false;

        // Check if a file is uploaded
        if (!empty($fname)) {
            $extension = strtolower(pathinfo($fname, PATHINFO_EXTENSION));
            $fnew = uniqid() . '.' . $extension;
            $store = "Res_img/" . basename($fnew);

            // Validate file extension
            if (in_array($extension, ['jpg', 'png', 'gif'])) {
                if ($fsize > 1024 * 1024) {
                    $error = '<div class="alert alert-danger alert-dismissible fade show">
                                <button type="button" class="close" data-dismiss="alert" aria-label="Close"><span aria-hidden="true">&times;</span></button>
                                <strong>Max image size is 1024kb!</strong> Try a different image.
                              </div>';
                } else {
                    // Move file and set the image flag
                    move_uploaded_file($temp, $store);
                    $imageFieldUpdated = true;
                }
            } else {
                $error = '<div class="alert alert-danger alert-dismissible fade show">
                            <button type="button" class="close" data-dismiss="alert" aria-label="Close"><span aria-hidden="true">&times;</span></button>
                            <strong>Invalid extension!</strong> Only png, jpg, and gif are accepted.
                          </div>';
            }
        }

        // Proceed if no errors in file validation
        if (!isset($error)) {
            $res_name = mysqli_real_escape_string($db, $_POST['res_name']);
            $email = mysqli_real_escape_string($db, $_POST['email']);
            $phone = mysqli_real_escape_string($db, $_POST['phone']);
            $url = mysqli_real_escape_string($db, $_POST['url']);
            $o_hr = mysqli_real_escape_string($db, $_POST['o_hr']);
            $c_hr = mysqli_real_escape_string($db, $_POST['c_hr']);
            $o_days = mysqli_real_escape_string($db, $_POST['o_days']);
            $address = mysqli_real_escape_string($db, $_POST['address']);
            $c_id = mysqli_real_escape_string($db, $_POST['c_name']);
            $rs_id = mysqli_real_escape_string($db, $_GET['res_upd']);

            // Update query
            $sql = "UPDATE restaurant SET 
                    c_id = '$c_id', 
                    title = '$res_name', 
                    email = '$email', 
                    phone = '$phone', 
                    url = '$url', 
                    o_hr = '$o_hr', 
                    c_hr = '$c_hr', 
                    o_days = '$o_days', 
                    address = '$address'";

            // Add image field to the query if a new image was uploaded
            if ($imageFieldUpdated) {
                $sql .= ", image = '$fnew'";
            }

            $sql .= " WHERE rs_id = '$rs_id'";

            // Execute the query
            if (mysqli_query($db, $sql)) {
                $success = '<div class="alert alert-success alert-dismissible fade show">
                                <button type="button" class="close" data-dismiss="alert" aria-label="Close"><span aria-hidden="true">&times;</span></button>
                                <strong>Record updated successfully!</strong>
                            </div>';
            } else {
                $error = '<div class="alert alert-danger alert-dismissible fade show">
                            <button type="button" class="close" data-dismiss="alert" aria-label="Close"><span aria-hidden="true">&times;</span></button>
                            <strong>Failed to update the record!</strong> Please try again.
                          </div>';
            }
        }
    }
}
?>



?>

<head>
    <meta charset="utf-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <meta name="description" content="">
    <meta name="author" content="">
    <link rel="icon" type="image/png" sizes="16x16" href="images/favicon.png">
    <title>Update Restaurant</title>
    <link href="css/lib/bootstrap/bootstrap.min.css" rel="stylesheet">
    <link href="css/helper.css" rel="stylesheet">
    <link href="css/style.css" rel="stylesheet">
</head>


<body class="fix-header">
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
                <div class="navbar-collapse">

                    <ul class="navbar-nav mr-auto mt-md-0">

                        <li class="nav-item dropdown mega-dropdown"> <a class="nav-link dropdown-toggle text-muted  " href="#" data-toggle="dropdown" aria-haspopup="true" aria-expanded="false"><i class="fa fa-th-large"></i></a>
                            <div class="dropdown-menu animated zoomIn">
                                <ul class="mega-dropdown-menu row">



                                    <li class="col-lg-3  m-b-30">
                                        <h4 class="m-b-20">CONTACT US</h4>

                                        <form>
                                            <div class="form-group">
                                                <input type="text" class="form-control" id="exampleInputname1" placeholder="Enter Name">
                                            </div>
                                            <div class="form-group">
                                                <input type="email" class="form-control" placeholder="Enter email">
                                            </div>
                                            <div class="form-group">
                                                <textarea class="form-control" id="exampleTextarea" rows="3" placeholder="Message"></textarea>
                                            </div>
                                            <button type="submit" class="btn btn-info">Submit</button>
                                        </form>
                                    </li>
                                    <li class="col-lg-3 col-xlg-3 m-b-30">
                                        <h4 class="m-b-20">List style</h4>

                                        <ul class="list-style-none">
                                            <li><a href="javascript:void(0)"><i class="fa fa-check text-success"></i> This Is Another Link</a></li>
                                            <li><a href="javascript:void(0)"><i class="fa fa-check text-success"></i> This Is Another Link</a></li>
                                            <li><a href="javascript:void(0)"><i class="fa fa-check text-success"></i> This Is Another Link</a></li>
                                            <li><a href="javascript:void(0)"><i class="fa fa-check text-success"></i> This Is Another Link</a></li>
                                            <li><a href="javascript:void(0)"><i class="fa fa-check text-success"></i> This Is Another Link</a></li>
                                        </ul>
                                    </li>
                                    <li class="col-lg-3 col-xlg-3 m-b-30">
                                        <h4 class="m-b-20">List style</h4>

                                        <ul class="list-style-none">
                                            <li><a href="javascript:void(0)"><i class="fa fa-check text-success"></i> This Is Another Link</a></li>
                                            <li><a href="javascript:void(0)"><i class="fa fa-check text-success"></i> This Is Another Link</a></li>
                                            <li><a href="javascript:void(0)"><i class="fa fa-check text-success"></i> This Is Another Link</a></li>
                                            <li><a href="javascript:void(0)"><i class="fa fa-check text-success"></i> This Is Another Link</a></li>
                                            <li><a href="javascript:void(0)"><i class="fa fa-check text-success"></i> This Is Another Link</a></li>
                                        </ul>
                                    </li>
                                    <li class="col-lg-3 col-xlg-3 m-b-30">
                                        <h4 class="m-b-20">List style</h4>

                                        <ul class="list-style-none">
                                            <li><a href="javascript:void(0)"><i class="fa fa-check text-success"></i> This Is Another Link</a></li>
                                            <li><a href="javascript:void(0)"><i class="fa fa-check text-success"></i> This Is Another Link</a></li>
                                            <li><a href="javascript:void(0)"><i class="fa fa-check text-success"></i> This Is Another Link</a></li>
                                            <li><a href="javascript:void(0)"><i class="fa fa-check text-success"></i> This Is Another Link</a></li>
                                            <li><a href="javascript:void(0)"><i class="fa fa-check text-success"></i> This Is Another Link</a></li>
                                        </ul>
                                    </li>
                                </ul>
                            </div>
                        </li>

                    </ul>


                    <ul class="navbar-nav my-lg-0">
                        <li class="nav-item dropdown">
                            <a class="nav-link dropdown-toggle text-muted  " href="#" data-toggle="dropdown" aria-haspopup="true" aria-expanded="false"><img src="images/bookingSystem/user-icn.png" alt="user" class="profile-pic" /></a>
                            <div class="dropdown-menu dropdown-menu-right animated zoomIn">
                                <ul class="dropdown-user">
                                    <li><a href="logout.php"><i class="fa fa-power-off"></i> Logout</a></li>
                                </ul>
                            </div>
                        </li>
                    </ul>
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

            <div class="row page-titles">
                <div class="col-md-5 align-self-center">
                    <h3 class="text-primary">Dashboard</h3>
                </div>
            </div>

            <div class="container-fluid">



                <?php echo $error;
                echo $success; ?>




                <div class="col-lg-12">
                    <div class="card card-outline-primary">

                        <h4 class="m-b-0 ">Update Restaurant</h4>

                        <div class="card-body">
                            <form action='' method='post' enctype="multipart/form-data">
                                <div class="form-body">
                                    <?php $ssql = "select * from restaurant where rs_id='$_GET[res_upd]'";
                                    $res = mysqli_query($db, $ssql);
                                    $row = mysqli_fetch_array($res); ?>
                                    <hr>
                                    <div class="row p-t-20">
                                        <div class="col-md-6">
                                            <div class="form-group">
                                                <label class="control-label">Restaurant Name</label>
                                                <input type="text" name="res_name" value="<?php echo $row['title'];  ?>" class="form-control" placeholder="John doe">
                                            </div>
                                        </div>

                                        <div class="col-md-6">
                                            <div class="form-group has-danger">
                                                <label class="control-label">Bussiness E-mail</label>
                                                <input type="text" name="email" value="<?php echo $row['email'];  ?>" class="form-control form-control-danger" placeholder="example@gmail.com">
                                            </div>
                                        </div>

                                    </div>

                                    <div class="row p-t-20">
                                        <div class="col-md-6">
                                            <div class="form-group">
                                                <label class="control-label">Phone </label>
                                                <input type="text" name="phone" class="form-control" value="<?php echo $row['phone'];  ?>" placeholder="1-(555)-555-5555">
                                            </div>
                                        </div>

                                        <div class="col-md-6">
                                            <div class="form-group has-danger">
                                                <label class="control-label">website URL</label>
                                                <input type="text" name="url" class="form-control form-control-danger" value="<?php echo $row['url'];  ?>" placeholder="http://example.com">
                                            </div>
                                        </div>

                                    </div>


                                    <div class="row">
                                        <div class="col-md-6">
                                            <div class="form-group">
                                                <label class="control-label">Open Hours</label>
                                                <select name="o_hr" class="form-control custom-select" data-placeholder="Choose a Category">
                                                    <option>--Select your Hours--</option>
                                                    <option value="6am" <?php if ($row['o_hr'] == '6am') echo 'selected'; ?>>6am</option>
                                                    <option value="7am" <?php if ($row['o_hr'] == '7am') echo 'selected'; ?>>7am</option>
                                                    <option value="8am" <?php if ($row['o_hr'] == '8am') echo 'selected'; ?>>8am</option>
                                                    <option value="9am" <?php if ($row['o_hr'] == '9am') echo 'selected'; ?>>9am</option>
                                                    <option value="10am" <?php if ($row['o_hr'] == '10am') echo 'selected'; ?>>10am</option>
                                                    <option value="11am" <?php if ($row['o_hr'] == '11am') echo 'selected'; ?>>11am</option>
                                                </select>
                                            </div>
                                        </div>


                                        <div class="col-md-6">
                                            <div class="form-group">
                                                <label class="control-label">Close Hours</label>
                                                <select name="c_hr" class="form-control custom-select" data-placeholder="Choose a Category">
                                                    <option>--Select your Hours--</option>
                                                    <option value="3pm" <?php if ($row['c_hr'] == '3pm') echo 'selected'; ?>>3pm</option>
                                                    <option value="4pm" <?php if ($row['c_hr'] == '4pm') echo 'selected'; ?>>4pm</option>
                                                    <option value="5pm" <?php if ($row['c_hr'] == '5pm') echo 'selected'; ?>>5pm</option>
                                                    <option value="6pm" <?php if ($row['c_hr'] == '6pm') echo 'selected'; ?>>6pm</option>
                                                    <option value="7pm" <?php if ($row['c_hr'] == '7pm') echo 'selected'; ?>>7pm</option>
                                                    <option value="8pm" <?php if ($row['c_hr'] == '8pm') echo 'selected'; ?>>8pm</option>
                                                    <option value="9pm" <?php if ($row['c_hr'] == '9pm') echo 'selected'; ?>>9pm</option>
                                                    <option value="10pm" <?php if ($row['c_hr'] == '10pm') echo 'selected'; ?>>10pm</option>
                                                    <option value="11pm" <?php if ($row['c_hr'] == '11pm') echo 'selected'; ?>>11pm</option>
                                                    <option value="12pm" <?php if ($row['c_hr'] == '12pm') echo 'selected'; ?>>12pm</option>
                                                </select>
                                            </div>
                                        </div>

                                        <div class="col-md-6">
                                            <div class="form-group">
                                                <label class="control-label">Open Days</label>
                                                <select name="o_days" class="form-control custom-select" data-placeholder="Choose a Category" tabindex="1">
                                                    <option>--Select your Days--</option>
                                                    <option value="mon-tue" <?php if ($row['o_days'] == 'mon-tue') echo 'selected'; ?>>mon-tue</option>
                                                    <option value="mon-wed" <?php if ($row['o_days'] == 'mon-wed') echo 'selected'; ?>>mon-wed</option>
                                                    <option value="mon-thu" <?php if ($row['o_days'] == 'mon-thu') echo 'selected'; ?>>mon-thu</option>
                                                    <option value="mon-fri" <?php if ($row['o_days'] == 'mon-fri') echo 'selected'; ?>>mon-fri</option>
                                                    <option value="mon-sat" <?php if ($row['o_days'] == 'mon-sat') echo 'selected'; ?>>mon-sat</option>
                                                    <option value="24hr-x7" <?php if ($row['o_days'] == '24hr-x7') echo 'selected'; ?>>24hr-x7</option>
                                                </select>
                                            </div>
                                        </div>
                                        <div class="col-md-6">
                                            <div class="form-group has-danger">
                                                <label class="control-label">Image</label>
                                                <div class="mb-3">
                                                    <p>Current File: <strong><?php echo $row['image']; ?></strong></p>


                                                    <?php if (!empty($row['image'])): ?>
                                                        <div>
                                                            <img src="Res_img/<?php echo $row['image']; ?>" alt="Current Image" style="max-width: 150px; max-height: 150px; border: 1px solid #ddd; padding: 5px;">
                                                        </div>
                                                    <?php else: ?>
                                                        <p>No image uploaded.</p>
                                                    <?php endif; ?>
                                                </div>

                                                <input type="file" name="file" id="lastName" class="form-control form-control-danger" placeholder="Upload New Image">
                                            </div>
                                        </div>



                                        <div class="col-md-12">
                                            <div class="form-group">
                                                <label class="control-label">Select Category</label>
                                                <select name="c_name" class="form-control custom-select" data-placeholder="Choose a Category" tabindex="1">
                                                    <option>--Select Category--</option>
                                                    <?php

                                                    $selected_category_id =  $row['c_id'];


                                                    $ssql = "SELECT * FROM res_category";
                                                    $res = mysqli_query($db, $ssql);

                                                    while ($rows = mysqli_fetch_array($res)) {

                                                        if ($rows['c_id'] == $selected_category_id) {
                                                            echo '<option value="' . $rows['c_id'] . '" selected>' . $rows['c_name'] . '</option>';
                                                        } else {
                                                            echo '<option value="' . $rows['c_id'] . '">' . $rows['c_name'] . '</option>';
                                                        }
                                                    }
                                                    ?>
                                                </select>
                                            </div>
                                        </div>





                                    </div>

                                    <h3 class="box-title m-t-40">Restaurant Address</h3>
                                    <hr>


                                    <div class="row">
                                        <div class="col-md-12 ">
                                            <div class="form-group">

                                                <textarea name="address" type="text" style="height:100px;" class="form-control"> <?php echo $row['address']; ?> </textarea>
                                            </div>
                                        </div>
                                    </div>


                                </div>
                        </div>
                        <div class="form-actions">
                            <input type="submit" name="submit" class="btn btn-primary" value="Save">
                            <a href="all_restaurant.php" class="btn btn-inverse">Cancel</a>
                        </div>
                        </form>
                    </div>
                </div>
            </div>
            <?php include "include/footer.php" ?>
        </div>

    </div>


    </div>

    </div>

    <script src="js/lib/jquery/jquery.min.js"></script>
    <script src="js/lib/bootstrap/js/popper.min.js"></script>
    <script src="js/lib/bootstrap/js/bootstrap.min.js"></script>
    <script src="js/jquery.slimscroll.js"></script>
    <script src="js/sidebarmenu.js"></script>
    <script src="js/lib/sticky-kit-master/dist/sticky-kit.min.js"></script>
    <script src="js/custom.min.js"></script>

</body>


</html>