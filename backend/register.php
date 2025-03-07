<?php

use PHPMailer\PHPMailer\PHPMailer;
use Dotenv\Dotenv;

require '../vendor/autoload.php';
require '../database/config.php'; // Include your database connection file

// Load environment variables
$dotenv = Dotenv::createImmutable(__DIR__ . '/../');
$dotenv->load();

session_start();
if (empty($_SESSION['csrf_token'])) {
    $_SESSION['csrf_token'] = bin2hex(random_bytes(32));
}

header("Cache-Control: no-store, no-cache, must-revalidate, max-age=0");
header("Cache-Control: post-check=0, pre-check=0", false);
header("Pragma: no-cache");

// Function to sanitize input data
function sanitizeInput($data, $conn)
{
    return $conn->real_escape_string(trim(htmlspecialchars($data)));
}

function fetchData($conn, $query)
{
    $data = [];
    $result = mysqli_query($conn, $query);
    if ($result) {
        while ($row = mysqli_fetch_assoc($result)) {
            $data[] = $row;
        }
    } else {
        echo "Error fetching data: " . htmlspecialchars(mysqli_error($conn));
    }
    return $data;
}

// function isValidStudentEmail($email)
// {
//     $disallowedDomains = ['gmail.com', 'yahoo.com'];
//     $emailDomain = substr(strrchr($email, "@"), 1);
//     return !in_array($emailDomain, $disallowedDomains);
// }


// Fetching data
$education = fetchData($conn, "SELECT edu_id, edu_name FROM edu_level");
$universities = fetchData($conn, "SELECT uni_id, uni_name FROM universities");
$courses = fetchData($conn, "SELECT course_id, course_name FROM course");
$countries = fetchData($conn, "SELECT country_id, country_name FROM country");

