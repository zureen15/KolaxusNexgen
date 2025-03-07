<?php
session_start(); // Start the session

// Regenerate session ID to prevent session fixation
session_regenerate_id(true);

// Set session timeout
if (isset($_SESSION['LAST_ACTIVITY']) && (time() - $_SESSION['LAST_ACTIVITY'] > 1800)) {
    session_unset();     // Unset $_SESSION variable for the run-time 
    session_destroy();   // Destroy session data in storage
}
$_SESSION['LAST_ACTIVITY'] = time(); // Update last activity time stamp

// Check if user is logged in
if (!isset($_SESSION['user_id'])) {
    header("Location: ../frontend/login_form.php");
    exit();
}

// Add secure headers
header("X-Content-Type-Options: nosniff");
header("X-Frame-Options: DENY");
header("X-XSS-Protection: 1; mode=block");
header("Strict-Transport-Security: max-age=31536000; includeSubDomains; preload");
header("Cache-Control: no-store, no-cache, must-revalidate, max-age=0");
header("Cache-Control: post-check=0, pre-check=0", false);
header("Pragma: no-cache");

// Include database configuration
require '../database/config.php';

// Function to fetch user data
function fetch_user_data($conn, $user_id)
{
    $stmt = $conn->prepare("SELECT username FROM users WHERE user_id = ?");
    $stmt->bind_param("i", $user_id);
    $stmt->execute();
    $result = $stmt->get_result();
    return $result->fetch_assoc();
}
// Function to fetch hackathon data
function fetch_hackathon_data($conn)
{
    $query = "SELECT hackathon_id, hackathon_name, prizes, end_date, images, hackathon_type FROM hackathon_program";
    $stmt = $conn->prepare($query);
    $stmt->execute();
    $result = $stmt->get_result();
    if (!$result) {
        error_log("Hackathon query failed: " . $stmt->error);
        return false;
    }
    return $result;
}

// Function to fetch joined hackathon data
function fetch_joined_hackathon_data($conn, $user_id)
{
    $query = "SELECT h.hackathon_id, h.hackathon_name, h.prizes, h.end_date, h.images, h.hackathon_type 
              FROM hackathon_program h
              JOIN join_hackathon j ON h.hackathon_id = j.hackathon_id
              WHERE j.user_id = ?";
    $stmt = $conn->prepare($query);
    $stmt->bind_param("i", $user_id);
    $stmt->execute();
    $result = $stmt->get_result();
    if (!$result) {
        error_log("Joined hackathon query failed: " . $stmt->error);
        return false;
    }
    return $result;
}

function fetch_joined_hackathon_count($conn, $user_id)
{
    $sql = "SELECT COUNT(*) as count FROM join_hackathon WHERE user_id = ?";
    if ($stmt = mysqli_prepare($conn, $sql)) {
        mysqli_stmt_bind_param($stmt, "i", $user_id);
        if (mysqli_stmt_execute($stmt)) {
            $result = mysqli_stmt_get_result($stmt);
            if ($row = mysqli_fetch_assoc($result)) {
                return $row['count'];
            }
        }
        mysqli_stmt_close($stmt);
    }
    return 0;
}

// Fetch user data
$user_id = filter_var($_SESSION['user_id'], FILTER_SANITIZE_NUMBER_INT);
$user = fetch_user_data($conn, $user_id);
$user_name = htmlspecialchars($user['username'], ENT_QUOTES, 'UTF-8');
$hackathon_result = fetch_hackathon_data($conn);
// Fetch joined hackathon data
$join_hackathon_result = fetch_joined_hackathon_data($conn, $user_id);

$joined_hackathon_count = fetch_joined_hackathon_count($conn, $user_id);
?>