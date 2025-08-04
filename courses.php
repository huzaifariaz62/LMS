<?php
// --- ADMIN-ONLY SECURITY BLOCK ---
if (session_status() === PHP_SESSION_NONE) {
    session_start();
}
// Block access if user is not logged in OR if they are not an admin.
if (!isset($_SESSION["loggedin"]) || $_SESSION["loggedin"] !== true || $_SESSION['role'] !== 'admin') {
    header("location: login.php");
    exit;
}
// The rest of the page's code follows...
?>
<?php
// --- THIS IS THE ONLY PHP BLOCK YOU NEED AT THE TOP ---

// 1. Safely start the session if it's not already active.
if (session_status() === PHP_SESSION_NONE) {
    session_start();
}
 
// 2. Check if the user is logged in. If not, redirect to the login page.
if (!isset($_SESSION["loggedin"]) || $_SESSION["loggedin"] !== true) {
    header("location: login.php");
    exit;
}

// 3. Once security is confirmed, include the required files.
require_once 'includes/header.php';
require_once 'includes/db.php';
?>

<div class="d-flex justify-content-between align-items-center mb-4">
    <h2>Manage Courses</h2>
    <a href="course_form.php" class="btn btn-primary">Add New Course</a>
</div>

<div class="table-responsive">
    <table class="table table-striped table-bordered">
        <thead class="table-dark">
            <tr>
                <th>Code</th>
                <th>Name</th>
                <th>Credits</th>
                <th>Program</th>
                <th>Teacher</th>
                <th>Actions</th>
            </tr>
        </thead>
        <tbody>
            <?php
            $sql = "SELECT c.*, p.program_name, CONCAT(t.first_name, ' ', t.last_name) AS teacher_name
                    FROM Courses c
                    JOIN Programs p ON c.program_id = p.program_id
                    LEFT JOIN Teachers t ON c.teacher_id = t.teacher_id
                    ORDER BY c.course_code ASC";
            $result = $conn->query($sql);
            if ($result->num_rows > 0) {
                while($row = $result->fetch_assoc()) {
                    echo "<tr>
                            <td>" . htmlspecialchars($row['course_code']) . "</td>
                            <td>" . htmlspecialchars($row['course_name']) . "</td>
                            <td>{$row['credits']}</td>
                            <td>" . htmlspecialchars($row['program_name']) . "</td>
                            <td>" . htmlspecialchars($row['teacher_name'] ?? 'N/A') . "</td>
                            <td>
                                <a href='course_form.php?id={$row['course_id']}' class='btn btn-sm btn-warning me-2'>Edit</a>
                                <a href='actions/delete_course.php?id={$row['course_id']}' class='btn btn-sm btn-danger' onclick='return confirm(\"Are you sure?\");'>Delete</a>
                            </td>
                          </tr>";
                }
            } else {
                echo "<tr><td colspan='6' class='text-center'>No courses found</td></tr>";
            }
            ?>
        </tbody>
    </table>
</div>

<?php 
$conn->close();
require_once 'includes/footer.php'; 
?>