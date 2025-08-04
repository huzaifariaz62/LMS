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

require_once 'includes/header.php';
require_once 'includes/db.php';

$teacher = ['teacher_id' => '', 'first_name' => '', 'last_name' => '', 'email' => '', 'phone' => '', 'department_id' => ''];
$is_edit = false;

if (isset($_GET['id'])) {
    $is_edit = true;
    $stmt = $conn->prepare("SELECT * FROM Teachers WHERE teacher_id = ?");
    $stmt->bind_param("i", $_GET['id']);
    $stmt->execute();
    $result = $stmt->get_result();
    if($result->num_rows > 0) {
        $teacher = $result->fetch_assoc();
    }
    $stmt->close();
}

$departments = $conn->query("SELECT * FROM Departments ORDER BY department_name");
?>

<h2 class="mb-4"><?php echo $is_edit ? 'Edit Teacher' : 'Add New Teacher'; ?></h2>
<form action="actions/process_teacher.php" method="POST">
    <input type="hidden" name="teacher_id" value="<?php echo $teacher['teacher_id']; ?>">
    
    <div class="row">
        <div class="col-md-6 mb-3">
            <label for="first_name" class="form-label">First Name</label>
            <input type="text" class="form-control" name="first_name" value="<?php echo htmlspecialchars($teacher['first_name']); ?>" required>
        </div>
        <div class="col-md-6 mb-3">
            <label for="last_name" class="form-label">Last Name</label>
            <input type="text" class="form-control" name="last_name" value="<?php echo htmlspecialchars($teacher['last_name']); ?>" required>
        </div>
    </div>
    <div class="mb-3">
        <label for="email" class="form-label">Email</label>
        <input type="email" class="form-control" name="email" value="<?php echo htmlspecialchars($teacher['email']); ?>" required>
    </div>
    <div class="mb-3">
        <label for="phone" class="form-label">Phone</label>
        <input type="text" class="form-control" name="phone" value="<?php echo htmlspecialchars($teacher['phone']); ?>">
    </div>
    <div class="mb-3">
        <label for="department_id" class="form-label">Department</label>
        <select class="form-select" name="department_id" required>
            <option value="">Select Department</option>
            <?php while($dept = $departments->fetch_assoc()): ?>
                <option value="<?php echo $dept['department_id']; ?>" <?php if($dept['department_id'] == $teacher['department_id']) echo 'selected'; ?>>
                    <?php echo htmlspecialchars($dept['department_name']); ?>
                </option>
            <?php endwhile; ?>
        </select>
    </div>
    <button type="submit" class="btn btn-success"><?php echo $is_edit ? 'Update Teacher' : 'Add Teacher'; ?></button>
    <a href="teachers.php" class="btn btn-secondary">Cancel</a>
</form>

<?php 
$conn->close();
require_once 'includes/footer.php'; 
?>