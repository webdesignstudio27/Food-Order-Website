<?php
session_start();
include("../connection/connect.php"); // Database connection

// Check if admin is logged in
if (!isset($_SESSION['adm_id'])) {
    header("Location: dashboard.php");
    exit();
}

// Check if u_id is provided (from URL or some other source)
$u_id = isset($_GET['u_id']) ? $_GET['u_id'] : null;

if (!$u_id) {
    echo "User ID is not provided.";
    exit();
}

// Fetch messages and replies combined, sorted by time for a specific user
$query = "
    SELECT um.u_id, u.username, 'user' AS sender, um.message AS message, um.timestamp 
    FROM user_messages um 
    INNER JOIN users u ON um.u_id = u.u_id
    WHERE um.u_id = :u_id  -- Filter by u_id
    UNION ALL
    SELECT ar.u_id, u.username, 'admin' AS sender, ar.reply AS message, ar.timestamp 
    FROM admin_replies ar
    INNER JOIN users u ON ar.u_id = u.u_id
    WHERE ar.u_id = :u_id  -- Filter by u_id
    ORDER BY timestamp ASC
";

$stmt = $pdo->prepare($query);
$stmt->bindParam(':u_id', $u_id, PDO::PARAM_INT);
$stmt->execute();
$messages = $stmt->fetchAll(PDO::FETCH_ASSOC);

// Handle admin reply
if ($_SERVER['REQUEST_METHOD'] == 'POST' && isset($_POST['reply'], $_POST['u_id'])) {
    $reply = $_POST['reply'];
    $u_id = $_POST['u_id'];

    // Check if the user ID exists
    $checkUserStmt = $pdo->prepare("SELECT COUNT(*) FROM users WHERE u_id = ?");
    $checkUserStmt->execute([$u_id]);
    $userExists = $checkUserStmt->fetchColumn();

    if ($userExists) {
        // Insert the reply into the admin_replies table
        $insertReplyStmt = $pdo->prepare("INSERT INTO admin_replies (u_id, reply) VALUES (?, ?)");
        $insertReplyStmt->execute([$u_id, $reply]);

        // Reload the page to prevent duplicate submissions
        header("Location: reply.php?u_id=" . $u_id);
        exit();
    } else {
        echo "<script>alert('Invalid User ID: The user does not exist.');</script>";
    }
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
    <title>Admin Reply</title>
    <style>
        /* Add your styles here */
        body {
            font-family: 'Segoe UI', Tahoma, Geneva, Verdana, sans-serif;
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
            display: flex;
            flex-direction: column;
            gap: 5px;
        }

        .user-msg {
            background-color: #A2F6BF;
            color: #333;
            padding: 8px 12px;
            border-radius: 15px;
            max-width: 70%;
            align-self: flex-start;
            font-size: 12px;
            word-wrap: break-word;
        }

        .admin-reply {
            background-color: #A2F6BF;
            color: #333;
            padding: 8px 12px;
            border-radius: 15px;
            max-width: 70%;
            align-self: flex-end;
            font-size: 12px;
            word-wrap: break-word;
        }

        .timestamp {
            font-size: 10px;
            color: #888;
            margin-top: 3px;
        }

        .user-timestamp {
            text-align: left;
        }

        .admin-timestamp {
            text-align: right;
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

        .reply-btn {
            background-color:  #25D366;
            color: white;
            border: none;
            border-radius: 20px;
            padding: 10px 20px;
            cursor: pointer;
            font-size: 16px;
        }

        .reply-btn:hover {
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
    <div class="chat-header">Admin Chat</div>
    <div class="message-box" id="chat-box">
        <?php
        if (!empty($messages)) {
            $previous_u_id = null;
            foreach ($messages as $message) {
                if ($previous_u_id != $message['u_id']) {
                    if ($previous_u_id != null) {
                        echo "</div>"; // Close the previous conversation
                    }
                    echo "<div class='message-box'>";
                    echo "<div class='chat-header'>Conversation with " . htmlspecialchars($message['username']) . "</div>";
                    $previous_u_id = $message['u_id'];
                }

                echo "<div class='message'>";
                if ($message['sender'] === 'user') {
                    echo "<div class='user-msg'>" . htmlspecialchars($message['message']) . "</div>";
                    echo "<div class='timestamp user-timestamp'>" . date("Y-m-d H:i:s", strtotime($message['timestamp'])) . "</div>";
                } else {
                    echo "<div class='admin-reply'>" . htmlspecialchars($message['message']) . "</div>";
                    echo "<div class='timestamp admin-timestamp'>" . date("Y-m-d H:i:s", strtotime($message['timestamp'])) . "</div>";
                }
                echo "</div>";
            }
        } else {
            echo "<p>No messages found for this user.</p>";
        }
        ?>
    </div>

    <!-- Reply form for admin -->
    <form method="POST" action="" id="reply-form" class="input-area">
        <textarea name="reply" rows="1" placeholder="Type your reply..." required></textarea>
        <input type="hidden" name="u_id" value="<?php echo htmlspecialchars($u_id); ?>">
        <button type="submit" class="reply-btn"><i class="fa fa-paper-plane" aria-hidden="true"></i></button>
    </form>
    <a href="suport.php" class="back-btn">Back</a>
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
