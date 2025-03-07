<?php

include '../backend/register.php'; // Include the database connection

// Define cache file path and cache duration (e.g., 10 minutes)
$cache_dir = '../cache/';
$cache_file = $cache_dir . 'register_cache.html';
$cache_duration = 600; // 600 seconds = 10 minutes

// Ensure the cache directory exists
if (!is_dir($cache_dir)) {
    mkdir($cache_dir, 0777, true);
}

// Check if cache file exists and is still valid
if (file_exists($cache_file) && (time() - filemtime($cache_file)) < $cache_duration) {
    // Serve cached content
    readfile($cache_file);
    exit;
}

// Start output buffering
ob_start();
?>

<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8" />
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1.0, user-scalable=no">
    <title>Register Form</title>
    <link rel="stylesheet" href="../frontend/css/register.css?v=1" />
    <link href="https://cdnjs.cloudflare.com/ajax/libs/select2/4.0.13/css/select2.min.css" rel="stylesheet" />
    <script src="https://cdnjs.cloudflare.com/ajax/libs/jquery/3.6.0/jquery.min.js"></script>
    <script src="https://cdnjs.cloudflare.com/ajax/libs/select2/4.0.13/js/select2.min.js"></script>
    <script src="../frontend/js/register.js?v=1" defer></script>
</head>

