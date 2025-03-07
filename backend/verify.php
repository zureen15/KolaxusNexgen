<?php

use PHPMailer\PHPMailer\PHPMailer;
use PHPMailer\PHPMailer\Exception;
use Dotenv\Dotenv;

require '../vendor/autoload.php';
require '../database/config.php'; // Include your database connection file

header("Cache-Control: no-store, no-cache, must-revalidate, max-age=0");
header("Cache-Control: post-check=0, pre-check=0", false);
header("Pragma: no-cache");

// Load environment variables
$dotenv = Dotenv::createImmutable(__DIR__ . '/../');
$dotenv->load();

if (isset($_GET['token'])) {
    $token = $_GET['token'];
    setcookie('token', $token, time() + 3600, '/login.php');

    // Prepare and execute the query to find the user with the given token
    $stmt = $conn->prepare("SELECT * FROM users WHERE verification_token = ? LIMIT 1");
    $stmt->bind_param("s", $token);
    $stmt->execute();
    $result = $stmt->get_result();

    if ($result->num_rows > 0) {
        $user = $result->fetch_assoc();
        $token_created_at = strtotime($user['token_created_at']);
        $current_time = time();

        // Check if the token is still valid (within 1 hour)
        if (($current_time - $token_created_at) <= 3600) {
            // Token is valid, update the user's verification status
            $sql_update = "UPDATE users SET is_verified = 1, verification_token = NULL, token_created_at = NULL WHERE verification_token = ?";
            $stmt_update = $conn->prepare($sql_update);
            $stmt_update->bind_param("s", $token);

            if ($stmt_update->execute()) {
                echo "Email verified successfully! You can now log in.";
            } else {
                echo "Error updating verification status: " . $conn->error;
            }
            $stmt_update->close();
        } else {
            // Token has expired, disable the token
            $sql_disable_token = "UPDATE users SET verification_token = NULL, token_created_at = NULL WHERE verification_token = ?";
            $stmt_disable_token = $conn->prepare($sql_disable_token);
            $stmt_disable_token->bind_param("s", $token);
            $stmt_disable_token->execute();
            $stmt_disable_token->close();

            echo "Verification token has expired!";
        }
    } else {
        // Token is invalid, disable the token
        $sql_disable_token = "UPDATE users SET verification_token = NULL WHERE verification_token = ?";
        $stmt_disable_token = $conn->prepare($sql_disable_token);
        $stmt_disable_token->bind_param("s", $token);
        $stmt_disable_token->execute();
        $stmt_disable_token->close();

        // Send an email to the admin
        $admin_email = $_ENV['SMTP_FROM_EMAIL'];
        $mail = new PHPMailer(true);
        try {
            $mail->isSMTP();
            $mail->Host = $_ENV['SMTP_HOST'];
            $mail->SMTPAuth = true;
            $mail->Username = $_ENV['SMTP_USERNAME'];
            $mail->Password = $_ENV['SMTP_PASSWORD'];
            $mail->SMTPSecure = $_ENV['SMTP_ENCRYPTION'];
            $mail->Port = $_ENV['SMTP_PORT'];

            $mail->setFrom($_ENV['SMTP_FROM_EMAIL'], 'Admin');
            $mail->addAddress($admin_email);

            $mail->isHTML(true);
            $mail->Subject = 'Invalid Verification Token Attempt';
            $mail->Body = "An invalid verification token attempt was made with token: $token";

            $mail->send();
        } catch (Exception $e) {
            echo "Admin notification email could not be sent. Mailer Error: {$mail->ErrorInfo}";
        }

        echo "Invalid verification token!";
    }
    $stmt->close();
} else {
    echo "No token provided!";
}

?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Account Activation</title>
    <!-- Bootstrap CSS -->
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
    <!-- Font Awesome for icons -->
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css">
    <link rel="stylesheet" href="../frontend/css/verify.css?v=1"/>
</head>
<body>
    <div class="card">
        <div class="card-body text-center pt-4">
            <?php if ($status === "success"): ?>
                <div class="icon-circle success-circle">
                    <i class="fas fa-check-circle success-icon"></i>
                </div>
                <h1 class="mt-4 mb-3 fw-bold">Account Activated</h1>
            <?php elseif ($status === "expired"): ?>
                <div class="icon-circle warning-circle">
                    <i class="fas fa-exclamation-triangle warning-icon"></i>
                </div>
                <h1 class="mt-4 mb-3 fw-bold">Token Expired</h1>
            <?php elseif ($status === "invalid"): ?>
                <div class="icon-circle danger-circle">
                    <i class="fas fa-times danger-icon"></i>
                </div>
                <h1 class="mt-4 mb-3 fw-bold">Invalid Token</h1>
            <?php else: ?>
                <div class="icon-circle info-circle">
                    <i class="fas fa-question info-icon"></i>
                </div>
                <h1 class="mt-4 mb-3 fw-bold">No Token Provided</h1>
            <?php endif; ?>
            
            <p class="text-muted">
                <?php echo htmlspecialchars($message); ?>
            </p>
            
            <?php if ($status === "success"): ?>
                <div class="highlight-box">
                    <div class="highlight-glow"></div>
                    <div class="highlight-content">
                        <p class="mb-0 small fw-medium">
                            You can now access your account. Please log in to continue.
                        </p>
                    </div>
                </div>
                
                <a href="../frontend/login_form.php" class="btn btn-primary btn-lg w-100 mb-3 btn-login">
                    Login
                </a>
            <?php elseif ($status === "expired"): ?>
                <a href="../frontend/login_form.php.php" class="btn btn-outline-primary btn-lg w-100 mb-3">
                    Your token has expired. Please contact mail@kolaxus.net for assistance.
                </a>
            <?php elseif ($status === "invalid"): ?>
                <a href="../frontend/login_form.php" class="btn btn-outline-secondary btn-lg w-100 mb-3">
                    Your token is invalid. Please contact mail@kolaxus.net for assistance.
                </a>
            <?php else: ?>
                <a href="../frontend/login_form.php" class="btn btn-outline-secondary btn-lg w-100 mb-3">
                    Back to Home
                </a>
            <?php endif; ?>
        </div>
    </div>

    <!-- Bootstrap JS (optional) -->
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>
</body>
</html>