<?php
session_start();
include "../config.php";

// Check if a subject is provided
if (!isset($_GET['subject'])) {
    die("Error: No subject selected.");
}

$subject = $_GET['subject'];

// Fetch files for the selected subject
$query = $conn->prepare("SELECT * FROM files WHERE subject = ?");
$query->bind_param("s", $subject);
$query->execute();
$result = $query->get_result();
?>

<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title><?php echo htmlspecialchars($subject); ?> Files</title>
    <link rel="stylesheet" href="subject_files.css">
</head>

<body>
    <h2>📂 Study Materials for <?php echo htmlspecialchars($subject); ?></h2>
    <a href="user_panel.php" class="back-btn">⬅ Back</a>
    <ul>
        <?php while ($row = $result->fetch_assoc()): ?>
            <ul class="file-list">
                <li class="file-box">
                    <a href="../uploads/<?php echo $row['filename']; ?>" target="_blank"><?php echo $row['filename']; ?>
                        <button class="view-btn">👁 View</button>
                    </a>
                </li>
            </ul>
        <?php endwhile; ?>
    </ul>
</body>

</html>