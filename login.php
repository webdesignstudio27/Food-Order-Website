<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <title>Login || Web Design Studio</title>

    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/meyer-reset/2.0/reset.min.css">

    <link rel='stylesheet prefetch' href='https://fonts.googleapis.com/css?family=Roboto:400,100,300,500,700,900|RobotoDraft:400,100,300,500,700,900'>
    <link rel='stylesheet prefetch' href='https://maxcdn.bootstrapcdn.com/font-awesome/4.3.0/css/font-awesome.min.css'>

    <link rel="stylesheet" href="css/login.css">

    <style type="text/css">
        #buttn {
            color: #fff;
            background-color: #5c4ac7;
        }
    </style>

    <meta name="viewport" content="width=device-width, initial-scale=1, shrink-to-fit=no">
    <link href="css/bootstrap.min.css" rel="stylesheet">
    <link href="css/font-awesome.min.css" rel="stylesheet">
    <link href="css/animsition.min.css" rel="stylesheet">
    <link href="css/animate.css" rel="stylesheet">
    <link href="css/style.css" rel="stylesheet">

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
                    <img class="img-icon" src="images\orders.png"> My Orders
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
    <div style=" background-image: url('images/img/pimg.jpg');">

    <?php
include("connection/connect.php");
error_reporting(0);
session_start();

// Set timezone to India Standard Time
date_default_timezone_set('Asia/Kolkata');

if (isset($_POST['submit'])) {
    $username = $_POST['username'];
    $password = $_POST['password'];

    if (!empty($username) && !empty($password)) {
        // Secure query with prepared statements
        $stmt = $db->prepare("SELECT * FROM users WHERE username=? AND password=?");
        $stmt->bind_param("ss", $username, $password);
        $stmt->execute();
        $result = $stmt->get_result();
        $row = $result->fetch_assoc();

        if ($row) {
            $_SESSION["user_id"] = $row['u_id']; // Start user session

            // Set a cookie for the username (optional, based on your use case)
            setcookie("user_id", $row['u_id'], time() + (7 * 24 * 60 * 60), "/"); // Cookie valid for 7 days
            setcookie("username", $row['username'], time() + (7 * 24 * 60 * 60), "/"); // Cookie valid for 7 days

            // Update last login date and time
            $current_date_time = date('Y-m-d H:i:s'); // Now in IST
            $updateStmt = $db->prepare("UPDATE users SET date=? WHERE u_id=?");
            $updateStmt->bind_param("si", $current_date_time, $row['u_id']);
            $updateStmt->execute();

            header("refresh:1;url=index.php");
        } else {
            $message = "Invalid Username or Password!";
        }
    } else {
        $message = "Please fill in all fields.";
    }
}
?>



        <div class="pen-title">
            < </div>

                <div class="module form-module">
                    <div class="toggle">

                    </div>
                    <div class="form">
                        <h2>Login to your account</h2>
                        <span style="color:red;"><?php echo $message; ?></span>
                        <span style="color:green;"><?php echo $success; ?></span>
                        <form action="" method="post">
                            <input type="text" placeholder="Username" name="username" />
                            <input type="password" placeholder="Password" name="password" />
                            <input type="submit" id="buttn" name="submit" value="Login" />
                        </form>
                    </div>

                    <div class="cta">Not registered?<a href="registration.php" style="color:#5c4ac7;"> Create an account</a></div>
                </div>
                <script src='http://cdnjs.cloudflare.com/ajax/libs/jquery/2.1.3/jquery.min.js'></script>

                <div class="container-fluid pt-3">
                    <p></p>
                </div>
                <?php include "include/footer.php" ?>

</body>

</html>