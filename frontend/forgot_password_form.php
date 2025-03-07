<?php

require_once '../backend/forgot_password.php'; // Include the forgot_password.php file
?>

<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Forgot Password</title>
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css">
    <link rel="stylesheet" href="../frontend/css/login.css?v=1">
</head>

<body>
    <div class="container">
        <div class="form-section">
            <div class="login-container">
                <div class="login-header">
                    <h2>Forgot Password</h2>
                    <p>Enter your email to reset your password</p>
                </div>

                <div class="error" id="forgotErrorMessage" style="display: none; color: red;"></div>
                <div class="success" id="forgotSuccessMessage" style="display: none; color: green;"></div>

                <form id="forgotForm" action="forgot_password_form.php" method="POST" onsubmit="handleForgot(event)">
                    <div class="form-group">
                        <label for="forgotEmail">Email address</label>
                        <div class="input-group">
                            <i class="fas fa-user icon-left" aria-label="User icon"></i>
                            <input type="email" id="forgotEmail" name="email" required placeholder="Enter your email">
                        </div>
                    </div>

                    <button type="submit" class="submit-btn" id="forgotSubmitButton">
                        <span class="spinner" id="forgotSpinner" style="display: none;"></span>
                        <span id="forgotButtonText">Send Reset Link</span>
                    </button>
                </form>
            </div>
        </div>
    </div>

    <div class="toast" id="toast"></div>
    <script src="../frontend/js/login.js?v=1"></script>
</body>

</html>