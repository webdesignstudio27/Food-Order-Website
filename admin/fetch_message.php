<?php
function getUserMessages($pdo) {
    $query = "SELECT * FROM user_messages WHERE is_admin = 'no' ORDER BY timestamp ASC";
    $stmt = $pdo->prepare($query);
    $stmt->execute();
    return $stmt->fetchAll();
}

function insertAdminReply($pdo, $reply, $message_id) {
    $stmt = $pdo->prepare("INSERT INTO admin_replies (u_id, reply) SELECT u_id, ? FROM user_messages WHERE id = ?");
    $stmt->execute([$reply, $message_id]);
}

function markMessageAsReplied($pdo, $message_id) {
    $stmt = $pdo->prepare("UPDATE user_messages SET is_admin = 'yes' WHERE id = ?");
    $stmt->execute([$message_id]);
}
?>
