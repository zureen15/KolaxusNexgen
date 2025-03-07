<?php
session_start(); // Start the session

// Add secure headers
header("X-Content-Type-Options: nosniff");
header("X-Frame-Options: DENY");
header("X-XSS-Protection: 1; mode=block");
header("Strict-Transport-Security: max-age=31536000; includeSubDomains; preload");

header("Cache-Control: no-store, no-cache, must-revalidate, max-age=0");
header("Cache-Control: post-check=0, pre-check=0", false);
header("Pragma: no-cache");


if (isset($_GET["challenge_id"]) && !empty(trim($_GET["challenge_id"]))) {
    require "../database/config.php";

    $sql = "SELECT * FROM challenges WHERE challenge_id = ?";

    if ($stmt = mysqli_prepare($conn, $sql)) {
        mysqli_stmt_bind_param($stmt, "i", $param_id);

        $param_id = filter_var(trim($_GET["challenge_id"]), FILTER_SANITIZE_NUMBER_INT);

        if (mysqli_stmt_execute($stmt)) {
            $result = mysqli_stmt_get_result($stmt);

            if (mysqli_num_rows($result) == 1) {
                $row = mysqli_fetch_array($result, MYSQLI_ASSOC);

                $program_name = htmlspecialchars($row["program_name"], ENT_QUOTES, 'UTF-8');
                $challenge_type = htmlspecialchars($row["challenge_type"], ENT_QUOTES, 'UTF-8');
                $company = htmlspecialchars($row["company_name"], ENT_QUOTES, 'UTF-8');
                $description = htmlspecialchars_decode($row["challenge_description"], ENT_QUOTES);
                $start_date = date("F j, Y (h:i A)", strtotime($row["start_date"])); // Format start date with AM/PM
                $end_date = date("F j, Y (h:i A)", strtotime($row["end_date"])); // Format end date with AM/PM
                $contact_person = htmlspecialchars($row["contact_person"], ENT_QUOTES, 'UTF-8');
                $contact_email = htmlspecialchars($row["contact_email"], ENT_QUOTES, 'UTF-8');
                $location = htmlspecialchars($row["location"], ENT_QUOTES, 'UTF-8');
                $prizes = htmlspecialchars($row["prizes"], ENT_QUOTES, 'UTF-8');
                $registration_deadline = date("F j, Y (h:i A)", strtotime($row["registration_deadline"])); // Format registration deadline with AM/PM
                $sponsors = htmlspecialchars($row["sponsors"], ENT_QUOTES, 'UTF-8');
                $judges = htmlspecialchars($row["judges"], ENT_QUOTES, 'UTF-8');
                $rules_guidelines = htmlspecialchars_decode($row["rules_guidelines"], ENT_QUOTES);
                $website = htmlspecialchars($row["website"], ENT_QUOTES, 'UTF-8');
                $image = htmlspecialchars($row["image"], ENT_QUOTES, 'UTF-8');
            } else {
                header("location: error_challenge.php");
                exit();
            }

        } else {
            error_log("Database query failed: " . mysqli_error($conn));
            die("An error occurred. Please try again later.");
        }
    }

    mysqli_stmt_close($stmt);
    mysqli_close($conn);
} else {
    header("location: error_challenge.php");
    exit();
}
?>