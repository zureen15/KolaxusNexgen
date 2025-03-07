<?php
$user_image = isset($_SESSION['image']) ? $_SESSION['image'] : 'account.png';
$current_page = htmlspecialchars(basename($_SERVER['REQUEST_URI']), ENT_QUOTES, 'UTF-8');
?>
<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <link href='https://unpkg.com/boxicons@2.1.4/css/boxicons.min.css' rel='stylesheet'>
    <link rel="stylesheet" href="../frontend/css/side_user.css">
    <script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>
</head>

<body>
    <header id="main-header">
        <!-- Mobile Header View -->
        <div class="mobile-header">
            <button id="toggle-sidebar" aria-label="Toggle sidebar">
                <i class='bx bx-menu'></i>
            </button>

            <div class="logo-container">
                <img src="../frontend/sidebar/img/kolaxus.jpg" alt="Kolaxus Logo" class="logo">
            </div>

            <div class="mobile-profile" id="mobile-profile">
                <img src="../frontend/student_upload/<?php echo htmlspecialchars($user_image); ?>" alt="Student Card">
            </div>

            <!-- Mobile Profile Dropdown -->
            <div class="profile-dropdown" id="mobile-profile-dropdown">
                <div class="profile-header">
                    <img src="../frontend/student_upload/<?php echo htmlspecialchars($user_image); ?>"
                        alt="Student Card">
                    <div class="profile-info">
                        <h4><?php echo isset($_SESSION['name']) ? htmlspecialchars($_SESSION['name']) : 'User'; ?></h4>
                        <p><?php echo isset($_SESSION['email']) ? htmlspecialchars($_SESSION['email']) : 'user@example.com'; ?>
                        </p>
                    </div>
                </div>
                <div class="profile-actions">
                    <a href="#" class="profile-action mobile-view-profile">
                        <i class='bx bx-user'></i>
                        <span>View Profile</span>
                    </a>
                    <a href="#" class="profile-action">
                        <i class='bx bx-cog'></i>
                        <span>Settings</span>
                    </a>
                    <a href="../frontend/logout.php" class="profile-action logout">
                        <i class='bx bx-log-out'></i>
                        <span>Logout</span>
                    </a>
                </div>
            </div>
        </div>

        <!-- Desktop Header View -->
        <div class="desktop-header">
            <div class="logo-container">
                <img src="../frontend/sidebar/img/kolaxus.jpg" alt="Kolaxus Logo" class="logo">
            </div>

            <nav class="desktop-nav">
                <ul>
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
                </ul>
            </nav>

            <div class="header-actions">
                <div class="theme-toggle">
                    <input type="checkbox" id="theme-switch" hidden>
                    <label for="theme-switch">
                        <i class='bx bx-sun'></i>
                        <i class='bx bx-moon'></i>
                        <span class="slider"></span>
                    </label>
                </div>

                <div class="desktop-profile" id="desktop-profile">
                    <img src="../frontend/student_upload/<?php echo htmlspecialchars($user_image); ?>"
                        alt="Student Card">
                    <span class="profile-text">My Profile</span>
                    <i class='bx bx-chevron-down'></i>
                </div>

                <div class="profile-dropdown" id="desktop-profile-dropdown">
                    <div class="profile-header">
                        <img src="../frontend/student_upload/<?php echo htmlspecialchars($user_image); ?>"
                            alt="Student Card">
                        <div class="profile-info">
                            <h4><?php echo isset($_SESSION['name']) ? htmlspecialchars($_SESSION['name']) : 'User'; ?>
                            </h4>
                            <p><?php echo isset($_SESSION['email']) ? htmlspecialchars($_SESSION['email']) : 'user@example.com'; ?>
                            </p>
                        </div>
                    </div>
                    <div class="profile-actions">
                        <a href="#" class="profile-action desktop-view-profile">
                            <i class='bx bx-user'></i>
                            <span>View Profile</span>
                        </a>
                        <a href="#" class="profile-action">
                            <i class='bx bx-cog'></i>
                            <span>Settings</span>
                        </a>
                        <a href="../frontend/logout.php" class="profile-action logout">
                            <i class='bx bx-log-out'></i>
                            <span>Logout</span>
                        </a>
                    </div>
                </div>
            </div>
        </div>
    </header>

    <script>
        document.addEventListener('DOMContentLoaded', function () {
            // Mobile profile click handler
            const mobileProfile = document.getElementById('mobile-profile');
            const mobileProfileDropdown = document.getElementById('mobile-profile-dropdown');

            if (mobileProfile && mobileProfileDropdown) {
                mobileProfile.addEventListener('click', function (e) {
                    e.stopPropagation();
                    mobileProfileDropdown.classList.toggle('active');

                    // Close desktop dropdown if open
                    const desktopProfileDropdown = document.getElementById('desktop-profile-dropdown');
                    if (desktopProfileDropdown && desktopProfileDropdown.classList.contains('active')) {
                        desktopProfileDropdown.classList.remove('active');
                    }
                });
            }

            // Mobile View Profile button click handler
            const mobileViewProfileBtn = document.querySelector('.mobile-view-profile');
            if (mobileViewProfileBtn) {
                mobileViewProfileBtn.addEventListener('click', function (e) {
                    e.preventDefault();
                    e.stopPropagation();

                    // Hide dropdown first
                    if (mobileProfileDropdown) {
                        mobileProfileDropdown.classList.remove('active');
                    }

                    // Show SweetAlert
                    Swal.fire({
                        title: 'Student Card',
                        imageUrl: '../frontend/student_upload/<?php echo htmlspecialchars($user_image); ?>',
                        imageAlt: 'Profile Image',
                        confirmButtonText: 'Close',
                        customClass: {
                            popup: 'swal2-popup',
                            image: 'swal2-image'
                        }
                    });
                });
            }

            // Desktop profile dropdown toggle
            const desktopProfile = document.getElementById('desktop-profile');
            const desktopProfileDropdown = document.getElementById('desktop-profile-dropdown');

            if (desktopProfile && desktopProfileDropdown) {
                desktopProfile.addEventListener('click', function (e) {
                    e.stopPropagation();
                    desktopProfileDropdown.classList.toggle('active');

                    // Close mobile dropdown if open
                    if (mobileProfileDropdown && mobileProfileDropdown.classList.contains('active')) {
                        mobileProfileDropdown.classList.remove('active');
                    }
                });
            }

            // Desktop View Profile button click handler
            const desktopViewProfileBtn = document.querySelector('.desktop-view-profile');
            if (desktopViewProfileBtn) {
                desktopViewProfileBtn.addEventListener('click', function (e) {
                    e.preventDefault();
                    e.stopPropagation();

                    // Hide dropdown first
                    if (desktopProfileDropdown) {
                        desktopProfileDropdown.classList.remove('active');
                    }

                    // Show SweetAlert
                    Swal.fire({
                        title: 'Student Card',
                        imageUrl: '../frontend/student_upload/<?php echo htmlspecialchars($user_image); ?>',
                        imageAlt: 'Profile Image',
                        confirmButtonText: 'Close',
                        customClass: {
                            popup: 'swal2-popup',
                            image: 'swal2-image'
                        }
                    });
                });
            }

            // Close dropdowns when clicking outside
            document.addEventListener('click', function () {
                if (desktopProfileDropdown) {
                    desktopProfileDropdown.classList.remove('active');
                }
                if (mobileProfileDropdown) {
                    mobileProfileDropdown.classList.remove('active');
                }
            });

            // Prevent closing when clicking inside dropdowns
            const profileDropdowns = document.querySelectorAll('.profile-dropdown');
            profileDropdowns.forEach(dropdown => {
                dropdown.addEventListener('click', function (e) {
                    e.stopPropagation();
                });
            });

            // Handle window resize - close dropdowns when resizing
            let resizeTimer;
            window.addEventListener('resize', function () {
                clearTimeout(resizeTimer);
                resizeTimer = setTimeout(function () {
                    // Close all dropdowns on resize
                    if (desktopProfileDropdown) {
                        desktopProfileDropdown.classList.remove('active');
                    }
                    if (mobileProfileDropdown) {
                        mobileProfileDropdown.classList.remove('active');
                    }
                }, 250);
            });
        });
    </script>
</body>

</html>