<?php
require '../backend/user/user_hackathon.php';
?>

<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1.0, user-scalable=no">
    <link href="https://unpkg.com/boxicons@2.0.9/css/boxicons.min.css" rel="stylesheet">
    <link href="https://stackpath.bootstrapcdn.com/bootstrap/4.5.2/css/bootstrap.min.css" rel="stylesheet">
    <link rel="stylesheet" href="../frontend/css/side_user.css?v=1">
    <!-- <link rel="stylesheet" href="../adminhub-master/style.css"> -->
    <link rel="stylesheet" href="../frontend/css/dashboard.css?v=1">
    <title>My Hackathon</title>
</head>

<body>

    <?php include '../frontend/sidebar/side_user.php'; ?>


    <?php include '../frontend/sidebar/header_user.php'; ?>

    <div id="main-content" class="main-content">
        <div class="content-container">
            <!-- Hackathon Page -->
            <!-- <h1 class="pull-left">Joined Hackathons</h1> -->
            <div class="nav-buttons">
                <button id="available-button" class="nav-button primary-button" onclick="showContent('available')">
                    Hackathon Available </button>
                <button id="joined-button" class="nav-button secondary-button" onclick="showContent('joined')">
                    Joined Hackathon</button>
            </div>
            <div class="container-fluid" id="available">
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
            <div class="container-fluid" id="joined" style="display: none;">
                <div class="container mt-5 mb-3">
                    <div class="row">
                        <?php
                        // Check if there are joined hackathons and display them as cards
                        if ($join_hackathon_result->num_rows > 0) {
                            while ($row = $join_hackathon_result->fetch_assoc()) {
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
                            echo '<p>No joined hackathons available.</p>';
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
    <script src="../frontend/js/side_user.js?v=1"></script>
    <script>
        function showContent(section) {
            document.getElementById('available').style.display = section === 'available' ? 'block' : 'none';
            document.getElementById('joined').style.display = section === 'joined' ? 'block' : 'none';
            document.getElementById('available-button').classList.toggle('active', section === 'available');
            document.getElementById('joined-button').classList.toggle('active', section === 'joined');
        }

        // Show available challenges by default
        showContent('available');
    </script>

</body>

</html>