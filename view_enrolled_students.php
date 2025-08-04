<?php
// --- CORRECTED SECURITY BLOCK FOR TEACHER ROLE ---
if (session_status() === PHP_SESSION_NONE) {
    session_start();
}
// This checks if the user is logged in AND if their role is 'teacher'.
if (!isset($_SESSION["loggedin"]) || $_SESSION["loggedin"] !== true || $_SESSION['role'] !== 'teacher') {
    header("location: login.php");
    exit;
}

require_once 'includes/header.php';
require_once 'includes/db.php';

// Check if a course ID is provided in the URL
if (!isset($_GET['course_id'])) {
    echo "<div class='alert alert-danger'>Error: No course specified.</div>";
    require_once 'includes/footer.php';
    exit;
}
$course_id = $_GET['course_id'];

// Get the course name to display as a title
$course_name_stmt = $conn->prepare("SELECT course_name FROM Courses WHERE course_id = ?");
$course_name_stmt->bind_param("i", $course_id);
$course_name_stmt->execute();
$course_name_result = $course_name_stmt->get_result();
$course_info = $course_name_result->fetch_assoc();
?>

<h2 class="mb-4">Enrolled Students in: <?php echo htmlspecialchars($course_info['course_name']); ?></h2>
<div class="table-responsive">
    <table class="table table-striped table-bordered">
        <thead class="table-dark">
            <tr>
                <th>Student ID</th>
                <th>Full Name</th>
                <th>Email</th>
                <th>Enrollment Date</th>
            </tr>
        </thead>
        <tbody>
            <?php
            // SQL query to find all students enrolled in this specific course
            $sql = "SELECT s.student_id, s.first_name, s.last_name, s.email, e.enrollment_date 
                    FROM Students s
                    JOIN Enrollments e ON s.student_id = e.student_id
                    WHERE e.course_id = ?";
    
            $stmt = $conn->prepare($sql);
            $stmt->bind_param("i", $course_id);
            $stmt->execute();
            $result = $stmt->get_result();

            if ($result->num_rows > 0) {
                while($row = $result->fetch_assoc()) {
                    echo "<tr>
                            <td>" . $row['student_id'] . "</td>
                            <td>" . htmlspecialchars($row['first_name'] . ' ' . $row['last_name']) . "</td>
                            <td>" . htmlspecialchars($row['email']) . "</td>
                            <td>" . date("d M Y", strtotime($row['enrollment_date'])) . "</td>
                          </tr>";
                }
            } else {
                echo "<tr><td colspan='4' class='text-center'>No students are currently enrolled in this course.</td></tr>";
            }
            ?>
        </tbody>
    </table>
</div>
<a href="manage_course.php?id=<?php echo $course_id; ?>" class="btn btn-secondary mt-3">Back to Course Management</a>

<?php 
if(isset($stmt)) $stmt->close();
$conn->close();
require_once 'includes/footer.php'; 
?>