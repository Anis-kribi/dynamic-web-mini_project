<?php
require_once '../dbconnect.php'; // adjust path if needed

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $user_id = $_POST['user_id'] ?? null;

    if ($user_id) {
        try {
            $stmt = $pdo->prepare("DELETE FROM users WHERE user_id = :user_id");
            $stmt->execute([':user_id' => $user_id]);

            header("Location: dashboard.php?msg=User+deleted+successfully");
            exit;
        } catch (PDOException $e) {
            die("Database error: " . $e->getMessage());
        }
    } else {
        header("Location: dashboard.php?error=Missing+user+ID");
        exit;
    }
} else {
    header("Location: dashboard.php");
    exit;
}
