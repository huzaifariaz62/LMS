<?php
// --- This is the correct Teacher Dashboard content ---

// 1. Start session and check security
if (session_status() === PHP_SESSION_NONE) {
    session_start();
}
// 2. Check if logged in and if the user is a 'teacher'
if (!isset($_SESSION["loggedin"]) || $_SESSION["loggedin"] !== true || $_SESSION['role'] !== 'teacher') {
    header("location: login.php");
    exit;
}

// 3. Include required files
require_once 'includes/header.php';
require_once 'includes/db.php';

// Get the teacher's ID from the session
$teacher_id = $_SESSION['reference_id'];
?>

<!-- 4. Display the actual dashboard HTML content -->
<h2 class="mb-4">Teacher Dashboard: My Assigned Courses</h2>
<div class="list-group">
    <?php
    $sql = "SELECT course_id, course_name, course_code FROM Courses WHERE teacher_id = ?";
    $stmt = $conn->prepare($sql);
    $stmt->bind_param("i", $teacher_id);
    $stmt->execute();
    $result = $stmt->get_result();
    if ($result->num_rows > 0) {
        while($row = $result->fetch_assoc()) {
            // --- QUOTES ARE ALSO FIXED HERE FOR CONSISTENCY ---
            echo '<a href="manage_course.php?id=' . $row['course_id'] . '" class="list-group-item list-group-item-action">
                    <strong>' . htmlspecialchars($row['course_code']) . '</strong> - ' . htmlspecialchars($row['course_name']) . '
                  </a>';
        }
    } else {
        echo "<p class='list-group-item'>You have not been assigned to any courses.</p>";
    }
    ?>
</div>

<?php require_once 'includes/footer.php'; ?>