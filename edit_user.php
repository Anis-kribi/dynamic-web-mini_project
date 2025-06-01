<?php
require_once '../dbconnect.php'; // adjust path if needed

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $user_id = $_POST['user_id'] ?? null;
    $username = trim($_POST['username'] ?? '');
    $full_name = trim($_POST['full_name'] ?? '');
    $email = trim($_POST['email'] ?? '');

    if ($user_id && $username && $full_name && $email) {
        try {
            $stmt = $pdo->prepare("
                UPDATE users
                SET username = :username,
                    full_name = :full_name,
                    email = :email
                WHERE user_id = :user_id
            ");
            $stmt->execute([
                ':username' => $username,
                ':full_name' => $full_name,
                ':email' => $email,
                ':user_id' => $user_id
            ]);

            header("Location: dashboard.php?msg=User+updated+successfully");
            exit;
        } catch (PDOException $e) {
            die("Database error: " . $e->getMessage());
        }
    } else {
        header("Location: dashboard.php?error=Missing+or+invalid+data");
        exit;
    }
} else {
    header("Location: dashboard.php");
    exit;
}
