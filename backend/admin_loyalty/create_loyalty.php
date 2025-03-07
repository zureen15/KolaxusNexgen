<?php
// Include config file
require_once "../database/config.php";

header("Cache-Control: no-store, no-cache, must-revalidate, max-age=0");
header("Cache-Control: post-check=0, pre-check=0", false);
header("Pragma: no-cache");

// Define variables and initialize with empty values
$program_name = $loyalty_type = $voucher_code = $description = $start_date = $expiry_date = $image = $max_claims = "";
$program_name_err = $loyalty_type_err = $voucher_err = $description_err = $start_err = $expiry_err = $image_err = $max_claims_err = "";

// Processing form data when form is submitted
if ($_SERVER["REQUEST_METHOD"] == "POST") {
    // Validate loyalty program name
    $input_name = trim($_POST["program_name"]);
    if (empty($input_name)) {
        $program_name_err = "Please enter a voucher or discount name.";
    } else {
        $program_name = $input_name;
    }

    // Validate loyalty type
    $input_type = trim($_POST["loyalty_type"]);
    if (empty($input_type)) {
        $loyalty_type_err = "Please enter a voucher or discount type.";
    } else {
        $loyalty_type = $input_type;
    }

    // Validate voucher code
    $input_voucher = trim($_POST["voucher_code"]);
    if (empty($input_voucher)) {
        $voucher_err = "Please enter the voucher code.";
    } else {
        $voucher_code = $input_voucher;
    }

    // Validate description
    $input_description = trim($_POST["challenge_description"]);
    if (empty($input_description)) {
        $description_err = "Please enter the description of voucher.";
    } else {
        $description = $input_description;
    }

    // Validate start date of loyalty
    $input_start = trim($_POST["start_date"]);
    if (empty($input_start)) {
        $start_err = "Please select the start date of loyalty.";
    } else {
        $start_date = $input_start;
    }

    // Validate expiry date of loyalty
    $input_expiry = trim($_POST["expiry_date"]);
    if (empty($input_expiry)) {
        $expiry_err = "Please select the expiry date of loyalty.";
    } else {
        $expiry_date = $input_expiry;
    }

    // Validate image upload
    if (isset($_FILES["image"]) && $_FILES["image"]["error"] == 0) {
        $allowed = ["jpg" => "image/jpg", "jpeg" => "image/jpeg", "png" => "image/png"];
        $filename = $_FILES["image"]["name"];
        $filetype = $_FILES["image"]["type"];
        $filesize = $_FILES["image"]["size"];

        // Verify file extension
        $ext = pathinfo($filename, PATHINFO_EXTENSION);
        if (!array_key_exists($ext, $allowed)) {
            $image_err = "Please select a valid file format.";
        }

        // Verify file size - 5MB maximum
        if ($filesize > 5 * 1024 * 1024) {
            $image_err = "File size is larger than the allowed limit.";
        }

        // Verify MIME type of the file
        if (in_array($filetype, $allowed)) {
            // Generate a unique filename to avoid overwriting
            $new_filename = uniqid() . "_" . $filename;

            // Check whether file exists before uploading it
            if (file_exists("upload/" . $new_filename)) {
                $image_err = "File already exists.";
            } else {
                if (move_uploaded_file($_FILES["image"]["tmp_name"], "upload/" . $new_filename)) {
                    $image = $new_filename;
                } else {
                    $image_err = "There was a problem uploading your file. Please try again.";
                }
            }
        } else {
            $image_err = "There was a problem with the file type. Please try again.";
        }
    } else {
        $image_err = "Please upload an image.";
    }

    // Validate max claims
    $input_max_claims = trim($_POST["max_claims"]);
    if (empty($input_max_claims) || !is_numeric($input_max_claims) || $input_max_claims <= 0) {
        $max_claims_err = "Please enter a valid number for max claims.";
    } else {
        $max_claims = $input_max_claims;
    }

    // Check input errors before inserting in database
    if (empty($program_name_err) && empty($loyalty_type_err) && empty($voucher_err) && empty($description_err) && empty($start_err) && empty($expiry_err) && empty($image_err) && empty($max_claims_err)) {
        // Prepare an insert statement
        $sql = "INSERT INTO loyalty_program (program_name, loyalty_type, voucher_code, challenge_description, start_date, expiry_date, image, max_claims) VALUES (?, ?, ?, ?, ?, ?, ?, ?)";

        if ($stmt = mysqli_prepare($conn, $sql)) {
            // Bind variables to the prepared statement as parameters
            mysqli_stmt_bind_param($stmt, "sssssssi", $param_program_name, $param_loyalty_type, $param_voucher, $param_description, $param_start_date, $param_expiry_date, $param_image, $param_max_claims);

            // Set parameters
            $param_program_name = $program_name;
            $param_loyalty_type = $loyalty_type;
            $param_voucher = $voucher_code;
            $param_description = $description;
            $param_start_date = $start_date;
            $param_expiry_date = $expiry_date;
            $param_image = $image;
            $param_max_claims = $max_claims;

            // Attempt to execute the prepared statement
            if (mysqli_stmt_execute($stmt)) {
                // Records created successfully. Redirect to landing page
                header("location: index_loyalty.php");
                exit();
            } else {
                echo "Oops! Something went wrong. Please try again later.";
            }
        }

        // Close statement
        mysqli_stmt_close($stmt);
    }

    // Close connection
    mysqli_close($conn);
}
?>