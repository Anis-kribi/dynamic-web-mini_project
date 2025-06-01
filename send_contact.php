<?php
// Include the database connection
require_once("../dbconnect.php");

// Check if the form is submitted
if ($_SERVER["REQUEST_METHOD"] == "POST") {
    // Collect and sanitize the form data
    $nom = htmlspecialchars(trim($_POST['name']));
    $email = htmlspecialchars(trim($_POST['email']));
    $subject = htmlspecialchars(trim($_POST['subject']));
    $message = htmlspecialchars(trim($_POST['message']));

    // Check if all fields are filled
    if (!empty($nom) && !empty($email) && !empty($subject) && !empty($message)) {

        // Prepare the SQL query to insert the data into the contact table
        $sql = "INSERT INTO contact (nom, email, subject, message) VALUES (:nom, :email, :subject, :message)";

        // Prepare the statement
        $stmt = $conn->prepare($sql);

        // Bind parameters
        $stmt->bindParam(':nom', $nom);
        $stmt->bindParam(':email', $email);
        $stmt->bindParam(':subject', $subject);
        $stmt->bindParam(':message', $message);

        // Execute the query
        if ($stmt->execute()) {
            // Redirect to the contact page with a success message
            header("Location: contact.php?success=1");
            exit();
        } else {
            echo "Erreur : Impossible d'enregistrer le message.";
        }

    } else {
        echo "Veuillez remplir tous les champs.";
    }
} else {
    echo "Méthode non autorisée.";
}
?>
