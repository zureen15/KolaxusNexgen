<?php
// session_start();
require '../backend/login.php';
?>

<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Kolaxus NexGen - Login</title>
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css">
    <link rel="stylesheet" href="../frontend/css/login.css?v=1">
</head>

<body>
    <div class="container">
        <div class="content-section">
            <div class="image-container">
                <img src="../frontend/img/student.jpg" alt="Students collaborating">
                <div class="overlay"></div>
            </div>
            <div class="description">
                <p>Kolaxus neXgen is a cutting-edge initiative designed to empower university and college students by
                    unleashing their full potential for the future. Through global exchanges, collaborative projects,
                    and immersive learning experiences, neXgen prepares students to thrive in a rapidly evolving world.
                    By engaging with diverse cultures and industries, participants gain the adaptability, leadership
                    skills, and innovative mindset needed to excel in an interconnected global environment. With neXgen,
                    students will break through personal and professional barriers, equipping themselves to become the
                    future leaders of tomorrow.</p>
            </div>
        </div>

        <div class="form-section">
            <div class="login-container">
                <div class="login-header">
                    <h2>Welcome back</h2>
                    <p>Login to your account to continue</p>
                </div>

                <div class="error" id="errorMessage"></div>

                <form id="loginForm" action="login_form.php" method="POST" onsubmit="handleSubmit(event)">
                    <input type="hidden" name="csrf_token" value="<?php echo $_SESSION['csrf_token']; ?>">
                    <div class="form-group">
                        <label for="email">Email address</label>
                        <div class="input-group">
                            <i class="fas fa-user icon-left" aria-label="User icon"></i>
                            <input type="email" id="email" name="email" required placeholder="Enter your email">
                        </div>
                    </div>

                    <div class="form-group">
                        <div class="password-header">
                            <label for="password">Password</label>
                            <a href="forgot_password_form.php" class="forgot-password">Forgot password?</a>
                        </div>
                        <div class="input-group">
                            <i class="fas fa-lock icon-left" aria-label="Lock icon"></i>
                            <input type="password" id="password" name="password" required
                                placeholder="Enter your password">
                            <i class="fas fa-eye password-toggle" id="passwordToggle"
                                aria-label="Toggle password visibility" style="cursor: pointer;"></i>
                        </div>
                    </div>

                    <button type="submit" class="submit-btn" id="submitButton">
                        <span class="spinner" id="spinner"></span>
                        <span id="buttonText">Login</span>
                    </button>

                    <div class="signup-prompt">
                        Don't have an account?<a href="register.php">Sign up</a>
                    </div>
                </form>
            </div>
        </div>
    </div>

    <div class="toast" id="toast"></div>

    <script src="../frontend/js/login.js?v=1"></script>
</body>

</html>