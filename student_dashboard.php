<?php
// 1. Start session and check security
if (session_status() === PHP_SESSION_NONE) {
    session_start();
}
// 2. Check if logged in and if the user is a 'student'
if (!isset($_SESSION["loggedin"]) || $_SESSION["loggedin"] !== true || $_SESSION['role'] !== 'student') {
    header("location: login.php");
    exit;
}

// 3. Include required files
require_once 'includes/header.php';
require_once 'includes/db.php';

// Get the student's ID from the session
$student_id = $_SESSION['reference_id'];
?>
<h2 class="mb-4">Student Dashboard: My Enrolled Courses</h2>
<div class="list-group">
    <?php
    $sql = "SELECT c.course_id, c.course_name, c.course_code
            FROM Courses c
            JOIN Enrollments e ON c.course_id = e.course_id
            WHERE e.student_id = ?";
    $stmt = $conn->prepare($sql);
    $stmt->bind_param("i", $student_id);
    $stmt->execute();
    $result = $stmt->get_result();
    if ($result->num_rows > 0) {
        while($row = $result->fetch_assoc()) {
            // --- THE FIX IS ON THIS LINE ---
            // The main echo now uses single quotes to avoid conflict with the class="...".
            echo '<a href="view_course.php?id=' . $row['course_id'] . '" class="list-group-item list-group-item-action">
                    <strong>' . htmlspecialchars($row['course_code']) . '</strong> - ' . htmlspecialchars($row['course_name']) . '
                  </a>';
        }
    } else {
        echo "<p>You are not enrolled in any courses.</p>";
    }
    ?>
</div>
<?php require_once 'includes/footer.php'; ?>