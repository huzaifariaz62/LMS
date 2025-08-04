<?php
// Security block here...
require_once 'includes/header.php';
require_once 'includes/db.php';
$course_id = $_GET['id'];
?>
<h3 class="mb-4">Course Materials</h3>
<ul class="list-group">
    <?php
    $sql = "SELECT title, file_name, file_path FROM CourseMaterials WHERE course_id = ?";
    $stmt = $conn->prepare($sql);
    $stmt->bind_param("i", $course_id);
    $stmt->execute();
    $result = $stmt->get_result();
    if ($result->num_rows > 0) {
        while($row = $result->fetch_assoc()) {
            // Note: We link directly to the file for download
            echo "<li class='list-group-item'>
                    <a href='" . htmlspecialchars($row['file_path']) . "' download>" . htmlspecialchars($row['title']) . "</a>
                    <span class='text-muted small'> (" . htmlspecialchars($row['file_name']) . ")</span>
                  </li>";
        }
    } else {
        echo "<li class='list-group-item'>No materials have been uploaded for this course yet.</li>";
    }
    ?>
    
</ul>
<div class="mt-4">
    <a href="view_classmates.php?course_id=<?php echo $course_id; ?>" class="btn btn-info">View Classmates</a>
    <a href="student_dashboard.php" class="btn btn-secondary">Back to Dashboard</a>
</div>
<?php require_once 'includes/footer.php'; ?>