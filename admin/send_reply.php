<?php
include("../connection/connect.php");
session_start();

// Ensure the admin is logged in
if (!isset($_SESSION["adm_id"])) {
    echo "Admin not logged in.";
    exit();
}

// Check if message and user ID are provided
if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['msg'], $_POST['u_id'])) {
    $admin_reply = htmlspecialchars($_POST['msg'], ENT_QUOTES, 'UTF-8');
    $user_id = intval($_POST['u_id']);
    $timestamp = date("Y-m-d H:i:s");

    // Insert admin's reply into the chat table
    $query = "INSERT INTO chat (u_id, msg, sender_type, timestamp) 
              VALUES ('$user_id', '$admin_reply', 'bot', '$timestamp')";

    if (mysqli_query($db, $query)) {
        echo "Message sent successfully!";
    } else {
        echo "Error: " . mysqli_error($db);
    }
} else {
    echo "No message or user ID provided!";
}
?>
