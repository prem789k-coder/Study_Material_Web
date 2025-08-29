<?php
include "../config.php";
session_start();  // Start session for user tracking

// Simulate user login (Replace with real authentication system)
$user_id = $_SESSION['user_id'] ?? 1;  // Assume user ID 1 for testing

if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $file_id = $_POST['file_id'];
    $rating = $_POST['rating'];

    // Check if user already rated this file
    $check_query = "SELECT * FROM ratings WHERE file_id = ? AND user_id = ?";
    $stmt = $conn->prepare($check_query);
    $stmt->bind_param("ii", $file_id, $user_id);
    $stmt->execute();
    $result = $stmt->get_result();

    if ($result->num_rows > 0) {
        echo "You have already rated this file.";
    } else {
        // Insert rating
        $insert_query = "INSERT INTO ratings (file_id, user_id, rating) VALUES (?, ?, ?)";
        $stmt = $conn->prepare($insert_query);
        $stmt->bind_param("iii", $file_id, $user_id, $rating);
        if ($stmt->execute()) {
            echo "Thank you for rating!";
        } else {
            echo "Failed to submit rating.";
        }
    }
}
?>
<a href="user_panel.php">Go Back</a>
