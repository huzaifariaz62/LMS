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
require_once '../includes/db.php';

// Check if ID is set
if (isset($_GET['id'])) {
    $student_id = $_GET['id'];

    // Prepare a delete statement to prevent SQL injection
    $stmt = $conn->prepare("DELETE FROM Students WHERE student_id = ?");
    $stmt->bind_param("i", $student_id);

    // Execute the statement
    if ($stmt->execute()) {
        // Redirect to the students list on success
        header("Location: ../students.php");
        exit();
    } else {
        echo "Error deleting record: " . $conn->error;
    }

    $stmt->close();
    $conn->close();
} else {
    // If no ID is provided, redirect
    header("Location: ../students.php");
    exit();
}
?>