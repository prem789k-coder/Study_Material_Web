<?php
session_start();
include "../config.php";

// Check if user is logged in
// if (!isset($_SESSION['user_id'])) {
//     die("Error: User not logged in.");
// }

// // Get user details
// $user_id = $_SESSION['user_id'];
// $user_query = $conn->prepare("SELECT branch, year FROM users WHERE id = ?");
// $user_query->bind_param("i", $user_id);
// $user_query->execute();
// $user_result = $user_query->get_result();
// $user = $user_result->fetch_assoc();

// if (!$user) {
//     die("Error: User not found.");
// }

// $branch = $user['branch'];
// $year = $user['year'];

$branch = isset($_GET['branch']) ? $_GET['branch'] : '';
$year = isset($_GET['year']) ? $_GET['year'] : '';


// // Fetch unique subjects based on branch and year
// $subjects = $conn->prepare("SELECT DISTINCT subject FROM files WHERE branch = ? AND year = ?");
// $subjects->bind_param("ss", $branch, $year);
// $subjects->execute();
// $subjects_result = $subjects->get_result();

$subjects = $conn->prepare("SELECT DISTINCT subject FROM files WHERE branch = ? AND year = ?");
$subjects->bind_param("ss", $branch, $year);
$subjects->execute();
$subjects_result = $subjects->get_result();

$subjects = $conn->prepare("SELECT DISTINCT subject FROM files WHERE branch = ? AND year = ?");
$subjects->bind_param("ss", $branch, $year);


// Get selected subject from dropdown (if any)
$subject_filter = isset($_GET['subject']) ? $_GET['subject'] : "";

// Get search query (if any)
$search_query = isset($_GET['search']) ? $_GET['search'] : "";

// Fetch study materials for user's branch and year
$query = "SELECT * FROM files WHERE branch = ? AND year = ?";
$params = [$branch, $year];
$types = "ss";

if (!empty($subject_filter)) {
    $query .= " AND subject = ?";
    $params[] = $subject_filter;
    $types .= "s";
}

if (!empty($search_query)) {
    $query .= " AND filename LIKE ?";
    $params[] = "%$search_query%";
    $types .= "s";
}

$stmt = $conn->prepare($query);
$stmt->bind_param($types, ...$params);
$stmt->execute();
$result = $stmt->get_result();
?>
<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>User Panel</title>
    <link rel="stylesheet" href="../style.css">
    <link rel="stylesheet" href="../styleforuser.css">
    <style>
        .container {
            max-width: 800px;
            /* Increase the width */
            width: 90%;
            /* Ensure responsiveness */
            margin: auto;
            padding: 20px;
        }

        .subject-container {
            display: grid;
            grid-template-columns: repeat(2, 1fr);
            /* 2 subjects per row */
            gap: 20px;
            /* Space between subjects */
            justify-content: center;
            padding: 20px;
        }

        a {
            text-decoration: none;
        }


        /* Individual Subject Box */
        .subject-box {
            background: rgba(0, 0, 0, 0.2);
            padding: 15px;
            border-radius: 8px;
            text-align: center;
            font-size: 18px;
            font-weight: bold;
            color: white;
            box-shadow: 0px 4px 10px rgba(60, 206, 220, 0.2);
            cursor: pointer;
            transition: transform 0.3s ease, background 0.3s ease;
            font-size: 30px;
            margin-bottom: 20px;
            color: rgb(213, 6, 6);
            text-shadow: 2px 2px 10px rgba(56, 4, 228, 0.8);
        }

        .subject-box:hover {
            transform: translateY(-5px);
            background: #444;
            /* Slightly lighter on hover */
        }

        /* Responsive: 1 subject per row on smaller screens */
        @media (max-width: 768px) {
            .subject-container {
                grid-template-columns: repeat(1, 1fr);
                /* 1 per row */
            }
        }

        /* Highlighted: Slider Styles */
        .slider-container {
            margin: auto;
            display: flex;
            justify-content: center;
            width: 100%;
            max-width: 600px;
            overflow: hidden;
            position: relative;
        }

        .slider {
            display: flex;
            transition: transform 0.5s ease-in-out;
        }

        .slide {
            min-width: 100%;
            display: flex;
            justify-content: center;
            align-items: center;
        }

        .slide img {
            max-width: 100%;
            max-height: 400px;
            object-fit: contain;
        }

        .scroll-container {
            max-height: 400px;
            overflow-y: auto;
        }

        .prev,
        .next {
            position: absolute;
            top: 50%;
            transform: translateY(-50%);
            background-color: rgba(0, 0, 0, 0.5);
            color: white;
            border: none;
            padding: 10px;
            cursor: pointer;
        }

        .prev {
            left: 0;
        }

        .next {
            right: 0;
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
        <h2>📄 Available Study Materials</h2>

        <!-- Slider Start -->
        <div class="slider-container">
            <div class="slider">
                <div class="slide"><img src="pce.png" alt="PCE"></div>
                <div class="slide"><img src="timetable.png" alt="4th Sem Time Table"></div>
                <div class="slide"><img src="syllabus.jpg" alt="4th Sem Syllabus"></div>
            </div>
            <div class="prev" onclick="moveSlide(-1)">&#10094;</div>
            <div class="next" onclick="moveSlide(1)">&#10095;</div>
        </div>
        <!-- Slider End -->

        <!-- Search and Filter -->
        <form method="GET">
            <input type="text" name="search" placeholder="Search PDF..."
                value="<?php echo htmlspecialchars($search_query); ?>">
            <select name="subject">
                <option value="">All Subjects</option>
                <?php while ($row = $subjects_result->fetch_assoc()): ?>
                    <option value="<?php echo $row['subject']; ?>" <?php if ($subject_filter == $row['subject'])
                           echo 'selected'; ?>>
                        <?php echo $row['subject']; ?>
                    </option>
                <?php endwhile; ?>
            </select>
            <button type="submit">🔍 Search</button>
        </form>

        <!-- Display files in subject-wise boxes -->
        <div class="subject-container">
            <?php
            $subject_data = [];
            while ($row = $result->fetch_assoc()) {
                $subject_data[$row['subject']][] = $row;
            }
            foreach ($subject_data as $subject => $files) {
                echo "<a href='subject_files.php?subject=" . urlencode($subject) . "' class='subject-box'>$subject</a>";
            }
            ?>
        </div>

        <!-- Logout
        <div class="logout-container">
            <a href="../logout.php" onclick="return confirmLogout()" class="logout-btn">Logout</a>
        </div>
    </div> -->
    <script>
        let slideIndex = 0;
        function moveSlide(step) {
            const slides = document.querySelectorAll(".slide");
            slideIndex = (slideIndex + step + slides.length) % slides.length;
            document.querySelector(".slider").style.transform = `translateX(-${slideIndex * 100}%)`;
        }

        function confirmLogout() {
            if (confirm("Are you sure you want to logout?")) {
                window.location.href = "../logout.php";
                return false;
            } else {
                return false;
            }
        }
        document.addEventListener("DOMContentLoaded", function () {
            const subjects = document.querySelectorAll(".subject-box");

            subjects.forEach(subject => {
                subject.addEventListener("click", function () {
                    let fileContainer = this.nextElementSibling;

                    if (fileContainer.style.display === "none" || fileContainer.style.display === "") {
                        fileContainer.style.display = "block"; // Show files
                    } else {
                        fileContainer.style.display = "none"; // Hide files
                    }
                });
            });
        });
    </script>
</body>

</html>