// Check if form is submitted via POST
if ($_SERVER["REQUEST_METHOD"] == "POST") {
    if (!isset($_POST['csrf_token']) || $_POST['csrf_token'] !== $_SESSION['csrf_token']) {
        echo json_encode(['status' => 'error', 'message' => 'Invalid CSRF token.']);
        exit;
    }

    // Get form data and sanitize it
    $first_name = sanitizeInput($_POST['first-name'], $conn);
    $last_name = sanitizeInput($_POST['last-name'], $conn);
    $nationality = sanitizeInput($_POST['nationality'], $conn);
    $username = sanitizeInput($_POST['username'], $conn);
    $phone = sanitizeInput($_POST['phone'], $conn);
    $country_id = isset($_POST['country']) ? (int) $_POST['country'] : null; // Optional
    $edu_id = isset($_POST['edu_level']) ? (int) $_POST['edu_level'] : null; // Optional
    $uni_id = isset($_POST['university']) && $_POST['university'] !== 'other' ? (int) $_POST['university'] : null; // Optional
    $course_id = isset($_POST['course']) && $_POST['course'] !== 'other' ? (int) $_POST['course'] : null; // Optional
    $custom_universities = isset($_POST['custom_universities']) && $_POST['custom_universities'] !== '' ? sanitizeInput($_POST['custom_universities'], $conn) : null; // Optional
    $custom_courses = isset($_POST['custom_courses']) && $_POST['custom_courses'] !== '' ? sanitizeInput($_POST['custom_courses'], $conn) : null; // Optional
    $email = sanitizeInput($_POST['email'], $conn);
    $password = password_hash($_POST['password'], PASSWORD_DEFAULT); // Hash the password
    $facebook = isset($_POST['facebook']) && $_POST['facebook'] !== '' ? sanitizeInput($_POST['facebook'], $conn) : null; // Optional
    $instagram = isset($_POST['instagram']) && $_POST['instagram'] !== '' ? sanitizeInput($_POST['instagram'], $conn) : null; // Optional
    $youtube = isset($_POST['youtube']) && $_POST['youtube'] !== '' ? sanitizeInput($_POST['youtube'], $conn) : null; // Optional
    $interests = isset($_POST['interest']) ? $_POST['interest'] : [];
    $other_interest = isset($_POST['other-interest']) ? sanitizeInput($_POST['other-interest'], $conn) : '';

    // If "Other" is selected, add it to the interests array
    if (in_array('other', $interests) && !empty($other_interest)) {
        $interests[] = $other_interest; // Add the other interest
    }

    // Remove the "other" option from the interests array if it exists
    $interests = array_filter($interests, function ($interest) {
        return $interest !== 'other';
    });

    // Validate that at least 2 interests are selected
    if (count($interests) < 2) {
        echo json_encode(['status' => 'error', 'message' => 'Please select at least 2 interests.']);
        exit; // Stop further execution
    }

    // Convert the interests array to a comma-separated string
    $interests_string = implode(',', $interests);

    // Set user_type
    $user_type = isset($_POST['user_type']) ? sanitizeInput($_POST['user_type'], $conn) : 'user'; // Default to 'user'

    // Validate email format
    if (!filter_var($email, FILTER_VALIDATE_EMAIL)) {
        echo json_encode(['status' => 'error', 'message' => 'Invalid email format.']);
        exit; // Stop further execution
    }

    // // Validate email domain
    // if (!isValidStudentEmail($email)) {
    //     echo json_encode(['status' => 'error', 'message' => 'Only student email addresses are allowed.']);
    //     exit; // Stop further execution
    // }

    //Handle image upload for student card
    if (isset($_FILES["image"]) && $_FILES["image"]["error"] == 0) {
        $allowed = ["jpg" => "image/jpg", "jpeg" => "image/jpeg", "png" => "image/png", "JPG" => "image/jpg", "JPEG" => "image/jpeg", "PNG" => "image/png"];
        $filename = $_FILES["image"]["name"];
        $filetype = $_FILES["image"]["type"];
        $filesize = $_FILES["image"]["size"];

        // Verify file extension
        $ext = pathinfo($filename, PATHINFO_EXTENSION);
        if (!array_key_exists($ext, $allowed)) {
            echo json_encode(['status' => 'error', 'message' => 'Please select a valid file format.']);
            exit;
        }

        // Verify file size - 5MB maximum
        if ($filesize > 10 * 1024 * 1024) {
            echo json_encode(['status' => 'error', 'message' => 'File size is larger than the allowed limit.']);
            exit;
        }

        // Verify MIME type of the file
        if (in_array($filetype, $allowed)) {
            // Generate a unique filename to avoid overwriting
            $new_filename = uniqid() . "_" . $filename;

            // Check whether file exists before uploading it
            if (file_exists("../frontend/student_upload/" . $new_filename)) {
                echo json_encode(['status' => 'error', 'message' => 'File already exists.']);
                exit;
            } else {
                if (move_uploaded_file($_FILES["image"]["tmp_name"], "../frontend/student_upload/" . $new_filename)) {
                    $student_card = $new_filename;
                } else {
                    echo json_encode(['status' => 'error', 'message' => 'There was a problem uploading your file. Please try again.']);
                    exit;
                }
            }
        } else {
            echo json_encode(['status' => 'error', 'message' => 'There was a problem with the file type. Please try again.']);
            exit;
        }
    } else {
        echo json_encode(['status' => 'error', 'message' => 'Please upload an image.']);
        exit;
    }


    // Generate a verification token
    $verification_token = bin2hex(random_bytes(16)); // Generate a random token
    $token_created_at = date('Y-m-d H:i:s'); // Current timestamp

    // Prepare SQL to insert data into the users table
    $stmt = $conn->prepare("INSERT INTO users (first_name, last_name, nationality, username, phone, country_id, edu_id, uni_id, custom_universities, course_id, custom_courses, email, image, password, facebook, instagram, youtube, interests, user_type, verification_token, token_created_at, is_verified) VALUES (?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, 0)");
    $stmt->bind_param("ssssiisssssssssssssss", $first_name, $last_name, $nationality, $username, $phone, $country_id, $edu_id, $uni_id, $custom_universities, $course_id, $custom_courses, $email, $student_card, $password, $facebook, $instagram, $youtube, $interests_string, $user_type, $verification_token, $token_created_at);

    if ($stmt->execute()) {
        // Send a verification email with the verification token
        $mail = new PHPMailer(true);
        $mail->isSMTP();                                            // Send using SMTP
        $mail->Host = $_ENV['SMTP_HOST'];                     // Set the SMTP server to send through
        $mail->SMTPAuth = true;                                   // Enable SMTP authentication
        $mail->Username = $_ENV['SMTP_USERNAME'];       // SMTP username
        $mail->Password = $_ENV['SMTP_PASSWORD'];                    // SMTP password
        $mail->SMTPSecure = $_ENV['SMTP_ENCRYPTION'];                                  // Enable implicit TLS encryption
        $mail->Port = $_ENV['SMTP_PORT'];

        $mail->setFrom($_ENV['SMTP_FROM_EMAIL'], $first_name);
        $mail->addAddress($email);

        // Send verification email
        $mail->isHTML(true);                                       // Set email format to HTML
        $mail->Subject = 'Email Verification for Student Registration';

        $email_template =
            "
            <html>
            <head>
                <title>Welcome to Nexgen Kolaxus</title>
            </head>
            <body style=\"margin: 0; padding: 20px; background-color: #f5f7fa;\">
                <div style=\"width: 100%; max-width: 600px; margin: auto; background-color: #ffffff; padding: 0; border-radius: 16px; overflow: hidden; box-shadow: 0 4px 6px rgba(0, 0, 0, 0.1); font-family: Arial, sans-serif;\">
                    <!-- Header with gradient -->
                    <div style=\"background: linear-gradient(135deg, #007bff, #0056b3); padding: 40px 20px; text-align: center;\">
                        <h2 style=\"color: #ffffff; margin: 0; font-size: 28px; font-weight: 600;\">Welcome to Nexgen Kolaxus!</h2>
                    </div>
            
                    <!-- Main Content -->
                    <div style=\"padding: 40px 30px; background-color: #ffffff;\">
                        <!-- Welcome Message -->
                        <p style=\"color: #444; font-size: 16px; line-height: 1.6; margin-bottom: 25px; text-align: center;\">
                            Thank you for joining us! Please verify your email to get started.
                        </p>
            
                        <!-- Verification Button -->
                        <div style=\"text-align: center; margin: 35px 0;\">
                            <a href=\"$verification_link\" 
                               style=\"display: inline-block; 
                                      padding: 14px 35px; 
                                      color: #ffffff; 
                                      background-color: #007bff; 
                                      border-radius: 8px; 
                                      text-decoration: none; 
                                      font-weight: bold; 
                                      font-size: 16px;
                                      box-shadow: 0 2px 4px rgba(0, 123, 255, 0.2);
                                      transition: background-color 0.3s ease;\">
                                Verify Email
                            </a>
                        </div>
            
                        <!-- Security Notice -->
                        <div style=\"margin: 30px 0; padding: 20px; background-color: #f8f9fa; border-left: 4px solid #007bff; border-radius: 4px;\">
                            <p style=\"color: #666; font-size: 14px; line-height: 1.5; margin: 0;\">
                                If you didn't request this, you can safely ignore this email.
                            </p>
                        </div>
            
                        <!-- Divider -->
                        <div style=\"border-top: 1px solid #eee; margin: 30px 0;\"></div>
            
                        <!-- Footer -->
                        <div style=\"text-align: left;\">
                            <p style=\"color: #555; font-size: 15px; line-height: 1.6; margin: 0;\">
                                Best Regards,
                                <br>
                                <strong style=\"color: #333; font-size: 16px;\">Nexgen Kolaxus</strong>
                            </p>
                        </div>
                    </div>
            
                    <!-- Bottom Banner -->
                    <div style=\"background-color: #f8f9fa; padding: 20px; text-align: center; border-top: 1px solid #eee;\">
                        <p style=\"color: #888; font-size: 13px; margin: 0;\">
                            This is an automated message. Please do not reply to this email.
                        </p>
                    </div>
                </div>
            
                <!-- Additional Information -->
                <div style=\"width: 100%; max-width: 600px; margin: 20px auto 0; text-align: center;\">
                    <p style=\"color: #666; font-size: 13px; margin: 0;\">
                        © 2024 Kolaxus. All rights reserved.
                    </p>
                </div>
            </body>
            </html>
            ";

        $mail->Body = $email_template;
        try {
            $mail->send();
            // Return a JSON response indicating success
            echo json_encode(['status' => 'success', 'message' => 'Registration successful! Please check your email for verification link in the inbox folder.']);
        } catch (Exception $e) {
            // Return a JSON response indicating failure
            echo json_encode(['status' => 'error', 'message' => 'Email could not be sent. Mailer Error: ' . $mail->ErrorInfo]);
        }
    } else {
        // Return a JSON response indicating SQL error
        echo json_encode(['status' => 'error', 'message' => 'Error: ' . $stmt->error]);
    }

}

?>