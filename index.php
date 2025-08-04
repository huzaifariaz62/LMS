<?php
// --- This is the complete and correct code for the router ---

// Safely start the session to read the user's role
if (session_status() === PHP_SESSION_NONE) {
    session_start();
}
 
// First, check if the user is logged in at all.
// If not, they have no business here. Send them to the login page.
if (!isset($_SESSION["loggedin"]) || $_SESSION["loggedin"] !== true || !isset($_SESSION["role"])) {
    header("location: login.php");
    exit;
}

// If they are logged in, get their role from the session.
$role = $_SESSION['role'];

// Now, use the switch statement to redirect them to the correct dashboard.
switch ($role) {
    case 'admin':
        header('location: admin_dashboard.php');
        break;
    case 'teacher':
        header('location: teacher_dashboard.php');
        break;
    case 'student':
        header('location: student_dashboard.php');
        break;
    default:
        // If they have an unknown role, it's a security issue. Log them out.
        header('location: logout.php');
        break;
}

// The exit() is crucial to stop the script after the redirect header is sent.
exit;
?>