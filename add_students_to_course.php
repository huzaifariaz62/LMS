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

// Check if a course ID is provided
if (!isset($_GET['course_id'])) {
    echo "<div class='alert alert-danger'>Error: No course specified.</div>";
    require_once 'includes/footer.php';
    exit;
}
$course_id = $_GET['course_id'];

// Get course name for the title
$course_name_stmt = $conn->prepare("SELECT course_name FROM Courses WHERE course_id = ?");
$course_name_stmt->bind_param("i", $course_id);
$course_name_stmt->execute();
$course_name_result = $course_name_stmt->get_result();
$course_info = $course_name_result->fetch_assoc();
?>

<h2 class="mb-4">Enroll New Students in: <?php echo htmlspecialchars($course_info['course_name']); ?></h2>

<!-- Display success or error messages from the enroll action -->
<?php if(isset($_GET['success'])) { echo "<div class='alert alert-success'>Student enrolled successfully!</div>"; } ?>
<?php if(isset($_GET['error'])) { echo "<div class='alert alert-danger'>Error: The student might already be enrolled.</div>"; } ?>


<div class="table-responsive">
    <table class="table table-striped table-bordered">
        <thead class="table-dark">
            <tr>
                <th>Student Name</th>
                <th>Email</th>
                <th>Action</th>
            </tr>
        </thead>
        <tbody>
            <?php
            // This complex query finds all students who are NOT already enrolled in THIS specific course
            $sql = "SELECT student_id, first_name, last_name, email 
                    FROM Students 
                    WHERE student_id NOT IN (
                        SELECT student_id FROM Enrollments WHERE course_id = ?
                    ) AND email LIKE '%@student.mpp.edu.pk'";
    
            $stmt = $conn->prepare($sql);
            $stmt->bind_param("i", $course_id);
            $stmt->execute();
            $result = $stmt->get_result();

            if ($result->num_rows > 0) {
                while($row = $result->fetch_assoc()) {
                    echo "<tr>
                            <td>" . htmlspecialchars($row['first_name'] . ' ' . $row['last_name']) . "</td>
                            <td>" . htmlspecialchars($row['email']) . "</td>
                            <td>
                                <form action='actions/enroll_student.php' method='POST' style='display:inline;'>
                                    <input type='hidden' name='student_id' value='" . $row['student_id'] . "'>
                                    <input type='hidden' name='course_id' value='" . $course_id . "'>
                                    <button type='submit' class='btn btn-sm btn-success'>Enroll</button>
                                </form>
                            </td>
                          </tr>";
                }
            } else {
                echo "<tr><td colspan='3' class='text-center'>All available students are already enrolled in this course.</td></tr>";
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