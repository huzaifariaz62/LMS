<?php
require_once '../includes/db.php';

if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $course_id = $_POST['course_id'];
    $course_code = $_POST['course_code'];
    $course_name = $_POST['course_name'];
    $credits = $_POST['credits'];
    $program_id = $_POST['program_id'];
    $teacher_id = !empty($_POST['teacher_id']) ? $_POST['teacher_id'] : NULL; // Handle optional teacher

    if (empty($course_id)) { // Add
        $sql = "INSERT INTO Courses (course_code, course_name, credits, program_id, teacher_id) VALUES (?, ?, ?, ?, ?)";
        $stmt = $conn->prepare($sql);
        $stmt->bind_param("ssiii", $course_code, $course_name, $credits, $program_id, $teacher_id);
    } else { // Update
        $sql = "UPDATE Courses SET course_code=?, course_name=?, credits=?, program_id=?, teacher_id=? WHERE course_id=?";
        $stmt = $conn->prepare($sql);
        $stmt->bind_param("ssiiii", $course_code, $course_name, $credits, $program_id, $teacher_id, $course_id);
    }

    if ($stmt->execute()) {
        header("Location: ../courses.php");
    } else {
        echo "Error: " . $stmt->error;
    }
    $stmt->close();
    $conn->close();
}
?>