<?php
// --- Final Correct Version of process_login.php ---

// Start session at the very beginning
if (session_status() === PHP_SESSION_NONE) {
    session_start();
}

require_once '../includes/db.php';

// Check if form was submitted
if ($_SERVER["REQUEST_METHOD"] == "POST") {

    // Make sure email and password were sent
    if (empty(trim($_POST["email"])) || empty(trim($_POST["password"]))) {
        header("location: ../login.php?error=empty");
        exit;
    }
    
    $email = trim($_POST['email']);
    $password = trim($_POST['password']);

    // Prepare a select statement to find the user by email
    $sql = "SELECT id, email, password, role, reference_id FROM Users WHERE email = ?";
    
    if ($stmt = $conn->prepare($sql)) {
        $stmt->bind_param("s", $email);
        
        if ($stmt->execute()) {
            $stmt->store_result();
            
            // Check if a user with that email exists
            if ($stmt->num_rows == 1) {
                $stmt->bind_result($id, $db_email, $hashed_password, $role, $reference_id);
                if ($stmt->fetch()) {
                    
                    // Verify the password
                    if (password_verify($password, $hashed_password)) {
                        
                        // Password is correct, regenerate session to prevent fixation
                        session_regenerate_id();
                        
                        // Store data in session variables
                        $_SESSION["loggedin"] = true;
                        $_SESSION["user_id"] = $id;
                        $_SESSION["email"] = $db_email;
                        $_SESSION["role"] = $role;
                        $_SESSION["reference_id"] = $reference_id;
                        
                        // Redirect user to the main index page, which will route them
                        header("location: ../index.php");
                        exit;
                    } else {
                        // Password is not valid
                        header("location: ../login.php?error=invalid");
                        exit;
                    }
                }
            } else {
                // Email doesn't exist
                header("location: ../login.php?error=invalid");
                exit;
            }
        } else {
            echo "Oops! Something went wrong. Please try again later.";
        }
        $stmt->close();
    }
    $conn->close();
}
?>