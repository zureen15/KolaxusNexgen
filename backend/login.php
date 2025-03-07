<?php
session_start(); // Start the session
require '../database/config.php'; // Include your database connection file
// require_once '../router.php';

// Generate CSRF token
if (empty($_SESSION['csrf_token'])) {
    $_SESSION['csrf_token'] = bin2hex(random_bytes(32));
}

header("Cache-Control: no-store, no-cache, must-revalidate, max-age=0");
header("Cache-Control: post-check=0, pre-check=0", false);
header("Pragma: no-cache");

// Function to limit login attempts
function limit_login_attempts($email, $conn)
{
    $stmt = $conn->prepare("SELECT attempts, last_attempt FROM login_attempts WHERE email = ?");
    $stmt->bind_param("s", $email);
    $stmt->execute();
    $stmt->store_result();
    $attempts = 0;
    $last_attempt = '';
    $stmt->bind_result($attempts, $last_attempt);
    $stmt->fetch();

    if ($stmt->num_rows > 0) {
        if ($attempts >= 5 && (time() - strtotime($last_attempt)) < 900) {
            return false;
        } elseif ((time() - strtotime($last_attempt)) >= 900) {
            $stmt = $conn->prepare("UPDATE login_attempts SET attempts = 1, last_attempt = NOW() WHERE email = ?");
            $stmt->bind_param("s", $email);
            $stmt->execute();
        } else {
            $stmt = $conn->prepare("UPDATE login_attempts SET attempts = attempts + 1, last_attempt = NOW() WHERE email = ?");
            $stmt->bind_param("s", $email);
            $stmt->execute();
        }
    } else {
        $stmt = $conn->prepare("INSERT INTO login_attempts (email, attempts, last_attempt) VALUES (?, 1, NOW())");
        $stmt->bind_param("s", $email);
        $stmt->execute();
    }
    return true;
}

// Check if the form is submitted
if ($_SERVER["REQUEST_METHOD"] == "POST") {
    // Check CSRF token
    if (!isset($_POST['csrf_token']) || $_POST['csrf_token'] !== $_SESSION['csrf_token']) {
        echo json_encode(['error' => "Invalid CSRF token."]);
        exit();
    }

    // Get the form data
    $email = filter_var($_POST['email'], FILTER_SANITIZE_EMAIL);
    $password = $_POST['password'];

    // Check login attempts
    if (!limit_login_attempts($email, $conn)) {
        $_SESSION['error'] = "Too many login attempts. Please try again after 15 minutes.";
        echo json_encode(['redirect' => '../frontend/login_form.php']);
        exit();
    }

    // Prepare SQL query to fetch user details
    $stmt = $conn->prepare("SELECT user_id, password, is_verified, user_type, image FROM users WHERE email = ?");
    $stmt->bind_param("s", $email);
    $stmt->execute();
    $stmt->store_result();

    // Check if the user exists
    if ($stmt->num_rows > 0) {
        $stmt->bind_result($user_id, $hashed_password, $is_verified, $user_type, $user_image);
        $stmt->fetch();

        // Verify the password
        if (password_verify($password, $hashed_password)) {
            // Check if the account is verified
            if ($is_verified) {
                // Password is correct and user is verified, set session variables
                session_regenerate_id(true); // Regenerate session ID to prevent session fixation
                $_SESSION['user_id'] = $user_id; // Store user ID in session
                $_SESSION['email'] = $email; // Store email in session
                $_SESSION['user_type'] = $user_type; // Store user type in session
                $_SESSION['image'] = $user_image; // Store user image in session

                // Reset login attempts on successful login
                $stmt = $conn->prepare("DELETE FROM login_attempts WHERE email = ?");
                $stmt->bind_param("s", $email);
                $stmt->execute();

                // Redirect based on user type
                if ($user_type === 'admin') {
                    echo json_encode(['redirect' => '../Admin/admin_dashboard.php']);
                } else {
                    echo json_encode(['redirect' => '../User/dashboard.php']);
                }
                exit();
            } else {
                echo json_encode(['error' => "Your account is not verified yet. Please check your email for the verification link."]);
                exit();
            }
        } else {
            // Increment login attempts on incorrect password
            $stmt = $conn->prepare("UPDATE login_attempts SET attempts = attempts + 1, last_attempt = NOW() WHERE email = ?");
            $stmt->bind_param("s", $email);
            $stmt->execute();

            echo json_encode(['error' => "Invalid email or password."]);
            exit();
        }
    } else {
        // Increment login attempts on incorrect email
        $stmt = $conn->prepare("UPDATE login_attempts SET attempts = attempts + 1, last_attempt = NOW() WHERE email = ?");
        $stmt->bind_param("s", $email);
        $stmt->execute();

        echo json_encode(['error' => "Invalid email or password."]);
        exit();
    }
}
?>