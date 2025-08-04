<?php
// Security check for TEACHER role
if (session_status() === PHP_SESSION_NONE) { session_start(); }
if (!isset($_SESSION["loggedin"]) || $_SESSION['role'] !== 'teacher') { header("location: ../login.php"); exit; }

require_once '../includes/db.php';

if ($_SERVER["REQUEST_METHOD"] == "POST") {

    // --- THIS IS THE FIX ---
    // Correctly get the course_id and title from the POST data
    $course_id = $_POST['course_id'];
    $title = $_POST['title'];

    // Check if file was uploaded without errors
    if (isset($_FILES["material_file"]) && $_FILES["material_file"]["error"] == 0) {
        $target_dir = "../uploads/";
        $original_file_name = basename($_FILES["material_file"]["name"]);
        // Create a unique name to prevent overwriting files
        $unique_file_name = uniqid() . '-' . preg_replace("/[^a-zA-Z0-9\-\._]/", "", $original_file_name);
        $target_file = $target_dir . $unique_file_name;
        
        // Move the uploaded file to the 'uploads' directory
        if (move_uploaded_file($_FILES["material_file"]["tmp_name"], $target_file)) {
            // File uploaded successfully, now insert its info into the database
            $sql = "INSERT INTO CourseMaterials (course_id, title, file_name, file_path) VALUES (?, ?, ?, ?)";
            $stmt = $conn->prepare($sql);
            // The path stored is relative to the project root, e.g., 'uploads/uniquefile.pdf'
            $db_path = 'uploads/' . $unique_file_name; 
            $stmt->bind_param("isss", $course_id, $title, $original_file_name, $db_path);
            
            if ($stmt->execute()) {
                header("location: ../manage_course.php?id=" . $course_id . "&success=1");
                exit;
            }
        }
    }
    // If anything fails, redirect back with an error
    header("location: ../manage_course.php?id=" . $course_id . "&error=1");
    exit;
}
?>