<body>
    <!--  -->
    <form id="registration-form" class="form-wizard" method="POST" enctype="multipart/form-data">
        <input type="hidden" name="csrf_token" value="<?php echo $_SESSION['csrf_token']; ?>"> <!-- CSRF Token -->
        <input type="hidden" id="user_type" name="user_type" value="user" /> <!-- Default user type -->
        <input type="hidden" id="uni_id" name="uni_id">
        <input type="hidden" id="course_id" name="course_id">
        <!-- .completed -->
        <div class="completed" hidden>
            <svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke-width="1.5"
                stroke="currentColor">
                <path stroke-linecap="round" stroke-linejoin="round"
                    d="M9 12.75 11.25 15 15 9.75M21 12a9 9 0 1 1-18 0 9 9 0 0 1 18 0Z" />
            </svg>
            <h3>Registration Successful!</h3>
            <p>Please check your email for verification link in the inbox folder.</p>
        </div>
        <!-- /.completed -->

        <h1>Student Registration</h1>

        <!-- .progress-container -->
        <div class="progress-container">
            <div class="progress"></div>
            <ol>
                <li class="current">Personal</li>
                <li>Account</li>
                <li>Education</li>
                <li>Student Interest</li>
            </ol>
        </div>
        <!-- /.progress-container -->

        <!-- .steps-container -->
        <div class="steps-container">
            <div class="step">
                <h3>Personal Information</h3>
                <div class="row">
                    <div class="form-control">
                        <label for="first-name">First Name <span style="color: red;">*</span></label>
                        <input type="text" id="first-name" name="first-name" autocomplete="given-name" required />
                    </div>
                    <div class="form-control">
                        <label for="last-name">Last Name <span style="color: red;">*</span></label>
                        <input type="text" id="last-name" name="last-name" autocomplete="family-name" required />
                    </div>
                </div>
                <div class="row">
                    <div class="form-control">
                        <div class="select2-container">
                            <label for="nationality">Nationality <span style="color: red;">*</span></label>
                            <select id="nationality" name="nationality" required onchange="toggleCountryField()">
                                <option selected disabled value="">--Select your nationality--</option>
                                <option value="malaysian">Malaysian</option>
                                <option value="non-malaysian">Non-Malaysian</option>
                            </select>
                        </div>
                    </div>
                    <div class="form-control">
                        <div class="select2-container">
                            <label for="country">If Non-Malaysian</label>
                            <select id="country" name="country" autocomplete="off">
                                <option selected disabled value="">--Select your country--</option>
                                <?php foreach ($countries as $country): ?>
                                    <option value="<?php echo $country['country_id']; ?>">
                                        <?php echo $country['country_name']; ?>
                                    </option>
                                <?php endforeach; ?>
                            </select>
                        </div>
                    </div>
                </div>
                <div class="row">
                    <div class="form-control">
                        <label for="username">Username <span style="color: red;">*</span></label>
                        <input type="text" id="username" name="username" autocomplete="given-name" required />
                    </div>
                    <div class="form-control">
                        <label for="phone">Mobile Phone <span style="color: red;">*</span></label>
                        <input type="tel" id="phone" name="phone" autocomplete="on" required pattern="\d{10}"
                            title="Please enter a 10-digit phone number" />
                    </div>
                </div>
            </div>

            <!-- STEP 2 FOR ACCOUNT INFORMATION -->
            <div class="step">
                <h3>Account Information</h3>
                <div class="row">
                    <div class="form-control">
                        <label for="password">Password <span style="color: red;">*</span></label>
                        <div class="password-field">
                            <input type="password" id="password" name="password" required
                                pattern="^(?=.*[a-z])(?=.*[A-Z])(?=.*\d)(?=.*[!@#$%^&*])[A-Za-z\d!@#$%^&*]{8,}$"
                                title="Password must be at least 8 characters long and contain at least one uppercase letter, one lowercase letter, one number, and one special character." />
                            <span class="toggle-password" id="toggle-password"><svg xmlns="http://www.w3.org/2000/svg"
                                    viewBox="0 0 576 512">
                                    <path
                                        d="M288 32c-80.8 0-145.5 36.8-192.6 80.6C48.6 156 17.3 208 2.5 243.7c-3.3 7.9-3.3 16.7 0 24.6C17.3 304 48.6 356 95.4 399.4C142.5 443.2 207.2 480 288 480s145.5-36.8 192.6-80.6c46.8-43.5 78.1-95.4 93-131.1c3.3-7.9 3.3-16.7 0-24.6c-14.9-35.7-46.2-87.7-93-131.1C433.5 68.8 368.8 32 288 32zM144 256a144 144 0 1 1 288 0 144 144 0 1 1 -288 0zm144-64c0 35.3-28.7 64-64 64c-7.1 0-13.9-1.2-20.3-3.3c-5.5-1.8-11.9 1.6-11.7 7.4c.3 6.9 1.3 13.8 3.2 20.7c13.7 51.2 66.4 81.6 117.6 67.9s81.6-66.4 67.9-117.6c-11.1-41.5-47.8-69.4-88.6-71.1c-5.8-.2-9.2 6.1-7.4 11.7c2.1 6.4 3.3 13.2 3.3 20.3z" />
                                </svg>
                            </span>
                        </div>
                    </div>
                    <div class="form-control">
                        <label for="confirm-password">Confirm Password <span style="color: red;">*</span></label>
                        <div class="password-field">
                            <input type="password" id="confirm-password" name="confirm-password" required />
                            <span class="toggle-password" id="toggle-confirm-password"><svg
                                    xmlns="http://www.w3.org/2000/svg" viewBox="0 0 576 512">
                                    <path
                                        d="M288 32c-80.8 0-145.5 36.8-192.6 80.6C48.6 156 17.3 208 2.5 243.7c-3.3 7.9-3.3 16.7 0 24.6C17.3 304 48.6 356 95.4 399.4C142.5 443.2 207.2 480 288 480s145.5-36.8 192.6-80.6c46.8-43.5 78.1-95.4 93-131.1c3.3-7.9 3.3-16.7 0-24.6c-14.9-35.7-46.2-87.7-93-131.1C433.5 68.8 368.8 32 288 32zM144 256a144 144 0 1 1 288 0 144 144 0 1 1 -288 0zm144-64c0 35.3-28.7 64-64 64c-7.1 0-13.9-1.2-20.3-3.3c-5.5-1.8-11.9 1.6-11.7 7.4c.3 6.9 1.3 13.8 3.2 20.7c13.7 51.2 66.4 81.6 117.6 67.9s81.6-66.4 67.9-117.6c-11.1-41.5-47.8-69.4-88.6-71.1c-5.8-.2-9.2 6.1-7.4 11.7c2.1 6.4 3.3 13.2 3.3 20.3z" />
                                </svg>
                            </span>
                        </div>
                    </div>
                </div>
                <div class="form-control">
                    <label for="email">Email <span style="color: red;">*</span></label>
                    <input type="email" id="email" name="email" required pattern=".+@.+\..+"
                        title="Please enter a valid email address." autocomplete="on" />
                    <span class="error-message" style="color: red; display: none;">Please enter a valid email
                        address.</span>
                </div>
                <div class="form-control">
                    <label for="image">Student ID Card Image <span style="color: red;">*</span></label>
                    <input type="file" id="image" name="image" accept="image/*"required />
                </div>
                <!-- <div class="form-control">
                    <label for="start_university">Start University Date (Month and Year) <span
                            style="color: red;">*</span></label>
                    <input type="date" id="start_university" name="start_university" required />
                </div>
                <div class="form-control">
                    <label for="finish_university">Finish University Date (Month and Year) <span
                            style="color: red;">*</span></label>
                    <input type="date" id="finish_university" name="finish_university" required />
                </div> -->
            </div>

            <!-- STEP 3 EDUCATION INFORMATION -->
            <div class="step">
                <h3>Education Information</h3>
                <div class="row">
                    <div class="form-control">
                        <div class="select2-container">
                            <label for="edu_level">Education Level</label>
                            <select id="edu_level" name="edu_level">
                                <option selected disabled value="">--Select your education level--</option>
                                <?php foreach ($education as $edu_level): ?>
                                    <option value="<?php echo $edu_level['edu_id']; ?>">
                                        <?php echo $edu_level['edu_name']; ?>
                                    </option>
                                <?php endforeach; ?>
                            </select>
                        </div>
                    </div>
                </div>
                <div class="row">
                    <div class="form-control">
                        <div class="select2-container">
                            <label for="university">Universities</label>
                            <select id="university" name="university" onchange="toggleCustomUniversityInput()">
                                <option selected disabled value="">--Select your university--</option>
                                <?php foreach ($universities as $university): ?>
                                    <option value="<?php echo $university['uni_id']; ?>">
                                        <?php echo $university['uni_name']; ?>
                                    </option>
                                <?php endforeach; ?>
                                <option value="other">Other</option>
                            </select>
                            <input type="text" id="custom_universities" name="custom_universities"
                                placeholder="Enter your university" style="display:none;">
                        </div>
                    </div>
                    <div class="form-control">
                        <div class="select2-container">
                            <label for="course">Course</label>
                            <select id="course" name="course" onchange="toggleCustomCourseInput()">
                                <option selected disabled value="">--Select your course--</option>
                                <?php foreach ($courses as $course): ?>
                                    <option value="<?php echo $course['course_id']; ?>">
                                        <?php echo $course['course_name']; ?>
                                    </option>
                                <?php endforeach; ?>
                                <option value="other">Other</option>
                            </select>
                            <input type="text" id="custom_courses" name="custom_courses" placeholder="Enter your course"
                                style="display:none;">
                        </div>
                    </div>
                </div>
                <div class="form-control">
                    <div class="social-media-container">
                        <h3>Social Media Link</h3>
                        <label for="facebook">Facebook Page</label>
                        <input type="url" id="facebook" name="facebook" placeholder="http://www.facebook.com/yourname">
                        <label for="instagram">Instagram Profile</label>
                        <input type="url" id="instagram" name="instagram"
                            placeholder="https://www.instagram.com/yourname">
                        <label for="youtube">YouTube Channel</label>
                        <input type="url" id="youtube" name="youtube"
                            placeholder="http://www.youtube.com/user/yourname">
                    </div>
                </div>
            </div>
            <!-- STEP 4 STUDENT INTEREST -->
            <div class="step">
                <h3>Student Interest</h3>
                <div class="form-control">
                    <label>Interest (Select at least 2): <span style="color: red;">*</span></label>
                    <div id="interest-checkboxes">
                        <label class="checkbox-label"><input type="checkbox" name="interest[]"
                                value="cook">Cooking</label>
                        <label class="checkbox-label"><input type="checkbox" name="interest[]"
                                value="read">Reading</label>
                        <label class="checkbox-label"><input type="checkbox" name="interest[]"
                                value="photo">Photography</label>
                        <label class="checkbox-label"><input type="checkbox" name="interest[]"
                                value="dance">Dance</label>
                        <label class="checkbox-label"><input type="checkbox" name="interest[]"
                                value="hiking">Hiking</label>
                        <label class="checkbox-label"><input type="checkbox" name="interest[]"
                                value="travel">Travelling</label>
                        <label class="checkbox-label"><input type="checkbox" name="interest[]" value="games">Video
                            Games</label>
                        <label class="checkbox-label"><input type="checkbox" name="interest[]"
                                value="sports">Sports</label>
                        <label class="checkbox-label"><input type="checkbox" name="interest[]"
                                value="draw">Drawing</label>
                        <label class="checkbox-label"><input type="checkbox" name="interest[]"
                                value="blog">Blogging</label>
                        <label class="checkbox-label"><input type="checkbox" name="interest[]" value="other"
                                id="other-interest-checkbox">Other</label>
                        <input type="text" id="other-interest" name="other-interest" placeholder="Please state it"
                            style="display:none;">
                    </div>
                    <span class="error-message" style="color: red; display: none;">Please select at least 2
                        interests.</span>
                </div>
            </div>
        </div>
        <!-- /.steps-container -->

        <!-- .controls -->
        <div class="controls">
            <button type="button" class="prev-btn">Prev</button>
            <button type="button " class="next-btn">Next</button>
            <button type="submit" class="submit-btn">Submit</button>
        </div>
        <!-- /.controls -->
    </form>

    <script>
        $(document).ready(function () {
            // Initialize Select2 for the select elements
            const selectElements = ['#nationality', '#country', '#university', '#course', '#edu_level'];
            selectElements.forEach(selector => {
                $(selector).select2({
                    placeholder: `--Select your ${selector.slice(1)}--`,
                    allowClear: true,
                    width: 'resolve'
                });
            });

            // Handle "Other" interest input visibility
            $('#other-interest-checkbox').change(function () {
                $('#other-interest').toggle(this.checked).val(this.checked ? '' : '');
            });
        });
    </script>

</body>

</html>

<?php
// Save the output to the cache file
file_put_contents($cache_file, ob_get_contents());
// End output buffering and flush the output
ob_end_flush();
?>