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
    <h2>Manage Teachers</h2>
    <a href="teacher_form.php" class="btn btn-primary">Add New Teacher</a>
</div>

<div class="table-responsive">
    <table class="table table-striped table-bordered">
        <thead class="table-dark">
            <tr>
                <th>ID</th>
                <th>Name</th>
                <th>Email</th>
                <th>Phone</th>
                <th>Department</th>
                <th>Actions</th>
            </tr>
        </thead>
        <tbody>
            <?php
            $sql = "SELECT t.*, d.department_name 
                    FROM Teachers t 
                    JOIN Departments d ON t.department_id = d.department_id 
                    ORDER BY t.teacher_id DESC";
            $result = $conn->query($sql);
            if ($result->num_rows > 0) {
                while($row = $result->fetch_assoc()) {
                    echo "<tr>
                            <td>{$row['teacher_id']}</td>
                            <td>" . htmlspecialchars($row['first_name'] . ' ' . $row['last_name']) . "</td>
                            <td>" . htmlspecialchars($row['email']) . "</td>
                            <td>" . htmlspecialchars($row['phone']) . "</td>
                            <td>" . htmlspecialchars($row['department_name']) . "</td>
                            <td>
                                <a href='teacher_form.php?id={$row['teacher_id']}' class='btn btn-sm btn-warning me-2'>Edit</a>
                                <a href='actions/delete_teacher.php?id={$row['teacher_id']}' class='btn btn-sm btn-danger' onclick='return confirm(\"Are you sure?\");'>Delete</a>
                            </td>
                          </tr>";
                }
            } else {
                echo "<tr><td colspan='6' class='text-center'>No teachers found</td></tr>";
            }
            ?>
        </tbody>
    </table>
</div>

<?php 
$conn->close();
require_once 'includes/footer.php'; 
?>