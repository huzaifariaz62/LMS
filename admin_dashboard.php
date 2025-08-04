<?php
// --- This is the correct Admin Dashboard content ---

// 1. Start session and check security
if (session_status() === PHP_SESSION_NONE) {
    session_start();
}
// 2. Check if logged in and if the user is an 'admin' (This is the line that was likely causing the error)
if (!isset($_SESSION["loggedin"]) || $_SESSION["loggedin"] !== true || $_SESSION['role'] !== 'admin') {
    header("location: login.php");
    exit;
}

// 3. Include the header for the page layout
require_once 'includes/header.php'; 
?>

<!-- 4. Display the actual dashboard HTML content -->
<div class="p-5 mb-4 bg-light rounded-3">
    <div class="container-fluid py-5">
        <h1 class="display-5 fw-bold">Admin Dashboard</h1>
        <p class="col-md-8 fs-4">Welcome, <?php echo htmlspecialchars($_SESSION["email"]); ?>. Use the options below to manage the LMS.</p>
    </div>
</div>

<div class="row">
    <div class="col-md-4 mb-4">
        <div class="card text-center h-100">
            <div class="card-header">Students</div>
            <div class="card-body">
                <p class="card-text">Add, view, and manage student records.</p>
                <a href="students.php" class="btn btn-primary">Manage Students</a>
            </div>
        </div>
    </div>
    <div class="col-md-4 mb-4">
        <div class="card text-center h-100">
            <div class="card-header">Teachers</div>
            <div class="card-body">
                <p class="card-text">Manage faculty members and their assignments.</p>
                <a href="teachers.php" class="btn btn-primary">Manage Teachers</a>
            </div>
        </div>
    </div>
    <div class="col-md-4 mb-4">
        <div class="card text-center h-100">
            <div class="card-header">Courses</div>
            <div class="card-body">
                <p class="card-text">Manage academic courses and programs.</p>
                <a href="courses.php" class="btn btn-primary">Manage Courses</a>
            </div>
        </div>
    </div>
</div>

<?php require_once 'includes/footer.php'; ?>