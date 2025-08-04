<?php
// Security check for TEACHER role
if (session_status() === PHP_SESSION_NONE) { session_start(); }
if (!isset($_SESSION["loggedin"]) || $_SESSION['role'] !== 'teacher') { header("location: ../login.php"); exit; }

require_once '../includes/db.php';

// Check if the required data was sent via POST
if ($_SERVER["REQUEST_METHOD"] == "POST" && isset($_POST['course_id']) && isset($_POST['student_id'])) {

    $course_id = $_POST['course_id'];
    $student_id = $_POST['student_id'];
    $semester = "Fall 2024"; // You can make this dynamic later if needed
    $enrollment_date = date("Y-m-d"); // Today's date

    // Prepare the INSERT statement
    $sql = "INSERT INTO Enrollments (student_id, course_id, semester, enrollment_date) VALUES (?, ?, ?, ?)";
    
    $stmt = $conn->prepare($sql);
    // Check if the statement was prepared successfully
    if ($stmt === false) {
        // Handle error, maybe redirect with an error message
        header("location: ../add_students_to_course.php?course_id=" . $course_id . "&error=prepare");
        exit;
    }

    $stmt->bind_param("iiss", $student_id, $course_id, $semester, $enrollment_date);

    // Execute the statement and redirect back
    if ($stmt->execute()) {
        header("location: ../add_students_to_course.php?course_id=" . $course_id . "&success=1");
        exit;
    } else {
        // Handle potential errors, like a student already being enrolled (due to UNIQUE key)
        header("location: ../add_students_to_course.php?course_id=" . $course_id . "&error=exists");
        exit;
    }

} else {
    // If accessed directly or without required data, redirect to the dashboard
    header("location: ../teacher_dashboard.php");
    exit;
}
?>