<?php
if (isset($_GET["challenge_id"]) && !empty(trim($_GET["challenge_id"]))) {
    require "../database/config.php";

    $sql = "SELECT * FROM challenges WHERE challenge_id = ?";

    if ($stmt = mysqli_prepare($conn, $sql)) {
        mysqli_stmt_bind_param($stmt, "i", $param_id);

        $param_id = trim($_GET["challenge_id"]);
        // Attempt to execute the prepared statement
        if (mysqli_stmt_execute($stmt)) {
            $result = mysqli_stmt_get_result($stmt);

            if (mysqli_num_rows($result) == 1) {
                $row = mysqli_fetch_array($result, MYSQLI_ASSOC);

                // Retrieve individual field value
                $program_name = $row["program_name"];
                $challenge_type = $row["challenge_type"];
                $company = $row["company_name"];
                $description = $row["challenge_description"];
                $start_date = $row["start_date"];
                $end_date = $row["end_date"];
                $contact_person = $row["contact_person"];
                $contact_email = $row["contact_email"];
                $location = $row["location"];
                $prizes = $row["prizes"];
                $registration_deadline = $row["registration_deadline"];
                $number_participants = $row["number_participants"];
                $sponsors = $row["sponsors"];
                $judges = $row["judges"];
                $rules_guidelines = $row["rules_guidelines"];
                $image = $row["image"];
            } else {
                // URL doesn't contain valid id parameter. Redirect to error page
                header("location: error_challenge.php");
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
    header("location: error_challenge.php");
    exit();
}
?>

<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1.0, user-scalable=no">
    <title>View Challenge</title>
    <link rel="stylesheet" href="https://stackpath.bootstrapcdn.com/bootstrap/4.5.2/css/bootstrap.min.css">
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/flatpickr/dist/flatpickr.min.css">
    <script src="https://cdn.jsdelivr.net/npm/flatpickr"></script>
    <script src="challenge.js?v=1"></script>
    <style>
        .wrapper {
            width: 100%;
            max-width: 800px;
            margin: 0 auto;
        }

        .form-select {
            appearance: none;
            -webkit-appearance: none;
            -moz-appearance: none;
            background: url('data:image/svg+xml;charset=US-ASCII,<svg xmlns="http://www.w3.org/2000/svg" viewBox="0 0 4 5"><path fill="none" stroke="currentColor" stroke-width=".5" d="M2 0L0 2h4zm0 5L0 3h4z"/></svg>') no-repeat right .75rem center/8px 10px;
        }

        .card {
            border-radius: 10px;
            box-shadow: 0 4px 8px rgba(0, 0, 0, 0.1);
        }

        .card-header {
            background-color: #007bff;
            color: white;
            border-radius: 10px 10px 0 0;
        }

        .form-group label {
            font-weight: bold;
        }

        .form-group p {
            margin-bottom: 0;
        }
    </style>
</head>

<body>
    <div class="wrapper">
        <div class="container-fluid">
            <div class="row">
                <div class="col-md-12">
                    <div class="card mt-5">
                        <div class="card-header">
                            <h2>View Challenge</h2>
                        </div>
                        <div class="card-body">
                            <div class="row">
                                <div class="col-md-6">
                                    <div class="col-md-6">
                                        <div class="form-group">
                                            <label>Image</label>
                                            <p><img src="upload/<?php echo $image; ?>" alt="Current Image" width="100"
                                                    height="100"></p>
                                        </div>
                                    </div>
                                </div>
                                <div class="col-md-6">
                                    <div class="form-group">
                                        <label>Challenge Name</label>
                                        <p><b><?php echo $program_name; ?></b></p>
                                    </div>
                                </div>
                                <div class="col-md-6">
                                    <div class="form-group">
                                        <label>Challenge Type</label>
                                        <p><b><?php echo $challenge_type; ?></b></p>
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
                                        <label>Number of Participants</label>
                                        <p><b><?php echo $number_participants; ?></b></p>
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
                            </div>
                            <p><a href="challenge.php" class="btn btn-primary">Back</a></p>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</body>

</html>