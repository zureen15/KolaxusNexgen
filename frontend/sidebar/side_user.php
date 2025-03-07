<?php
$current_page = htmlspecialchars(basename($_SERVER['REQUEST_URI']), ENT_QUOTES, 'UTF-8');
?>
<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <link href='https://unpkg.com/boxicons@2.1.4/css/boxicons.min.css' rel='stylesheet'>
    <link rel="stylesheet" href="../frontend/css/side_user.css">
</head>

<body>
    <!-- Mobile Sidebar - Only visible on mobile devices -->
    <aside id="mobile-sidebar">
        <div class="sidebar-header">
            <img src="../frontend/sidebar/img/kolaxus.jpg" alt="Kolaxus Logo" class="logo">
            <button id="close-sidebar" aria-label="Close sidebar">
                <i class='bx bx-x'></i>
            </button>
        </div>

        <div class="sidebar-content">
            <ul class="nav-menu">
                <li class="<?= ($current_page == 'dashboard.php') ? 'active' : '' ?>">
                    <a href="../User/dashboard.php">
                        <i class='bx bxs-dashboard'></i>
                        <span>My Homepage</span>
                    </a>
                </li>
                <li class="<?= ($current_page == 'user_hackathon.php') ? 'active' : '' ?>">
                    <a href="../User/user_hackathon.php">
                        <i class='bx bxs-trophy'></i>
                        <span>My Hackathons</span>
                    </a>
                </li>
                <li class="<?= ($current_page == 'user_challenge.php') ? 'active' : '' ?>">
                    <a href="../User/user_challenge.php">
                        <i class='bx bxs-game'></i>
                        <span>My Challenges</span>
                    </a>
                </li>
                <li>
                    <a href="../frontend/logout.php">
                        <i class='bx bx-log-out'></i>
                        <span>Logout</span>
                    </a>
                </li>
            </ul>
        </div>
    </aside>

    <!-- Overlay for mobile sidebar -->
    <div id="sidebar-overlay"></div>
</body>

</html>