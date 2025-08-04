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

$course = ['course_id' => '', 'course_code' => '', 'course_name' => '', 'credits' => '', 'program_id' => '', 'teacher_id' => ''];
$is_edit = false;

if (isset($_GET['id'])) {
    $is_edit = true;
    $stmt = $conn->prepare("SELECT * FROM Courses WHERE course_id = ?");
    $stmt->bind_param("i", $_GET['id']);
    $stmt->execute();
    $result = $stmt->get_result();
    if($result->num_rows > 0) {
        $course = $result->fetch_assoc();
    }
    $stmt->close();
}

$programs = $conn->query("SELECT * FROM Programs ORDER BY program_name");
$teachers = $conn->query("SELECT teacher_id, first_name, last_name FROM Teachers ORDER BY first_name");
?>

<h2 class="mb-4"><?php echo $is_edit ? 'Edit Course' : 'Add New Course'; ?></h2>
<form action="actions/process_course.php" method="POST">
    <input type="hidden" name="course_id" value="<?php echo $course['course_id']; ?>">

    <div class="row">
        <div class="col-md-4 mb-3">
            <label class="form-label">Course Code</label>
            <input type="text" class="form-control" name="course_code" value="<?php echo htmlspecialchars($course['course_code']); ?>" required>
        </div>
        <div class="col-md-8 mb-3">
            <label class="form-label">Course Name</label>
            <input type="text" class="form-control" name="course_name" value="<?php echo htmlspecialchars($course['course_name']); ?>" required>
        </div>
    </div>
    <div class="mb-3">
        <label class="form-label">Credits</label>
        <input type="number" class="form-control" name="credits" value="<?php echo htmlspecialchars($course['credits']); ?>" required>
    </div>
    <div class="mb-3">
        <label class="form-label">Program</label>
        <select class="form-select" name="program_id" required>
            <option value="">Select Program</option>
            <?php while($prog = $programs->fetch_assoc()): ?>
                <option value="<?php echo $prog['program_id']; ?>" <?php if($prog['program_id'] == $course['program_id']) echo 'selected'; ?>>
                    <?php echo htmlspecialchars($prog['program_name']); ?>
                </option>
            <?php endwhile; ?>
        </select>
    </div>
     <div class="mb-3">
        <label class="form-label">Assign Teacher (Optional)</label>
        <select class="form-select" name="teacher_id">
            <option value="">-- Unassigned --</option>
            <?php while($teach = $teachers->fetch_assoc()): ?>
                <option value="<?php echo $teach['teacher_id']; ?>" <?php if($teach['teacher_id'] == $course['teacher_id']) echo 'selected'; ?>>
                    <?php echo htmlspecialchars($teach['first_name'] . ' ' . $teach['last_name']); ?>
                </option>
            <?php endwhile; ?>
        </select>
    </div>
    <button type="submit" class="btn btn-success"><?php echo $is_edit ? 'Update Course' : 'Add Course'; ?></button>
    <a href="courses.php" class="btn btn-secondary">Cancel</a>
</form>

<?php 
$conn->close();
require_once 'includes/footer.php'; 
?>