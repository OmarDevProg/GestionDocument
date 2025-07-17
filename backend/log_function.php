<?php
function logAction(PDO $pdo, $userId, $actionType, $fileId = null, $description = '') {
    $stmt = $pdo->prepare("INSERT INTO action_history (user_id, action_type, file_id, description)
                           VALUES (?, ?, ?, ?)");
    $stmt->execute([$userId, $actionType, $fileId, $description]);
}
