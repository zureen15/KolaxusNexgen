<?php
session_start();
include '../database/config.php'; // Include the database connection
require '../vendor/autoload.php'; // Include Composer's autoloader

use PHPMailer\PHPMailer\PHPMailer;
use PHPMailer\PHPMailer\Exception;
use Dotenv\Dotenv;

$dotenv = Dotenv::createImmutable(__DIR__ . '/../');
$dotenv->load();

// header('Content-Type: application/json');

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $email = filter_var(trim($_POST['email']), FILTER_SANITIZE_EMAIL);

    // Validate email
    if (!filter_var($email, FILTER_VALIDATE_EMAIL)) {
        echo json_encode(['error' => 'Invalid email address.']);
        exit();
    }

    // Check if the email exists in the database
    $stmt = $conn->prepare("SELECT user_id FROM users WHERE email = ?");
    $stmt->bind_param("s", $email);
    $stmt->execute();
    $result = $stmt->get_result();

    if ($result->num_rows === 0) {
        echo json_encode(['error' => 'No account found with that email address.']);
        exit();
    }

    $user = $result->fetch_assoc();
    $user_id = $user['user_id'];

    // Generate a password reset token
    $token = bin2hex(random_bytes(32));
    $expiry = date('Y-m-d H:i:s', strtotime('+1 hour')); // Token expires in 1 hour

    // Insert the reset token into the password_resets table
    $stmt = $conn->prepare("INSERT INTO password_resets (user_id, reset_token, reset_token_expiry) VALUES (?, ?, ?)");
    $stmt->bind_param("iss", $user_id, $token, $expiry);
    $stmt->execute();

    // Send email with reset instructions using PHPMailer
    $resetLink = "http://localhost/Kolaxus/frontend/reset_password_form.php?token=" . $token;
    $subject = "Password Reset Request";
    $message = "To reset your password, please click the link below:\n" . $resetLink;

    $mail = new PHPMailer(true);

    try {
        //Server settings
        $mail->isSMTP();
        $mail->Host = $_ENV['SMTP_HOST'];                     // Set the SMTP server to send through
        $mail->SMTPAuth = true;                                   // Enable SMTP authentication
        $mail->Username = $_ENV['SMTP_USERNAME'];       // SMTP username
        $mail->Password = $_ENV['SMTP_PASSWORD'];                    // SMTP password
        $mail->SMTPSecure = $_ENV['SMTP_ENCRYPTION'];                                  // Enable implicit TLS encryption
        $mail->Port = $_ENV['SMTP_PORT'];

        //Recipients
        $mail->setFrom($_ENV['SMTP_FROM_EMAIL'], 'Kolaxus');
        $mail->addAddress($email);

        // Content
        $mail->isHTML(false);
        $mail->Subject = $subject;
        $mail->Body = $message;

        $mail->send();
        echo json_encode(['success' => 'A password reset link has been sent to your email.']);
    } catch (Exception $e) {
        echo json_encode(['error' => "Failed to send email. Please try again. Mailer Error: {$mail->ErrorInfo}"]);
    }

    exit();
}
?>