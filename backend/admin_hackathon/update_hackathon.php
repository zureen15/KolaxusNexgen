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
if (isset($_POST["hackathon_id"]) && !empty($_POST["hackathon_id"])) {
    $id = $_POST["hackathon_id"];

    // Validate hackathon name
    $input_name = trim($_POST["hackathon_name"]);
    if (empty($input_name)) {
        $hackathon_name_err = "Please enter a hackathon name.";
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

    // Validate company name
    $input_company = trim($_POST["company_name"]);
    if (empty($input_company)) {
        $company_err = "Please enter the company name";
    } else {
        $company = $input_company;
    }

    // Validate description
    $input_description = trim($_POST["hackathon_description"]);
    if (empty($input_description)) {
        $description_err = "Please enter a description.";
    } else {
        $description = $input_description;
    }

    // Validate start date
    $input_start = trim($_POST["start_date"]);
    if (empty($input_start)) {
        $start_err = "Please enter a start date.";
    } else {
        $start_date = $input_start;
    }

    // Validate end date
    $input_end = trim($_POST["end_date"]);
    if (empty($input_end)) {
        $end_err = "Please enter an end date.";
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
    $uploaded_images = [];
    if (!empty($_FILES['images']['name'][0])) {
        $target_dir = "upload/";
        foreach ($_FILES['images']['name'] as $key => $image_name) {
            $target_file = $target_dir . basename($image_name);
            if (move_uploaded_file($_FILES['images']['tmp_name'][$key], $target_file)) {
                $uploaded_images[] = $image_name;
            }
        }
    }

    // Combine existing images with newly uploaded images
    if (!empty($_POST['existing_images'])) {
        $existing_images = explode(',', $_POST['existing_images']);
        $all_images = array_merge($existing_images, $uploaded_images);
    } else {
        $all_images = $uploaded_images;
    }

    $image = implode(',', $all_images);
    

    // Check input errors before updating in database
    if (empty($hackathon_name_err) && empty($hackathon_type_err) && empty($company_err) && empty($description_err) && empty($start_err) && empty($end_err) && empty($image_err) && empty($contact_person_err) && empty($contact_email_err) && empty($location_err) && empty($prizes_err) && empty($deadline_err) && empty($sponsors_err) && empty($judges_err) && empty($rules_err) && empty($website_err)) {
        // Prepare an update statement
        $sql = "UPDATE hackathon_program SET hackathon_name=?, hackathon_type=?, company_name=?, hackathon_description=?, start_date=?, end_date=?, images=?, contact_person=?, contact_email=?, location=?, prizes=?, registration_deadline=?, sponsors=?, judges=?, rules_guidelines=?, website=?  WHERE hackathon_id=?";

        if ($stmt = mysqli_prepare($conn, $sql)) {
            // Bind variables to the prepared statement as parameters
            mysqli_stmt_bind_param($stmt, "ssssssssssssssssi", $param_hackathon_name, $param_hackathon_type, $param_company, $param_description, $param_start_date, $param_end_date, $param_image, $param_contact_person, $param_contact_email, $param_location, $param_prizes, $param_deadline, $param_sponsors, $param_judges, $param_rules, $param_website, $param_id);

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
            $param_id = $id;

            // Attempt to execute the prepared statement
            if (mysqli_stmt_execute($stmt)) {
                // Records updated successfully. Redirect to landing page
                header("location: hackathon.php");
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
    if (isset($_GET["hackathon_id"]) && !empty(trim($_GET["hackathon_id"]))) {
        // Get URL parameter
        $id = trim($_GET["hackathon_id"]);

        // Prepare a select statement
        $sql = "SELECT * FROM hackathon_program WHERE hackathon_id = ?";
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
                    $hackathon_name = $row["hackathon_name"];
                    $hackathon_type = $row["hackathon_type"];
                    $description = $row["hackathon_description"];
                    $company = $row["company_name"];
                    $start_date = $row["start_date"];
                    $end_date = $row["end_date"];
                    $contact_person = $row["contact_person"];
                    $contact_email = $row["contact_email"];
                    $location = $row["location"];
                    $prizes = $row["prizes"];
                    $deadline = $row["registration_deadline"];
                    $sponsors = $row["sponsors"];
                    $judges = $row["judges"];
                    $rules = $row["rules_guidelines"];
                    $website = $row["website"];
                    $image = $row["images"];
                } else {
                    // URL doesn't contain valid id. Redirect to error page
                    header("location: error_hackathon.php");
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
        header("location: error_hackathon.php");
        exit();
    }
}
?>