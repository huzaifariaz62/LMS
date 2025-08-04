<?php
require_once '../includes/db.php';

if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $teacher_id = $_POST['teacher_id'];
    $first_name = $_POST['first_name'];
    $last_name = $_POST['last_name'];
    $email = $_POST['email'];
    $phone = $_POST['phone'];
    $department_id = $_POST['department_id'];

    if (empty($teacher_id)) { // Add
        $sql = "INSERT INTO Teachers (first_name, last_name, email, phone, department_id) VALUES (?, ?, ?, ?, ?)";
        $stmt = $conn->prepare($sql);
        $stmt->bind_param("ssssi", $first_name, $last_name, $email, $phone, $department_id);
    } else { // Update
        $sql = "UPDATE Teachers SET first_name=?, last_name=?, email=?, phone=?, department_id=? WHERE teacher_id=?";
        $stmt = $conn->prepare($sql);
        $stmt->bind_param("ssssii", $first_name, $last_name, $email, $phone, $department_id, $teacher_id);
    }

    if ($stmt->execute()) {
        header("Location: ../teachers.php");
    } else {
        echo "Error: " . $stmt->error;
    }
    $stmt->close();
    $conn->close();
}
?>