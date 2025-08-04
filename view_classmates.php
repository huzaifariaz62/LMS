<?php
// Security check for STUDENT role
if (session_status() === PHP_SESSION_NONE) { session_start(); }
if (!isset($_SESSION["loggedin"]) || $_SESSION['role'] !== 'student') { header("location: login.php"); exit; }

require_once 'includes/header.php';
require_once 'includes/db.php';

// Check if a course ID is provided
if (!isset($_GET['course_id'])) {
    echo "<div class='alert alert-danger'>No course specified.</div>";
    require_once 'includes/footer.php';
    exit;
}
$course_id = $_GET['course_id'];
$current_student_id = $_SESSION['reference_id'];

// Get course name for the title
$course_name_stmt = $conn->prepare("SELECT course_name FROM Courses WHERE course_id = ?");
$course_name_stmt->bind_param("i", $course_id);
$course_name_stmt->execute();
$course_name_result = $course_name_stmt->get_result();
$course_info = $course_name_result->fetch_assoc();
?>

<h2 class="mb-4">Classmates in <?php echo htmlspecialchars($course_info['course_name']); ?></h2>
<div class="list-group">
    <?php
    // SQL to find all students (their names only) enrolled in the same course
    $sql = "SELECT s.first_name, s.last_name 
            FROM Students s
            JOIN Enrollments e ON s.student_id = e.student_id
            WHERE e.course_id = ? AND s.student_id != ?";
    $stmt = $conn->prepare($sql);
    $stmt->bind_param("ii", $course_id, $current_student_id);
    $stmt->execute();
    $result = $stmt->get_result();

    if ($result->num_rows > 0) {
        while($row = $result->fetch_assoc()) {
            echo "<li class='list-group-item'>" . htmlspecialchars($row['first_name'] . ' ' . $row['last_name']) . "</li>";
        }
    } else {
        echo "<li class='list-group-item'>You are the only student enrolled in this course.</li>";
    }
    ?>
</div>
<a href="student_dashboard.php" class="btn btn-secondary mt-3">Back to Dashboard</a>

<?php require_once 'includes/footer.php'; ?>