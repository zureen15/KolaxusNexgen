<?php
session_start();
include '../database/config.php'; // Include the database connection

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $token = $_POST['token'];
    $password = $_POST['password'];
    $confirm_password = $_POST['confirm_password'];

    // Validate passwords
    if ($password !== $confirm_password) {
        $_SESSION['error'] = "Passwords do not match.";
        header("Location: ../frontend/reset_password_form.php?token=" . urlencode($token));
        exit();
    }

    // Check if the token is valid and not expired
    $stmt = $conn->prepare("SELECT user_id FROM password_resets WHERE reset_token = ? AND reset_token_expiry > NOW()");
    $stmt->bind_param("s", $token);
    $stmt->execute();
    $result = $stmt->get_result();

    if ($result->num_rows === 0) {
        $_SESSION['error'] = "Invalid or expired token.";
        header("Location: ../frontend/reset_password_form.php?token=" . urlencode($token));
        exit();
    }

    $reset = $result->fetch_assoc();
    $user_id = $reset['user_id'];

    // Update the user's password
    $hashed_password = password_hash($password, PASSWORD_DEFAULT);
    $stmt = $conn->prepare("UPDATE users SET password = ? WHERE user_id = ?");
    $stmt->bind_param("si", $hashed_password, $user_id);
    $stmt->execute();

    // Delete the used reset token
    $stmt = $conn->prepare("DELETE FROM password_resets WHERE reset_token = ?");
    $stmt->bind_param("s", $token);
    $stmt->execute();

    $_SESSION['success'] = "Your password has been reset successfully.";
    header("Location: ../frontend/login_form.php");
    exit();
}
?>