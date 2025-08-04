<?php
// --- CORRECTED MANAGE_COURSE.PHP ---

// 1. Start session and perform security checks FIRST.
if (session_status() === PHP_SESSION_NONE) { session_start(); }
if (!isset($_SESSION["loggedin"]) || $_SESSION['role'] !== 'teacher') { header("location: login.php"); exit; }

// 2. GET THE COURSE ID FROM THE URL IMMEDIATELY. This is the main fix.
// Check if a course ID is provided in the URL and define the variable.
if (!isset($_GET['id']) || empty($_GET['id'])) {
    // If no ID, show an error and stop.
    require_once 'includes/header.php';
    echo "<div class='alert alert-danger'>Error: No course specified.</div>";
    require_once 'includes/footer.php';
    exit;
}
$course_id = $_GET['id']; // Now $course_id is defined for the whole page.

// 3. Include the rest of the required files.
require_once 'includes/header.php';
require_once 'includes/db.php';

// Get course name for the title (optional but good for UX)
$course_name_stmt = $conn->prepare("SELECT course_name FROM Courses WHERE course_id = ?");
$course_name_stmt->bind_param("i", $course_id);
$course_name_stmt->execute();
$course_name_result = $course_name_stmt->get_result();
$course_info = $course_name_result->fetch_assoc();
?>

<!-- 4. NOW we can display the HTML that uses $course_id -->
<h3 class="mb-4">Manage Course: <?php echo htmlspecialchars($course_info['course_name']); ?></h3>
<div class="mb-4">
    <a href="view_enrolled_students.php?course_id=<?php echo $course_id; ?>" class="btn btn-success">View Enrolled Students</a>
    <a href="add_students_to_course.php?course_id=<?php echo $course_id; ?>" class="btn btn-primary">Enroll New Students</a>
</div>


<h4 class="mb-3">Upload New Material</h4>
<!-- Display success or error messages from upload action -->
<?php if(isset($_GET['success'])) { echo "<div class='alert alert-success'>File uploaded successfully.</div>"; } ?>
<?php if(isset($_GET['error'])) { echo "<div class='alert alert-danger'>File upload failed. Please try again.</div>"; } ?>

<form action="actions/upload_material.php" method="post" enctype="multipart/form-data" class="mb-5 border p-3 rounded">
    <input type="hidden" name="course_id" value="<?php echo $course_id; ?>">
    <div class="mb-3">
        <label for="title" class="form-label">Material Title</label>
        <input type="text" class="form-control" name="title" required>
    </div>
    <div class="mb-3">
        <label for="material_file" class="form-label">Select File (PDF, DOCX, PPTX, JPG, PNG)</label>
        <input type="file" class="form-control" name="material_file" required>
    </div>
    <button type="submit" class="btn btn-primary">Upload Material</button>
</form>

<h4 class="mb-3">Uploaded Materials</h4>
<ul class="list-group">
    <?php
    $sql_materials = "SELECT title, file_name, file_path, upload_date FROM CourseMaterials WHERE course_id = ? ORDER BY upload_date DESC";
    $stmt_materials = $conn->prepare($sql_materials);
    $stmt_materials->bind_param("i", $course_id);
    $stmt_materials->execute();
    $result_materials = $stmt_materials->get_result();
    if ($result_materials->num_rows > 0) {
        while ($material = $result_materials->fetch_assoc()) {
            echo "<li class='list-group-item d-flex justify-content-between align-items-center'>
                    <div>
                        <a href='" . htmlspecialchars($material['file_path']) . "' download='" . htmlspecialchars($material['file_name']) . "'>" . htmlspecialchars($material['title']) . "</a>
                        <small class='text-muted d-block'>Uploaded on: " . date("d M Y, h:i A", strtotime($material['upload_date'])) . "</small>
                    </div>
                  </li>";
        }
    } else {
        echo "<li class='list-group-item'>No materials have been uploaded for this course yet.</li>";
    }
    ?>
</ul>

<?php 
$conn->close();
require_once 'includes/footer.php'; 
?>