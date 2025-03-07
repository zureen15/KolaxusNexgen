<?php
require '../backend/user/dashboard.php';

// Set the timezone to Malaysia
date_default_timezone_set('Asia/Kuala_Lumpur');
// Define cache file path and cache duration (e.g., 10 minutes)
$cache_dir = '../cache/';
$cache_file = $cache_dir . 'dashboard_cache.html';
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
    <meta charset="UTF-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1.0, user-scalable=no">
    <link href="https://unpkg.com/boxicons@2.0.9/css/boxicons.min.css" rel="stylesheet">
    <link href="https://stackpath.bootstrapcdn.com/bootstrap/4.5.2/css/bootstrap.min.css" rel="stylesheet">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0-beta3/css/all.min.css">
    <link rel="stylesheet" href="../frontend/css/side_user.css?v=1">
    <link rel="stylesheet" href="../frontend/css/dashboard.css?v=1">
    <title>My Homepage</title>
</head>

<body>

    <?php include '../frontend/sidebar/side_user.php'; ?>

    <?php include '../frontend/sidebar/header_user.php'; ?>
    <div id="main-content" class="main-content">
        <div class="content-container">
            <!-- Enhanced Welcome Section -->
            <div class="welcome-section">
                <div class="welcome-message">
                    <div class="welcome-icon">
                        <?php
                        $hour = date('H');
                        if ($hour >= 5 && $hour < 12) {
                            echo '<i class="fas fa-sun"></i>';
                        } elseif ($hour >= 12 && $hour < 18) {
                            echo '<i class="fas fa-cloud-sun"></i>';
                        } else {
                            echo '<i class="fas fa-moon"></i>';
                        }
                        ?>
                    </div>
                    <div class="welcome-text">
                        <h1>
                            <?php
                            if ($hour >= 1 && $hour < 12) {
                                echo 'Good Morning, ';
                            } elseif ($hour >= 12 && $hour < 18) {
                                echo 'Good Afternoon, ';
                            } else {
                                echo 'Good Evening, ';
                            }
                            echo $user_name . '!';
                            ?>
                        </h1>
                        <div class="welcome-subtext">Welcome to my homepage. Here's what's happening today.
                        </div>
                        <div class="welcome-date"><?php echo date('l, F j, Y'); ?></div>
                    </div>
                </div>
            </div>

            <!-- Vouchers & Discounts Page -->
            <div class="row">
                <div class="col-8">
                    <div class="slide-container">
                        <?php
                        // Check if there are loyalty programs and display them in the slider
                        if ($loyalty_result->num_rows > 0) {
                            $loyalty_result->data_seek(0); // Reset result pointer
                            while ($row = $loyalty_result->fetch_assoc()) {
                                $loyalty_type = isset($row['loyalty_type']) ? htmlspecialchars($row['loyalty_type'], ENT_QUOTES, 'UTF-8') : 'Standard';
                                $program_description = isset($row['challenge_description']) ? htmlspecialchars($row['challenge_description'], ENT_QUOTES, 'UTF-8') : 'Join our loyalty program for exclusive benefits and rewards.';

                                echo '<div class="slide fade">';
                                echo '<div class="loyalty-type">' . $loyalty_type . '</div>';
                                echo '<img src="../Admin/upload/' . htmlspecialchars($row['image'], ENT_QUOTES, 'UTF-8') . '" alt="' . htmlspecialchars($row['program_name'], ENT_QUOTES, 'UTF-8') . '">';
                                echo '<div class="program-info">';
                                echo '<div class="program-name">' . htmlspecialchars($row['program_name'], ENT_QUOTES, 'UTF-8') . '</div>';
                                echo '<div class="program-description">' . $program_description . '</div>';
                                echo '</div>';
                                echo '</div>';
                            }
                        } else {
                            echo '<div class="slide fade">';
                            echo '<div class="loyalty-type">Standard</div>';
                            echo '<img src="../frontend/img/default_image.jpg" alt="No Loyalty Program Available">';
                            echo '<div class="program-info">';
                            echo '<div class="program-name">No Loyalty Program Available</div>';
                            echo '<div class="program-description">Check back later for exciting loyalty programs and rewards.</div>';
                            echo '</div>';
                            echo '</div>';
                        }
                        ?>
                        <a href="#" class="prev" title="Previous">&#10094;</a>
                        <a href="#" class="next" title="Next">&#10095;</a>
                        <div class="progress-bar"></div>
                    </div>
                    <div class="dots-container">
                        <?php
                        // Create dots for each slide
                        if ($loyalty_result->num_rows > 0) {
                            $loyalty_result->data_seek(0); // Reset result pointer
                            $i = 0;
                            while ($row = $loyalty_result->fetch_assoc()) {
                                echo '<span class="dot" data-index="' . $i . '"></span>';
                                $i++;
                            }
                        } else {
                            echo '<span class="dot active" data-index="0"></span>';
                        }
                        ?>
                    </div>
                </div>
                <div class="col-4">
                    <div class="stats-container">
                        <div class="stat-item">
                            <span class="stat-label">Joined Hackathon</span>
                            <span class="stat-value"><?php echo $joined_hackathon_count; ?></span>
                        </div>
                        <div class="stat-item">
                            <span class="stat-label">Joined Challenge</span>
                            <span class="stat-value"><?php echo $joined_challenge_count; ?></span>
                        </div>
                        <div class="stat-item">
                            <span class="stat-label">Completed Hackathon</span>
                            <span class="stat-value">0</span>
                        </div>
                        <div class="stat-item">
                            <span class="stat-label">Completed Challenge</span>
                            <span class="stat-value">0</span>
                        </div>
                        <!-- <div class="stat-item">
                            <span class="stat-label">My Vouchers</span>
                            <span class="stat-value">0</span>
                        </div> -->
                    </div>
                </div>
            </div>

            <!-- Hackathon Page -->
            <h1 class="pull-left">Upcoming Hackathons</h1>
            <div class="container-fluid">
                <div class="container mt-5 mb-3">
                    <div class="row">
                        <?php
                        // Check if there are hackathons and display them as cards
                        if ($hackathon_result->num_rows > 0) {
                            while ($row = $hackathon_result->fetch_assoc()) {
                                $hackathon_name = htmlspecialchars($row['hackathon_name'], ENT_QUOTES, 'UTF-8');
                                $prizes = htmlspecialchars($row['prizes'], ENT_QUOTES, 'UTF-8');
                                $end_date = date("F j, Y (h:i A)", strtotime($row['end_date'])); // Format end date with AM/PM
                                $hackathon_type = isset($row['hackathon_type']) ? htmlspecialchars($row['hackathon_type'], ENT_QUOTES, 'UTF-8') : 'General';

                                // Split the images string into an array
                                $images = explode(',', $row['images']); // Use $row['images'] to get the correct value
                                $image_count = count($images);

                                // Determine image container class based on number of images
                                $image_container_class = 'single-image';
                                if ($image_count == 2) {
                                    $image_container_class = 'two-images';
                                } else if ($image_count >= 3) {
                                    $image_container_class = 'three-plus-images';
                                }

                                echo '<div class="col-md-4 mb-4">';
                                echo '<div class="card">';
                                echo '<div class="hackathon-type">' . $hackathon_type . '</div>';
                                echo '<div class="card-header">';
                                echo '<div class="c-details">';
                                echo '<h6>' . $hackathon_name . '</h6>';
                                echo '<span>Organized by Company</span>';
                                echo '</div>';
                                echo '<a href="read_hackathon.php?hackathon_id=' . htmlspecialchars($row['hackathon_id'], ENT_QUOTES, 'UTF-8') . '" class="btn">View</a>';
                                echo '</div>';
                                echo '<div class="image-container ' . $image_container_class . '">';

                                // Loop through each image and display it
                                foreach ($images as $img) {
                                    echo '<img src="../Hackathon/upload/' . htmlspecialchars(trim($img), ENT_QUOTES, 'UTF-8') . '" alt="' . $hackathon_name . '" class="img-fluid">';
                                }

                                echo '</div>'; // Close image-container
                                echo '<div class="card-body">';
                                echo '<div class="prizes-container">';
                                echo '<i class="fas fa-trophy"></i>';
                                echo '<div class="text1"><span class="prizes">Prizes: ' . $prizes . '</span></div>';
                                echo '</div>';
                                echo '<div class="date-container">';
                                echo '<i class="far fa-calendar-alt"></i>';
                                echo '<div class="text1"><span class="end-date">Ends on: ' . $end_date . '</span></div>';
                                echo '</div>';
                                echo '</div>'; // Close card-body
                                echo '</div>'; // Close card
                                echo '</div>'; // Close col-md-4
                            }
                        } else {
                            echo '<p>No upcoming hackathons available.</p>';
                        }
                        ?>
                    </div>
                </div>
            </div>
            <?php include '../frontend/sidebar/footer.html'; ?>
        </div>
    </div>

    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.0.0-beta1/dist/js/bootstrap.bundle.min.js"></script>
    <script src="https://cdnjs.cloudflare.com/ajax/libs/jquery/3.2.1/jquery.min.js"></script>
    <script src="../frontend/js/dashboard.js?v=1"></script>
    <script src="../frontend/js/side_user.js?v=1"></script>

</body>

</html>

<?php
// Save the output to the cache file
file_put_contents($cache_file, ob_get_contents());
// End output buffering and flush the output
ob_end_flush();
?>