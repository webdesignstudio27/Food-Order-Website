<?php
session_start();
include("connection/connect.php"); // Database connection

// Check if user is logged in
if (!isset($_SESSION['user_id'])) {
    header("Location: login.php");
    exit();
}

$limit = 20; // Number of messages to display per page
$page = isset($_GET['page']) ? intval($_GET['page']) : 1;
$offset = ($page - 1) * $limit;

$query = "SELECT 
            'user' AS sender, 
            um.message AS content, 
            um.timestamp AS msg_time 
          FROM user_messages um 
          WHERE um.u_id = ? 
          UNION ALL 
          SELECT 
            'admin' AS sender, 
            ar.reply AS content, 
            ar.timestamp AS msg_time 
          FROM admin_replies ar 
          WHERE ar.u_id = ? 
          ORDER BY msg_time ASC";

$stmt = $pdo->prepare($query);
$stmt->execute([$_SESSION['user_id'], $_SESSION['user_id']]);
$messages = $stmt->fetchAll(PDO::FETCH_ASSOC);

// Handle new user message submission
if ($_SERVER['REQUEST_METHOD'] == 'POST' && isset($_POST['message'])) {
    $message = $_POST['message'];
    $u_id = $_SESSION['user_id']; // Ensure the session user ID is used

    // Insert the new user message into the database
    $insertMessageStmt = $pdo->prepare("INSERT INTO user_messages (u_id, message) VALUES (?, ?)");
    $insertMessageStmt->execute([$u_id, $message]);

    // Reload the page to show the new message
    header("Location: chat.php");
    exit();
}
?>

<!DOCTYPE html>
<html lang="en">

<head>
<meta charset="UTF-8">
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
        body {
            font-family: 'Arial', sans-serif;
            background-color: #f0f0f0;
            margin: 0;
            padding: 0;
            display: flex;
            justify-content: center;
            align-items: center;
            height: 100vh;
        }

        .chat-container {
            width: 100%;
            max-width: 500px;
            height: 90vh;
            background-color: #ffffff;
            border-radius: 10px;
            box-shadow: 0 0 15px rgba(0, 0, 0, 0.1);
            display: flex;
            flex-direction: column;
            overflow: hidden;
        }

        .chat-header {
            background-color: #128C7E;
            color: white;
            padding: 15px;
            text-align: center;
            font-size: 20px;
            font-weight: bold;
        }

        .message-box {
            flex: 1;
            padding: 10px;
            overflow-y: auto;
            background-color: #455a64;
            display: flex;
            flex-direction: column;
            gap: 20px;
        }

        .message {
            max-width: 70%;
            padding: 10px 15px;
            border-radius: 20px;
            word-wrap: break-word;
            font-size: 14px;
            position: relative;
        }
        
        .user-msg {
            background-color:#A2F6BF;
            align-self: flex-end;
        }

        .admin-reply {
            background-color:#A2F6BF;
            align-self: flex-start;
        }

        .timestamp {
            font-size: 12px;
            color: #888;
            margin-top: 5px;
            position: absolute;
            bottom: -18px;
            white-space: nowrap;
        }

        .user-msg .timestamp {
            right: 10px;
            
        }

        .admin-reply .timestamp {
            left: 10px;
        }

        .input-area {
            padding: 10px;
            border-top: 1px solid #eee;
            display: flex;
            gap: 10px;
            background-color: #128C7E;
        }

        textarea {
            flex: 1;
            resize: none;
            border: 1px solid #ddd;
            border-radius: 20px;
            padding: 10px;
            font-size: 14px;
            background-color: #A2F6BF;
        }

        .send-btn {
            background-color:  #25D366;
            color: white;
            border: none;
            border-radius: 20px;
            padding: 10px 20px;
            cursor: pointer;
            font-size: 16px;
        }

        .send-btn:hover {
            background-color: #077C30;
        }
        .back-btn {
            display: block;
            color: black;
            border: none;
            font-size: 16px;
            text-align: center;
            margin-top: 10px;
           
        }

        .back-btn:hover {
            color: red;
        }
    </style>
</head>

<body>

    <div class="chat-container">
        <div class="chat-header">Chat Messenger</div>
        <div class="message-box" id="chat-box">
            <?php
            foreach ($messages as $message) {
                $class = $message['sender'] === 'user' ? 'user-msg' : 'admin-reply';
                $timeAlign = $message['sender'] === 'user' ? 'right' : 'left';
                echo "<div class='message $class'>";
                echo htmlspecialchars($message['content']);
                echo "<span class='timestamp' style='text-align:$timeAlign'>" . date("h:i A", strtotime($message['msg_time'])) . "</span>";
                echo "</div>";
            }
            ?>
        </div>

        <form method="POST" action="" class="input-area">
            <textarea name="message" rows="1" placeholder="Type your message..." required></textarea>
            <button type="submit" class="send-btn"><i class="fa fa-paper-plane" aria-hidden="true"></button>
        </form>
        <a href="index.php" class="back-btn">Back</a> 
    </div>
    
    <script>
        // Scroll to the bottom of the chat on page load
        const chatBox = document.getElementById('chat-box');
        chatBox.scrollTop = chatBox.scrollHeight;

        // Scroll to the bottom after message is sent
        const form = document.querySelector('form');
        form.addEventListener('submit', () => {
            setTimeout(() => chatBox.scrollTop = chatBox.scrollHeight, 100);
        });
    </script>
</body>

</html>
