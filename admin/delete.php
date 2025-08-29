<?php
include "../config.php";

if (isset($_GET['id'])) {
    $fileId = $_GET['id'];

    // Fetch file details
    $stmt = $conn->prepare("SELECT filename FROM files WHERE id = ?");
    $stmt->bind_param("i", $fileId);
    $stmt->execute();
    $stmt->bind_result($filename);
    $stmt->fetch();
    $stmt->close();

    if ($filename) {
        $filePath = "../uploads/" . $filename;

        // Delete the file from the server
        if (file_exists($filePath)) {
            unlink($filePath);
        }

        // Delete file record from the database
        $stmt = $conn->prepare("DELETE FROM files WHERE id = ?");
        $stmt->bind_param("i", $fileId);
        if ($stmt->execute()) {
            header("Location: admin_panel.php?message=✅ File deleted successfully!");
            exit();
        } else {
            header("Location: admin_panel.php?error=❌ Failed to delete file from database.");
            exit();
        }
    } else {
        header("Location: admin_panel.php?error=❌ File not found!");
        exit();
    }
} else {
    header("Location: admin_panel.php?error=❌ Invalid request.");
    exit();
}
?>
