<?php
session_start();
include "config.php"; // Include database connection

if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $username = trim($_POST['username']);
    $password = trim($_POST['password']);
    $mobile = trim($_POST['mobile']);
    $branch = trim($_POST['branch']);
    $year = trim($_POST['year']);
    // Hash the password for security
    $hashed_password = password_hash($password, PASSWORD_DEFAULT);
    $role = 'user';

    // Check if username already exists
    $stmt = $conn->prepare("SELECT id FROM users WHERE username = ?");
    $stmt->bind_param("s", $username);
    $stmt->execute();
    $stmt->store_result();

    if ($stmt->num_rows > 0) {
        echo "<script>alert('⚠️ Username already exists! Try another one.'); window.location.href = 'index.html';</script>";
    } else {
        // Insert new user
        $stmt = $conn->prepare("INSERT INTO users (username, password, mobile,  branch, year) VALUES (?, ?, ?, ?, ?)");
        $stmt->bind_param("sssss", $username, $hashed_password, $mobile, $branch, $year );

        if ($stmt->execute()) {
            echo "<script>alert('✅ Registration Successful! You can now login.');
                  window.location.href = 'index.html';</script>";
        } else {
            echo "<script>alert('❌ Error in registration. Try again later.');</script>";
        }
    }
    $stmt->close();
    $conn->close(); 
}
?>
