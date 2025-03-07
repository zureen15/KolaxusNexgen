<?php
// Include config file
require_once "../database/config.php";

header("Cache-Control: no-store, no-cache, must-revalidate, max-age=0");
header("Cache-Control: post-check=0, pre-check=0", false);
header("Pragma: no-cache");

// Define variables and initialize with empty values
$hackathon_name = $hackathon_type = $company = $description = $start_date = $end_date = $image = $contact_person = $contact_email = $location = $prizes = $deadline = $sponsors = $judges = $rules = $website = "";
$hackathon_name_err = $hackathon_type_err = $company_err = $description_err = $start_err = $end_err = $image_err = $contact_person_err = $contact_email_err = $location_err = $prizes_err = $deadline_err = $sponsors_err = $judges_err = $rules_err = $website_err = "";

// Processing form data when form is submitted
if ($_SERVER["REQUEST_METHOD"] == "POST") {
    // Validate hackathon program name
    $input_name = trim($_POST["hackathon_name"]);
    if (empty($input_name)) {
        $hackathon_name_err = "Please enter a hackathon program name.";
    } else {
        $hackathon_name = $input_name;
    }

    // Validate hackathon type
    $input_type = trim($_POST["hackathon_type"]);
    if (empty($input_type)) {
        $hackathon_type_err = "Please select the hackathon type.";
    } else {
        $hackathon_type = $input_type;

        // Check if the hackathon type is Offline and validate location
        if ($hackathon_type === "Offline") {
            $input_location = trim($_POST["location"]);
            if (empty($input_location)) {
                $location_err = "Please input the location.";
            } else {
                $location = $input_location;
            }
        } else {
            // If Online, set location to null or empty
            $location = null; // or $location = "";
        }
    }

    // Validate description
    $input_description = trim($_POST["hackathon_description"]);
    if (empty($input_description)) {
        $description_err = "Please enter the description of the hackathon.";
    } else {
        $description = $input_description;
    }

    // Validate company name
    $input_company = trim($_POST["company_name"]);
    if (empty($input_company)) {
        $company_err = "Please enter the company name";
    } else {
        $company = $input_company;
    }

    // Validate start date of loyalty
    $input_start = trim($_POST["start_date"]);
    if (empty($input_start)) {
        $start_err = "Please select the start date of hackathon program.";
    } else {
        $start_date = $input_start;
    }

    // Validate expiry date of loyalty
    $input_end = trim($_POST["end_date"]);
    if (empty($input_end)) {
        $end_err = "Please select the end date of hackathon program.";
    } else {
        $end_date = $input_end;
    }

    // Validate contact person
    if (empty(trim($_POST["contact_person"]))) {
        $contact_person_err = "Please enter a contact person.";
    } elseif (!preg_match("/^\d{10}$/", trim($_POST["contact_person"]))) {
        $contact_person_err = "Please enter a valid phone number (e.g., 1234567890).";
    } else {
        $contact_person = trim($_POST["contact_person"]);
    }

    // Validate contact email
    if (empty(trim($_POST["contact_email"]))) {
        $contact_email_err = "Please enter a contact email.";
    } elseif (!filter_var(trim($_POST["contact_email"]), FILTER_VALIDATE_EMAIL)) {
        $contact_email_err = "Please enter a valid email address.";
    } else {
        $contact_email = trim($_POST["contact_email"]);
    }

    // Validate the prizes
    $input_prizes = trim($_POST["prizes"]);
    if (empty($input_prizes)) {
        $prizes_err = "Please input the prizes.";
    } else {
        $prizes = $input_prizes;
    }

    // Validate the registration deadline
    $input_deadline = trim($_POST["registration_deadline"]);
    if (empty($input_deadline)) {
        $deadline_err = "Please input the registration deadline.";
    } else {
        $deadline = $input_deadline;
    }

    // Validate the sponsors
    $input_sponsors = trim($_POST["sponsors"]);
    if (empty($input_sponsors)) {
        $sponsors_err = "Please input the sponsors.";
    } else {
        $sponsors = $input_sponsors;
    }

    // Validate the judges
    $input_judges = trim($_POST["judges"]);
    if (empty($input_judges)) {
        $judges_err = "Please input the judges.";
    } else {
        $judges = $input_judges;
    }

    // Validate the rules and guidelines
    $input_rules = trim($_POST["rules_guidelines"]);
    if (empty($input_rules)) {
        $rules_err = "Please input the rules and guidelines.";
    } else {
        $rules = $input_rules;
    }

    // Validate the website
    $input_website = trim($_POST["website"]);
    if (empty($input_website)) {
        $website_err = "Please input the website link.";
    } else {
        $website = $input_website;
    }

    // Handle image upload
    $image_filenames = [];
    $upload_dir = "upload/";

    if (isset($_FILES["images"]) && count($_FILES["images"]["name"]) > 0) {
        $allowed = ["jpg" => "images/jpg", "jpeg" => "images/jpeg", "png" => "images/png"];

        foreach ($_FILES["images"]["name"] as $key => $filename) {
            $filetype = $_FILES["images"]["type"][$key];
            $filesize = $_FILES["images"]["size"][$key];

            // Verify file extension
            $ext = pathinfo($filename, PATHINFO_EXTENSION);
            if (!array_key_exists($ext, $allowed)) {
                $image_err = "Please select a valid file format.";
                break;
            }

            // Verify file size - 5MB maximum
            if ($filesize > 5 * 1024 * 1024) {
                $image_err = "File size is larger than the allowed limit.";
                break;
            }

            // Verify MIME type of the file
            if (in_array($filetype, $allowed)) {
                // Generate a unique filename to avoid overwriting
                $new_filename = uniqid() . "_" . basename($filename);

                // Move the uploaded file
                if (move_uploaded_file($_FILES["images"]["tmp_name"][$key], $upload_dir . $new_filename)) {
                    $image_filenames[] = $new_filename;
                } else {
                    $image_err = "There was a problem uploading your file. Please try again.";
                    break;
                }
            } else {
                $image_err = "There was a problem with the file type. Please try again.";
                break;
            }
        }

        if (empty($image_err)) {
            $image = implode(',', $image_filenames);
        } else {
            $image = "";
        }
    }

    // Check input errors before inserting in database
    if (empty($hackathon_name_err) && empty($hackathon_type_err) && empty($company_err) && empty($description_err) && empty($start_err) && empty($end_err) && empty($image_err) && empty($contact_person_err) && empty($contact_email_err) && empty($location_err) && empty($prizes_err) && empty($deadline_err) && empty($sponsors_err) && empty($judges_err) && empty($rules_err) && empty($website_err)) {
        // Prepare an insert statement
        $sql = "INSERT INTO hackathon_program (hackathon_name, hackathon_type, company_name, hackathon_description, start_date, end_date, images, contact_person, contact_email, location, prizes, registration_deadline, sponsors, judges, rules_guidelines, website) VALUES (?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?)";

        if ($stmt = mysqli_prepare($conn, $sql)) {
            // Bind variables to the prepared statement as parameters
            mysqli_stmt_bind_param($stmt, "ssssssssssssssss", $param_hackathon_name, $param_hackathon_type, $param_company, $param_description, $param_start_date, $param_end_date, $param_image, $param_contact_person, $param_contact_email, $param_location, $param_prizes, $param_deadline, $param_sponsors, $param_judges, $param_rules, $param_website);

            // Set parameters
            $param_hackathon_name = $hackathon_name;
            $param_hackathon_type = $hackathon_type;
            $param_company = $company;
            $param_description = $description;
            $param_start_date = $start_date;
            $param_end_date = $end_date;
            $param_image = $image;
            $param_contact_person = $contact_person;
            $param_contact_email = $contact_email;
            $param_location = $location;
            $param_prizes = $prizes;
            $param_deadline = $deadline;
            $param_sponsors = $sponsors;
            $param_judges = $judges;
            $param_rules = $rules;
            $param_website = $website;

            // Attempt to execute the prepared statement
            if (mysqli_stmt_execute($stmt)) {
                // Records created successfully. Redirect to landing page
                header("location: ../Hackathon/hackathon.php");
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