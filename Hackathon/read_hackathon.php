<?php
// Add secure headers
header("X-Content-Type-Options: nosniff");
header("X-Frame-Options: DENY");
header("X-XSS-Protection: 1; mode=block");
header("Strict-Transport-Security: max-age=31536000; includeSubDomains; preload");

if (isset($_GET["hackathon_id"]) && !empty(trim($_GET["hackathon_id"]))) {
    require "../database/config.php";

    $sql = "SELECT * FROM hackathon_program WHERE hackathon_id = ?";

    if ($stmt = mysqli_prepare($conn, $sql)) {
        mysqli_stmt_bind_param($stmt, "i", $param_id);

        $param_id = filter_var(trim($_GET["hackathon_id"]), FILTER_SANITIZE_NUMBER_INT);

        if (mysqli_stmt_execute($stmt)) {
            $result = mysqli_stmt_get_result($stmt);

            if (mysqli_num_rows($result) == 1) {
                $row = mysqli_fetch_array($result, MYSQLI_ASSOC);

                $hackathon_name = htmlspecialchars($row["hackathon_name"], ENT_QUOTES, 'UTF-8');
                $hackathon_type = htmlspecialchars($row["hackathon_type"], ENT_QUOTES, 'UTF-8');
                $company = htmlspecialchars($row["company_name"], ENT_QUOTES, 'UTF-8');
                $description = htmlspecialchars_decode($row["hackathon_description"], ENT_QUOTES);
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
                $image = htmlspecialchars($row["images"], ENT_QUOTES, 'UTF-8');
            } else {
                header("location: error_hackathon.php");
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
    header("location: error_hackathon.php");
    exit();
}
?>

<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1.0, user-scalable=no">
    <title>View Hackathon</title>
    <link rel="stylesheet" href="https://stackpath.bootstrapcdn.com/bootstrap/4.5.2/css/bootstrap.min.css">
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/flatpickr/dist/flatpickr.min.css">
    <link rel="stylesheet" href="../frontend/css/dashboard.css?v=1">
    <script src="https://cdn.jsdelivr.net/npm/flatpickr"></script>
    <script src="hackathon.js?v=1"></script>
</head>

<body>
    <div class="wrapper">
        <div class="container-fluid">
            <div class="row">
                <div class="col-md-12">
                    <div class="card mt-5">
                        <div class="card-header">
                            <h2>View Hackathon</h2>
                        </div>
                        <div class="card-body">
                            <div class="row">
                                <div class="col-md-6">
                                    <div class="form-group">
                                        <label>Images</label>
                                        <div class="image-preview">
                                            <?php
                                            // Split the image string into an array
                                            $images = explode(',', $image);
                                            foreach ($images as $img) {
                                                // Display each image
                                                echo '<img src="upload/' . htmlspecialchars($img) . '" alt="Image" width="100" height="100" style="margin-right: 5px; border: 1px solid #ddd; border-radius: 5px;">';
                                            }
                                            ?>
                                        </div>
                                    </div>
                                </div>
                                <div class="col-md-6">
                                    <div class="form-group">
                                        <label>Hackathon Name</label>
                                        <p><b><?php echo $hackathon_name; ?></b></p>
                                    </div>
                                </div>
                                <div class="col-md-6">
                                    <div class="form-group">
                                        <label>Hackathon Type</label>
                                        <p><b><?php echo $hackathon_type; ?></b></p>
                                    </div>
                                </div>
                                <div class="col-md-6">
                                    <div class="form-group">
                                        <label>Company Name</label>
                                        <p><b><?php echo $company; ?></b></p>
                                    </div>
                                </div>
                                <div class="col-md-6">
                                    <div class="form-group">
                                        <label>Description</label>
                                        <p><b><?php echo $description; ?></b></p>
                                    </div>
                                </div>
                                <div class="col-md-6">
                                    <div class="form-group">
                                        <label>Start Date</label>
                                        <p><b><?php echo $start_date; ?></b></p>
                                    </div>
                                </div>
                                <div class="col-md-6">
                                    <div class="form-group">
                                        <label>End Date</label>
                                        <p><b><?php echo $end_date; ?></b></p>
                                    </div>
                                </div>
                                <div class="col-md-6">
                                    <div class="form-group">
                                        <label>Contact Person</label>
                                        <p><b><?php echo $contact_person; ?></b></p>
                                    </div>
                                </div>
                                <div class="col-md-6">
                                    <div class="form-group">
                                        <label>Contact Email</label>
                                        <p><b><?php echo $contact_email; ?></b></p>
                                    </div>
                                </div>
                                <div class="col-md-6">
                                    <div class="form-group">
                                        <label>Location</label>
                                        <p><b><?php echo $location; ?></b></p>
                                    </div>
                                </div>
                                <div class="col-md-6">
                                    <div class="form-group">
                                        <label>Prizes</label>
                                        <p><b><?php echo $prizes; ?></b></p>
                                    </div>
                                </div>
                                <div class="col-md-6">
                                    <div class="form-group">
                                        <label>Registration Deadline</label>
                                        <p><b><?php echo $registration_deadline; ?></b></p>
                                    </div>
                                </div>
                                <div class="col-md-6">
                                    <div class="form-group">
                                        <label>Sponsors</label>
                                        <p><b><?php echo $sponsors; ?></b></p>
                                    </div>
                                </div>
                                <div class="col-md-6">
                                    <div class="form-group">
                                        <label>Judges</label>
                                        <p><b><?php echo $judges; ?></b></p>
                                    </div>
                                </div>
                                <div class="col-md-6">
                                    <div class="form-group">
                                        <label>Rules and Guidelines</label>
                                        <p><b><?php echo $rules_guidelines; ?></b></p>
                                    </div>
                                </div>
                                <div class="col-md-6">
                                    <div class="form-group">
                                        <label>Website</label>
                                        <p><b><?php echo $website; ?></b></p>
                                    </div>
                                </div>
                            </div>
                            <p><a href="hackathon.php" class="btn">Back</a></p>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</body>

</html>