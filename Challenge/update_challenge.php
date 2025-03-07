<?php
// Include config file
require_once "../database/config.php";
require "../backend/admin_challenge/update_challenge.php";
?>

<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <title>Update Challenge</title>
    <link rel="stylesheet" href="https://stackpath.bootstrapcdn.com/bootstrap/4.5.2/css/bootstrap.min.css">
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/flatpickr/dist/flatpickr.min.css">
    <script src="https://cdn.jsdelivr.net/npm/flatpickr"></script>
    <script src="challenge.js?v=1"></script>
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
    </style>
</head>

<body>
    <div class="wrapper">
        <div class="container-fluid">
            <div class="row">
                <div class="col-md-12">
                    <div class="card mt-5">
                        <div class="card-header">
                            <h2>Update Challenge</h2>
                            <p>Please fill this form and submit to update challenge record in the database.</p>
                        </div>
                        <div class="card-body">
                            <form action="update_challenge.php" method="post" enctype="multipart/form-data">
                                <div class="form-row">
                                    <div class="form-group col-md-6">
                                        <label>Upload Images:</label>
                                        <input type="file" id="image" name="image[]" class="form-control" multiple>
                                        <div id="imagePreview" class="image-preview">
                                            <?php if (!empty($image)): ?>
                                                <?php foreach (explode(',', $image) as $img): ?>
                                                    <div class="image-container">
                                                        <img src="upload/<?php echo htmlspecialchars($img); ?>" alt="Image"
                                                            width="50" height="50">
                                                    </div>
                                                <?php endforeach; ?>
                                                <input type="hidden" name="existing_images"
                                                    value="<?php echo htmlspecialchars($image); ?>">
                                            <?php endif; ?>
                                        </div>
                                    </div>
                                    <div class="form-group col-md-6">
                                        <label>Challenge Name</label>
                                        <input type="text" id="program_name" name="program_name"
                                            class="form-control <?php echo (!empty($program_name_err)) ? 'is-invalid' : ''; ?>"
                                            value="<?php echo $program_name; ?>">
                                        <span class="invalid-feedback"><?php echo $program_name_err; ?></span>
                                    </div>
                                </div>
                                <div class="form-row">
                                    <div class="form-group col-md-6">
                                        <label>Challenge Type</label>
                                        <select id="challenge_type" name="challenge_type"
                                            onchange="toggleLocationField()"
                                            class="form-select form-control <?php echo (!empty($challenge_type_err)) ? 'is-invalid' : ''; ?>">
                                            <option selected disabled value="">-- Choose --</option>
                                            <option value="Online" <?php echo ($challenge_type == 'Online') ? 'selected' : ''; ?>>Online</option>
                                            <option value="Offline" <?php echo ($challenge_type == 'Offline') ? 'selected' : ''; ?>>Offline</option>
                                        </select>
                                    </div>
                                    <div class="form-group col-md-6" id="location_field"
                                        style="display: <?php echo ($challenge_type == 'Offline') ? 'block' : 'none'; ?>;">
                                        <label>Location</label>
                                        <input type="text" id="location" name="location" class="form-control"
                                            value="<?php echo htmlspecialchars($location); ?>">
                                        <span class="invalid-feedback"><?php echo $location_err; ?></span>
                                    </div>
                                </div>
                                <div class="form-group">
                                    <label>Description</label>
                                    <textarea id="challenge_description" name="challenge_description"
                                        class="form-control <?php echo (!empty($description_err)) ? 'is-invalid' : ''; ?>"><?php echo $description; ?></textarea>
                                    <span class="invalid-feedback"><?php echo $description_err; ?></span>
                                </div>
                                <div class="form-row">
                                    <div class="form-group col-md-6">
                                        <label>Start Date:</label>
                                        <input type="datetime-local" id="start_date" name="start_date"
                                            class="form-control <?php echo (!empty($start_err)) ? 'is-invalid' : ''; ?>"
                                            value="<?php echo $start_date; ?>">
                                        <span class="invalid-feedback"><?php echo $start_err; ?></span>
                                    </div>
                                    <div class="form-group col-md-6">
                                        <label>End Date:</label>
                                        <input type="datetime-local" id="end_date" name="end_date"
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
                                        <input type="datetime-local" id="registration_deadline"
                                            name="registration_deadline"
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
                                <input type="hidden" name="challenge_id" value="<?php echo $id; ?>" />
                                <input type="submit" class="btn btn-primary" value="Submit">
                                <a href="challenge.php" class="btn btn-secondary ml-2">Cancel</a>
                            </form>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
    <script>
        ClassicEditor
            .create(document.querySelector('#challenge_description'))
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