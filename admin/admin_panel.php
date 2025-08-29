<div id="messageBox">
    <?php
    if (isset($_GET['message'])) {
        echo "<p class='success-message'>{$_GET['message']}</p>";
    }
    if (isset($_GET['error'])) {
        echo "<p class='error-message'>{$_GET['error']}</p>";
    }
    ?>
</div>

<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <title>Admin Panel</title>
    <link rel="stylesheet" href="../style.css">
    <style>
        .success-message {
            color: green;
            font-weight: bold;
            background: #d4edda;
            padding: 10px;
            border-radius: 5px;
            width: fit-content;
        }

        .error-message {
            color: red;
            font-weight: bold;
            background: #f8d7da;
            padding: 10px;
            border-radius: 5px;
            width: fit-content;
        }

        .logout-btn {
            display: inline-block;
            padding: 10px 15px;
            background-color: red;
            color: white;
            text-decoration: none;
            font-weight: bold;
            border-radius: 5px;
            position: absolute;
            top: 20px;
            right: 20px;
        }

        .logout-btn:hover {
            background-color: darkred;
        }
    </style>
</head>

<body>

    <div class="container">
        <h2>📂 Upload a New Study Material PDF</h2>

        <br>

        <form action="upload.php" method="POST" enctype="multipart/form-data">
            <label>📚 Select Subject:</label>
            <select name="subject" required>
                <option value="">-- Select Subject --</option>
                <option value="DSPD">DSPD</option>
                <option value="APP">APP</option>
                <option value="ST">ST</option>
                <option value="WDD">WDD</option>
                <option value="DBMS">DBMS</option>
                <option value="OOPJ">OOPJ</option>
                <option value="DEV">DEV</option>
                <option value="Honours">Honours</option>
            </select>

            <br><br>

            <label>🏫 Select Branch:</label>
            <select name="branch" required>
                <option value="">-- Select Branch --</option>
                <option value="CT">Computer Technology</option>
                <option value="CSE">Computer Science Engineering</option>
                <option value="IT">Information Technology</option>
            </select>

            <br><br>

            <label>📆 Select Year:</label>
            <select name="year" required>
                <option value="">-- Select Year --</option>
                <option value="1st Year">1st Year</option>
                <option value="2nd Year">2nd Year</option>
                <option value="3rd Year">3rd Year</option>
                <option value="4th Year">4th Year</option>
            </select>

            <br><br>

            <label>📆 Select Semester:</label>
            <select name="semester" required>
                <option value="">-- Select Semester --</option>
                <option value="1st Semester">1st Semester</option>
                <option value="2nd Semester">2nd Semester</option>
                <option value="3rd Semester">3rd Semester</option>
                <option value="4th Semester">4th Semester</option>
                <option value="5th Semester">5th Semester</option>
                <option value="6th Semester">6th Semester</option>
                <option value="7th Semester">7th Semester</option>
                <option value="8th Semester">8th Semester</option>
            </select>

            <br><br><br>

            <label>📂 Upload PDF:</label>
            <input type="file" name="pdfFile" required>

            <button type="submit">Upload</button>
        </form>

        <h2>📄 Uploaded Study Materials</h2>
        <ul>
            <?php
            include "../config.php";
            $result = $conn->query("SELECT * FROM files ORDER BY id DESC");
            while ($row = $result->fetch_assoc()) {
                echo "<li>
                    <strong>{$row['subject']}</strong> - 
                    <a href='../uploads/{$row['filename']}' target='_blank'>{$row['filename']}</a>
                    <button onclick=\"deleteFile({$row['id']}, '{$row['filename']}')\">🗑 Delete</button>
                </li>";
            }
            ?>
        </ul>

        <div class="logout-container">
            <a href="../logout.php" onclick="return confirmLogout()" class="logout-btn">Logout</a>
        </div>

        <script>
            function deleteFile(fileId, fileName) {
                if (confirm("Are you sure you want to delete " + fileName + "?")) {
                    window.location.href = "delete.php?id=" + fileId;
                }
            }

            // Hide message box after 3 seconds
            setTimeout(function () {
                var messageBox = document.getElementById("messageBox");
                if (messageBox) {
                    messageBox.style.display = "none";
                }
            }, 3000);

            function confirmLogout() {
                if (confirm("Are you sure you want to logout?")) {
                    window.location.href = "../logout.php"; // Redirect to logout script
                    return false; // Prevent the default link action
                } else {
                    return false; // Prevent logout if canceled
                }
            }
        </script>
    </div>

</body>

</html>