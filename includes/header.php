<?php
// Safely start the session if it's not already active
if (session_status() === PHP_SESSION_NONE) {
    session_start();
}
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>MPP LMS</title>
    <link rel="icon" type="image/png" href="images/favicon.png">
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/css/bootstrap.min.css" rel="stylesheet">
    <link rel="stylesheet" href="css/style.css">
</head>
<body>

<nav class="navbar navbar-expand-lg navbar-dark bg-dark">
  <div class="container-fluid">
    <a class="navbar-brand" href="index.php">
      <img src="images/logo.png" alt="MPP Logo" width="30" height="30" class="d-inline-block align-text-top">
      MPP LMS
    </a>
    <button class="navbar-toggler" type="button" data-bs-toggle="collapse" data-bs-target="#navbarNav">
      <span class="navbar-toggler-icon"></span>
    </button>
    <div class="collapse navbar-collapse" id="navbarNav">
      <ul class="navbar-nav me-auto mb-2 mb-lg-0">
        <li class="nav-item">
          <a class="nav-link" href="index.php">Dashboard</a>
        </li>

        <?php // --- ADMIN-ONLY LINKS ---
        if (isset($_SESSION['role']) && $_SESSION['role'] === 'admin'): ?>
            <li class="nav-item">
              <a class="nav-link" href="students.php">Students</a>
            </li>
            <li class="nav-item">
              <a class="nav-link" href="teachers.php">Teachers</a>
            </li>
            <li class="nav-item">
              <a class="nav-link" href="courses.php">Courses</a>
            </li>
        <?php endif; ?>

      </ul>
      <span class="navbar-text me-3">
        Welcome, <?php echo htmlspecialchars($_SESSION["email"]); ?>!
      </span>
      <a href="logout.php" class="btn btn-outline-danger">Logout</a>
    </div>
  </div>
</nav>

<div class="container mt-4">