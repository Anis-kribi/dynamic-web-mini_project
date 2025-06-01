<?php
// Start session and destroy it
session_start();
session_unset();  // Remove all session variables
session_destroy();  // Destroy the session

// Redirect to login page
header("Location: ../connexion/connexion.php");
exit();
?>
