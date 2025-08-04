<?php
require_once '../includes/db.php';

// Check if the form was submitted
if ($_SERVER["REQUEST_METHOD"] == "POST") {
    
    // Collect and sanitize form data
    $first_name = $_POST['first_name'];
    $last_name = $_POST['last_name'];
    $email = $_POST['email'];
    $date_of_birth = $_POST['date_of_birth'];
    $enrollment_date = $_POST['enrollment_date'];
    $program_id = $_POST['program_id'];
    $student_id = isset($_POST['student_id']) ? $_POST['student_id'] : null;

    if ($student_id) {
        // ----- UPDATE Operation -----
        $sql = "UPDATE Students SET first_name=?, last_name=?, email=?, date_of_birth=?, enrollment_date=?, program_id=? WHERE student_id=?";
        $stmt = $conn->prepare($sql);
        // Bind parameters: s=string, i=integer
        $stmt->bind_param("sssssii", $first_name, $last_name, $email, $date_of_birth, $enrollment_date, $program_id, $student_id);

    } else {
        // ----- INSERT Operation -----
        $sql = "INSERT INTO Students (first_name, last_name, email, date_of_birth, enrollment_date, program_id) VALUES (?, ?, ?, ?, ?, ?)";
        $stmt = $conn->prepare($sql);
        // Bind parameters
        $stmt->bind_param("sssssi", $first_name, $last_name, $email, $date_of_birth, $enrollment_date, $program_id);
    }

    // Execute the statement and check for success
    if ($stmt->execute()) {
        // Redirect to student list page on success
        header("Location: ../students.php");
        exit();
    } else {
        // Handle error
        echo "Error: " . $stmt->error;
    }

    // Close statement and connection
    $stmt->close();
    $conn->close();

} else {
    // If not a POST request, redirect to the students page
    header("Location: ../students.php");
    exit();
}
?>