<?php
// Include config file
require "../database/config.php";
require "../backend/admin_hackathon/create_hackathon.php";

?>

<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1.0, user-scalable=no">
    <title>Create Hackathon Program</title>
    <link rel="stylesheet" href="https://stackpath.bootstrapcdn.com/bootstrap/4.5.2/css/bootstrap.min.css">
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/flatpickr/dist/flatpickr.min.css">
    <script src="https://cdn.jsdelivr.net/npm/flatpickr"></script>
    <script src="hackathon.js?v=1"></script>
    <script src="https://cdn.ckeditor.com/ckeditor5/35.0.1/classic/ckeditor.js"></script>
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

        .image-container {
            position: relative;
            margin-right: 10px;
        }

        .remove-image {
            position: absolute;
            top: 0;
            right: 0;
            background: red;
            color: white;
            border: none;
            cursor: pointer;
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
                            <h2>Create Hackathon</h2>
                            <p>Please fill this form and submit to add hackathon program to the database.</p>
                        </div>
                        <div class="card-body">
                            <form action="create_hackathon.php" method="post" enctype="multipart/form-data">
                                <div class="form-row">
                                    <div class="form-group col-md-6">
                                        <label>Upload Images:</label>
                                        <input type="file" id="images" name="images[]" multiple
                                            class="form-control <?php echo (!empty($image_err)) ? 'is-invalid' : ''; ?>">
                                        <span class="invalid-feedback"><?php echo $image_err; ?></span>
                                        <div id="imagePreview" class="image-preview"></div>
                                    </div>
                                    <div class="form-group col-md-6">
                                        <label>Hackathon Name</label>
                                        <input type="text" id="hackathon_name" name="hackathon_name"
                                            class="form-control <?php echo (!empty($hackathon_name_err)) ? 'is-invalid' : ''; ?>"
                                            value="<?php echo $hackathon_name; ?>">
                                        <span class="invalid-feedback"><?php echo $hackathon_name_err; ?></span>
                                    </div>
                                </div>
                                <div class="form-row">
                                    <div class="form-group col-md-6">
                                        <label>Hackathon Type</label>
                                        <select id="hackathon_type" name="hackathon_type"
                                            onchange="toggleLocationField()"
                                            class="form-select form-control <?php echo (!empty($hackathon_type_err)) ? 'is-invalid' : ''; ?>">
                                            <option selected disabled value="">-- Choose --</option>
                                            <option value="Online" <?php echo ($hackathon_type == 'Online') ? 'selected' : ''; ?>>Online</option>
                                            <option value="Offline" <?php echo ($hackathon_type == 'Offline') ? 'selected' : ''; ?>>Offline</option>
                                        </select>
                                    </div>
                                    <div class="form-group col-md-6" id="location_field"
                                        style="display: <?php echo ($hackathon_type == 'Offline') ? 'block' : 'none'; ?>;">
                                        <label>Location</label>
                                        <input type="text" id="location" name="location" class="form-control"
                                            value="<?php echo htmlspecialchars($location); ?>">
                                        <span class="invalid-feedback"><?php echo $location_err; ?></span>
                                    </div>
                                </div>
                                <div class="form-group">
                                    <label>Description</label>
                                    <textarea id="hackathon_description" name="hackathon_description"
                                        class="form-control <?php echo (!empty($description_err)) ? 'is-invalid' : ''; ?>"><?php echo $description; ?></textarea>
                                    <span class="invalid-feedback"><?php echo $description_err; ?></span>
                                </div>
                                <div class="form-row">
                                    <div class="form-group col-md-6">
                                        <label>Start Date:</label>
                                        <input type="text" id="start_date" name="start_date"
                                            class="form-control <?php echo (!empty($start_err)) ? 'is-invalid' : ''; ?>"
                                            value="<?php echo $start_date; ?>">
                                        <span class="invalid-feedback"><?php echo $start_err; ?></span>
                                    </div>
                                    <div class="form-group col-md-6">
                                        <label>End Date:</label>
                                        <input type="text" id="end_date" name="end_date"
                                            class="form-control <?php echo (!empty($end_err)) ? 'is-invalid' : ''; ?>"
                                            value="<?php echo $end_date; ?>">
                                        <span class="invalid-feedback"><?php echo $end_err; ?></span>
                                    </div>
                                </div>
                                <div class="form-row">
                                    <div class="form-group col-md-6">
                                        <label>Contact Person</label>
                                        <input type="tel" id="contact_person" name="contact_person"
                                            class="form-control <?php echo (!empty($contact_person_err)) ? 'is-invalid' : ''; ?>"
                                            value="<?php echo $contact_person; ?>" required pattern="\d{10}"
                                            title="Enter a valid phone number">
                                        <span class="invalid-feedback"><?php echo $contact_person_err; ?></span>
                                    </div>
                                    <div class="form-group col-md-6">
                                        <label>Contact Email</label>
                                        <input type="text" id="contact_email" name="contact_email"
                                            class="form-control <?php echo (!empty($contact_email_err)) ? 'is-invalid' : ''; ?>"
                                            value="<?php echo $contact_email; ?>" required pattern=".+@.+\..+"
                                            title="Enter a valid email address">
                                        <span class="invalid-feedback"><?php echo $contact_email_err; ?></span>
                                    </div>
                                </div>
                                <div class="form-row">
                                    <div class="form-group col-md-6">
                                        <label>Company Name</label>
                                        <input type="text" id="company_name" name="company_name"
                                            class="form-control <?php echo (!empty($company_err)) ? 'is-invalid' : ''; ?>"
                                            value="<?php echo $company; ?>">
                                        <span class="invalid-feedback"><?php echo $company_err; ?></span>
                                    </div>
                                    <div class="form-group col-md-6">
                                        <label>Prizes</label>
                                        <input type="text" id="prizes" name="prizes"
                                            class="form-control <?php echo (!empty($prizes_err)) ? 'is-invalid' : ''; ?>"
                                            value="<?php echo $prizes; ?>">
                                        <span class="invalid-feedback"><?php echo $prizes_err; ?></span>
                                    </div>
                                </div>
                                <div class="form-row">
                                    <div class="form-group col-md-6">
                                        <label>Registration Deadline</label>
                                        <input type="text" id="registration_deadline" name="registration_deadline"
                                            class="form-control <?php echo (!empty($deadline_err)) ? 'is-invalid' : ''; ?>"
                                            value="<?php echo $deadline; ?>">
                                        <span class="invalid-feedback"><?php echo $deadline_err; ?></span>
                                    </div>
                                    <div class="form-group col-md-6">
                                        <label>Sponsors</label>
                                        <input type="text" id="sponsors" name="sponsors"
                                            class="form-control <?php echo (!empty($sponsors_err)) ? 'is-invalid' : ''; ?>"
                                            value="<?php echo $sponsors; ?>">
                                        <span class="invalid-feedback"><?php echo $sponsors_err; ?></span>
                                    </div>
                                </div>
                                <div class="form-row">
                                    <div class="form-group col-md-6">
                                        <label>Judges</label>
                                        <input type="text" id="judges" name="judges"
                                            class="form-control <?php echo (!empty($judges_err)) ? 'is-invalid' : ''; ?>"
                                            value="<?php echo $judges; ?>">
                                        <span class="invalid-feedback"><?php echo $judges_err; ?></span>
                                    </div>
                                    <div class="form-group col-md-6">
                                        <label>Website</label>
                                        <input type="url" id="website" name="website"
                                            class="form-control <?php echo (!empty($website_err)) ? 'is-invalid' : ''; ?>"
                                            value="<?php echo $website; ?>">
                                        <span class="invalid-feedback"><?php echo $website_err; ?></span>
                                    </div>
                                </div>
                                <div class="form-group">
                                    <label>Rules and Guidelines</label>
                                    <textarea id="rules_guidelines" name="rules_guidelines" rows="5"
                                        class="form-control <?php echo (!empty($rules_err)) ? 'is-invalid' : ''; ?>"><?php echo $rules; ?></textarea>
                                    <span class="invalid-feedback"><?php echo $rules_err; ?></span>
                                </div>
                                <input type="submit" class="btn btn-primary" value="Submit">
                                <a href="hackathon.php" class="btn btn-secondary ml-2">Cancel</a>
                            </form>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
    <script>
        ClassicEditor
            .create(document.querySelector('#hackathon_description'))
            .catch(error => {
                console.error(error);
            });

        ClassicEditor
            .create(document.querySelector('#rules_guidelines'))
            .catch(error => {
                console.error(error);
            });
    </script>
</body>

</html>