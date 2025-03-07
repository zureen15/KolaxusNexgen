<?php

include '../database/config.php'; // Include the database connection
include_once '../backend/reset_password.php';

if (!isset($_GET['token'])) {
    $_SESSION['error'] = "Invalid or missing token.";
    header("Location: ../frontend/login_form.php");
    exit();
}

$token = $_GET['token'];
?>

<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Reset Password</title>
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css">
    <link rel="stylesheet" href="../frontend/css/login.css?v=1">
</head>

<body>
    <div class="container">
        <div class="form-section">
            <div class="login-container">
                <div class="login-header">
                    <h2>Reset Password</h2>
                    <p>Enter your new password below</p>
                </div>

                <div class="error" id="errorMessage" style="display: none; color: red;">
                    <?php echo $_SESSION['error'] ?? ''; ?></div>
                <div class="success" id="successMessage" style="display: none; color: green;">
                    <?php echo $_SESSION['success'] ?? ''; ?></div>

                <form id="resetPasswordForm" method="POST" onsubmit="handleReset(event)">
                    <input type="hidden" name="token" value="<?php echo htmlspecialchars($token); ?>">
                    <div class="form-group">
                        <label for="password">New Password</label>
                        <div class="input-group">
                            <i class="fas fa-lock icon-left" aria-label="Lock icon"></i>
                            <input type="password" id="password" name="password" required
                                placeholder="Enter your new password">
                            <i class="fas fa-eye password-toggle" id="passwordToggle"
                                aria-label="Toggle password visibility" style="cursor: pointer;"></i>
                        </div>
                    </div>

                    <div class="form-group">
                        <label for="confirm_password">Confirm Password</label>
                        <div class="input-group">
                            <i class="fas fa-lock icon-left" aria-label="Lock icon"></i>
                            <input type="password" id="confirm_password" name="confirm_password" required
                                placeholder="Confirm your new password">
                            <i class="fas fa-eye password-toggle" id="passwordToggle"
                                aria-label="Toggle password visibility" style="cursor: pointer;"></i>
                        </div>
                    </div>

                    <button type="submit" class="submit-btn" id="submitButton">
                        <span class="spinner" id="spinner" style="display: none;"></span>
                        <span id="buttonText">Reset Password</span>
                    </button>
                </form>
            </div>
        </div>
    </div>

    <div class="toast" id="toast"></div>
    <script src="../frontend/js/login.js?v=1"></script>
</body>

</html>