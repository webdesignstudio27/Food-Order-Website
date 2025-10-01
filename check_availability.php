<<<<<<< HEAD
<?php
include("connection/connect.php"); // Ensure this file has your database connection logic

if (isset($_POST['username'])) {
    $username = $_POST['username'];
    $query = "SELECT username FROM users WHERE username = '$username'";
    $result = mysqli_query($db, $query);
    if (mysqli_num_rows($result) > 0) {
        echo "<span style='color:red;'>Username is already taken!</span>";
    } else {
        echo "<span style='color:green;'></span>";
    }
}

if (isset($_POST['email'])) {
    $email = $_POST['email'];
    $query = "SELECT email FROM users WHERE email = '$email'";
    $result = mysqli_query($db, $query);
    if (mysqli_num_rows($result) > 0) {
        echo "<span style='color:red;'>Email is already taken!</span>";
    } else {
        echo "<span style='color:green;'></span>";
    }
}

if (isset($_POST['phone'])) {
    $phone = $_POST['phone'];
    $query = "SELECT phone FROM users WHERE phone = '$phone'";
    $result = mysqli_query($db, $query);
    if (mysqli_num_rows($result) > 0) {
        echo "<span style='color:red;'>Phone number is already registered!</span>";
    } else {
        echo "<span style='color:green;'></span>";
    }
}
?>
=======
<?php
include("connection/connect.php"); // Ensure this file has your database connection logic

if (isset($_POST['username'])) {
    $username = $_POST['username'];
    $query = "SELECT username FROM users WHERE username = '$username'";
    $result = mysqli_query($db, $query);
    if (mysqli_num_rows($result) > 0) {
        echo "<span style='color:red;'>Username is already taken!</span>";
    } else {
        echo "<span style='color:green;'></span>";
    }
}

if (isset($_POST['email'])) {
    $email = $_POST['email'];
    $query = "SELECT email FROM users WHERE email = '$email'";
    $result = mysqli_query($db, $query);
    if (mysqli_num_rows($result) > 0) {
        echo "<span style='color:red;'>Email is already taken!</span>";
    } else {
        echo "<span style='color:green;'></span>";
    }
}

if (isset($_POST['phone'])) {
    $phone = $_POST['phone'];
    $query = "SELECT phone FROM users WHERE phone = '$phone'";
    $result = mysqli_query($db, $query);
    if (mysqli_num_rows($result) > 0) {
        echo "<span style='color:red;'>Phone number is already registered!</span>";
    } else {
        echo "<span style='color:green;'></span>";
    }
}
?>
>>>>>>> 0ea36b08fba4a2fd4e40b676ae2b1ec945104fd7
