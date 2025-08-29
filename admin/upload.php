<?php
include "../config.php";

if ($_SERVER["REQUEST_METHOD"] == "POST") {
    if (!isset($_POST['subject']) || trim($_POST['subject']) === "") {
        die("❌ Error: Subject is required.");
    }

    $subject = $_POST['subject'];
    $filename = $_FILES['pdfFile']['name'];
    $tmp_name = $_FILES['pdfFile']['tmp_name'];
    $upload_dir = "../uploads/";
    $branch = $_POST['branch'];
    $year = $_POST['year'];


    if (!is_dir($upload_dir)) {
        mkdir($upload_dir, 0777, true); // Create uploads folder if it doesn't exist
    }

    if (move_uploaded_file($tmp_name, $upload_dir . $filename)) {
        $stmt = $conn->prepare("INSERT INTO files (filename, subject, branch, year) VALUES (?, ?, ?, ?)");
        $stmt->bind_param("ssss", $filename, $subject, $branch, $year);

        if ($stmt->execute()) {
            echo "✅ File uploaded successfully!";
        } else {
            echo "❌ Database error: " . $stmt->error;
        }
    } else {
        echo "❌ Failed to upload file.";
    }
}
?>
<a href="admin_panel.php">Go Back</a>