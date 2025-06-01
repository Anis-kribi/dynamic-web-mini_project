<?php
require_once("../dbconnect.php");

if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $username = $_POST['username'] ?? '';
    $full_name = $_POST['full_name'] ?? '';
    $email = $_POST['email'] ?? '';
    $password = $_POST['password'] ?? '';
    $role = $_POST['role'] ?? 'user';

    if (!empty($username) && !empty($email) && !empty($password)) {
        // Hash password
        $hashed_password = password_hash($password, PASSWORD_BCRYPT);

        $stmt = $pdo->prepare("
            INSERT INTO users (username, full_name, email, password, role)
            VALUES (:username, :full_name, :email, :password, :role)
        ");
        
        $stmt->execute([
            ':username' => $username,
            ':full_name' => $full_name,
            ':email' => $email,
            ':password' => $hashed_password,
            ':role' => $role
        ]);

        header("Location: dashboard.php?success=1");
        exit();
    } else {
        header("Location: dashboard.php?error=1");
        exit();
    }
}
?>
