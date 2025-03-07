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
if (isset($_POST["voucher_id"]) && !empty($_POST["voucher_id"])) {
    $id = $_POST["voucher_id"];

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

    // Validate max claims
    $input_max_claims = trim($_POST["max_claims"]);
    if (empty($input_max_claims) || !is_numeric($input_max_claims) || $input_max_claims <= 0) {
        $max_claims_err = "Please enter a valid number for max claims.";
    } else {
        $max_claims = (int) $input_max_claims; // Cast the input to an integer
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
            // Check whether file exists before uploading it
            if (file_exists("upload/" . $filename)) {
                $image = $filename; // Use the existing file
            } else {
                if (move_uploaded_file($_FILES["image"]["tmp_name"], "upload/" . $filename)) {
                    $image = $filename;
                } else {
                    $image_err = "There was a problem uploading your file. Please try again.";
                }
            }
        } else {
            $image_err = "There was a problem uploading your file. Please try again.";
        }
    } else {
        // If no new image is uploaded, use the existing image from the database
        $image = $_POST["existing_image"];
    }

    // Check input errors before updating in database
    if (empty($program_name_err) && empty($loyalty_type_err) && empty($voucher_err) && empty($description_err) && empty($start_err) && empty($expiry_err) && empty($image_err) && empty($max_claims_err)) {
        // Prepare an update statement
        $sql = "UPDATE loyalty_program SET program_name=?, loyalty_type=?, voucher_code=?, challenge_description=?, start_date=?, expiry_date=?, image=?, max_claims=? WHERE voucher_id=?";

        if ($stmt = mysqli_prepare($conn, $sql)) {
            // Bind variables to the prepared statement as parameters
            mysqli_stmt_bind_param($stmt, "sssssssii", $param_program_name, $param_loyalty_type, $param_voucher, $param_description, $param_start_date, $param_expiry_date, $param_image, $param_max_claims, $param_id);

            // Set parameters
            $param_program_name = $program_name;
            $param_loyalty_type = $loyalty_type;
            $param_voucher = $voucher_code;
            $param_description = $description;
            $param_start_date = $start_date;
            $param_expiry_date = $expiry_date;
            $param_image = $image;
            $param_max_claims = $max_claims;
            $param_id = $id;

            // Attempt to execute the prepared statement
            if (mysqli_stmt_execute($stmt)) {
                // Records updated successfully. Redirect to landing page
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
} else {
    // Check existence of id parameter before processing further
    if (isset($_GET["voucher_id"]) && !empty(trim($_GET["voucher_id"]))) {
        // Get URL parameter
        $id = trim($_GET["voucher_id"]);

        // Prepare a select statement
        $sql = "SELECT * FROM loyalty_program WHERE voucher_id = ?";
        if ($stmt = mysqli_prepare($conn, $sql)) {
            mysqli_stmt_bind_param($stmt, "i", $param_id);

            // Set parameters
            $param_id = $id;

            // Attempt to execute the prepared statement
            if (mysqli_stmt_execute($stmt)) {
                $result = mysqli_stmt_get_result($stmt);

                if (mysqli_num_rows($result) == 1) {
                    /* Fetch result row as an associative array. Since the result set
                    contains only one row, we don't need to use while loop */
                    $row = mysqli_fetch_array($result, MYSQLI_ASSOC);

                    // Retrieve individual field value
                    $program_name = $row["program_name"];
                    $loyalty_type = $row["loyalty_type"];
                    $voucher_code = $row["voucher_code"];
                    $description = $row["challenge_description"];
                    $start_date = $row["start_date"];
                    $expiry_date = $row["expiry_date"];
                    $image = $row["image"];
                    $max_claims = $row["max_claims"];
                } else {
                    // URL doesn't contain valid id. Redirect to error page
                    header("location: error_loyalty.php");
                    exit();
                }

            } else {
                echo "Oops! Something went wrong. Please try again later.";
            }
        }

        // Close statement
        mysqli_stmt_close($stmt);

        // Close connection
        mysqli_close($conn);
    } else {
        // URL doesn't contain id parameter. Redirect to error page
        header("location: error_loyalty.php");
        exit();
    }
}
?>