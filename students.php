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
// Safely start the session if it's not already active
if (session_status() === PHP_SESSION_NONE) {
    session_start();
}
 
// Check if the user is logged in, if not then redirect to login page
if (!isset($_SESSION["loggedin"]) || $_SESSION["loggedin"] !== true) {
    header("location: login.php");
    exit;
}

// ... rest of the page
 
// Check if the user is logged in, if not then redirect to login page
if (!isset($_SESSION["loggedin"]) || $_SESSION["loggedin"] !== true) {
    header("location: login.php");
    exit;
}

// Then, the rest of your page's code, like require_once 'includes/header.php';
?>
<?php require_once 'includes/header.php'; ?>
<?php require_once 'includes/db.php'; ?>

<div class="d-flex justify-content-between align-items-center mb-4">
    <h2>Manage Students</h2>
    <a href="student_form.php" class="btn btn-primary">Add New Student</a>
</div>

<div class="table-responsive">
    <table class="table table-striped table-bordered">
        <thead class="table-dark">
            <tr>
                <th>ID</th>
                <th>First Name</th>
                <th>Last Name</th>
                <th>Email</th>
                <th>Program</th>
                <th>Enrollment Date</th>
                <th>Actions</th>
            </tr>
        </thead>
        <tbody>
            <?php
            // Fetch students with their program names
            $sql = "SELECT s.*, p.program_name 
                    FROM Students s 
                    JOIN Programs p ON s.program_id = p.program_id 
                    ORDER BY s.student_id DESC";
            $result = $conn->query($sql);

            if ($result->num_rows > 0) {
                // Output data of each row
                while($row = $result->fetch_assoc()) {
                    echo "<tr>";
                    echo "<td>" . $row["student_id"] . "</td>";
                    echo "<td>" . htmlspecialchars($row["first_name"]) . "</td>";
                    echo "<td>" . htmlspecialchars($row["last_name"]) . "</td>";
                    echo "<td>" . htmlspecialchars($row["email"]) . "</td>";
                    echo "<td>" . htmlspecialchars($row["program_name"]) . "</td>";
                    echo "<td>" . $row["enrollment_date"] . "</td>";
                    echo "<td>
                            <a href='student_form.php?id=" . $row["student_id"] . "' class='btn btn-sm btn-warning me-2'>Edit</a>
                            <a href='actions/delete_student.php?id=" . $row["student_id"] . "' class='btn btn-sm btn-danger' onclick='return confirm(\"Are you sure you want to delete this student?\");'>Delete</a>
                          </td>";
                    echo "</tr>";
                }
            } else {
                echo "<tr><td colspan='7' class='text-center'>No students found</td></tr>";
            }
            $conn->close();
            ?>
        </tbody>
    </table>
</div>

<?php require_once 'includes/footer.php'; ?>