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
?><?php
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
<?php
require_once 'includes/header.php';
require_once 'includes/db.php';

// Initialize variables
$student_id = null;
$first_name = '';
$last_name = '';
$email = '';
$date_of_birth = '';
$enrollment_date = '';
$program_id = '';
$is_edit = false;

// Check if an ID is passed for editing
if (isset($_GET['id'])) {
    $is_edit = true;
    $student_id = $_GET['id'];

    // Fetch student data from the database
    $stmt = $conn->prepare("SELECT * FROM Students WHERE student_id = ?");
    $stmt->bind_param("i", $student_id);
    $stmt->execute();
    $result = $stmt->get_result();
    if ($result->num_rows > 0) {
        $student = $result->fetch_assoc();
        $first_name = $student['first_name'];
        $last_name = $student['last_name'];
        $email = $student['email'];
        $date_of_birth = $student['date_of_birth'];
        $enrollment_date = $student['enrollment_date'];
        $program_id = $student['program_id'];
    } else {
        echo "<div class='alert alert-danger'>Student not found.</div>";
        exit;
    }
    $stmt->close();
}

// Fetch programs for the dropdown
$programs_result = $conn->query("SELECT program_id, program_name FROM Programs ORDER BY program_name");
?>

<h2 class="mb-4"><?php echo $is_edit ? 'Edit Student' : 'Add New Student'; ?></h2>

<form action="actions/process_student.php" method="POST">
    <?php if ($is_edit): ?>
        <input type="hidden" name="student_id" value="<?php echo $student_id; ?>">
    <?php endif; ?>

    <div class="row">
        <div class="col-md-6 mb-3">
            <label for="first_name" class="form-label">First Name</label>
            <input type="text" class="form-control" id="first_name" name="first_name" value="<?php echo htmlspecialchars($first_name); ?>" required>
        </div>
        <div class="col-md-6 mb-3">
            <label for="last_name" class="form-label">Last Name</label>
            <input type="text" class="form-control" id="last_name" name="last_name" value="<?php echo htmlspecialchars($last_name); ?>" required>
        </div>
    </div>

    <div class="mb-3">
        <label for="email" class="form-label">Email</label>
        <input type="email" class="form-control" id="email" name="email" value="<?php echo htmlspecialchars($email); ?>" required>
    </div>

    <div class="row">
        <div class="col-md-6 mb-3">
            <label for="date_of_birth" class="form-label">Date of Birth</label>
            <input type="date" class="form-control" id="date_of_birth" name="date_of_birth" value="<?php echo $date_of_birth; ?>">
        </div>
        <div class="col-md-6 mb-3">
            <label for="enrollment_date" class="form-label">Enrollment Date</label>
            <input type="date" class="form-control" id="enrollment_date" name="enrollment_date" value="<?php echo $enrollment_date; ?>" required>
        </div>
    </div>

    <div class="mb-3">
        <label for="program_id" class="form-label">Program</label>
        <select class="form-select" id="program_id" name="program_id" required>
            <option value="">Select a Program</option>
            <?php
            if ($programs_result->num_rows > 0) {
                while($program = $programs_result->fetch_assoc()) {
                    $selected = ($program['program_id'] == $program_id) ? 'selected' : '';
                    echo "<option value='" . $program['program_id'] . "' " . $selected . ">" . htmlspecialchars($program['program_name']) . "</option>";
                }
            }
            ?>
        </select>
    </div>

    <button type="submit" class="btn btn-success"><?php echo $is_edit ? 'Update Student' : 'Add Student'; ?></button>
    <a href="students.php" class="btn btn-secondary">Cancel</a>
</form>

<?php 
$conn->close();
require_once 'includes/footer.php'; 
?>