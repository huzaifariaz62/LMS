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
?>
<?php
require_once '../includes/db.php';
if (isset($_GET['id'])) {
    $stmt = $conn->prepare("DELETE FROM Courses WHERE course_id = ?");
    $stmt->bind_param("i", $_GET['id']);
    if ($stmt->execute()) {
        header("Location: ../courses.php");
    } else {
        echo "Error deleting record: " . $conn->error;
    }
    $stmt->close();
    $conn->close();
}
?>
