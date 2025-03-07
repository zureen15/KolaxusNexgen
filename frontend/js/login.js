function showToast(message, type = 'success') {
    const toast = document.getElementById('toast');
    toast.textContent = message;
    toast.className = `toast ${type}`;
    toast.style.display = 'block';

    setTimeout(() => {
        toast.style.display = 'none';
    }, 3000);
}

function handleSubmit(event) {
    event.preventDefault();

    const email = document.getElementById('email').value;
    const password = document.getElementById('password').value;
    const submitButton = document.getElementById('submitButton');
    const spinner = document.getElementById('spinner');
    const buttonText = document.getElementById('buttonText');
    const errorMessage = document.getElementById('errorMessage');
    // const successMessage = document.getElementById('successMessage');

    // Reset error message
    errorMessage.style.display = 'none';

    // Show loading state
    submitButton.disabled = true;
    spinner.style.display = 'inline-block';
    buttonText.textContent = 'Signing in...';

    // Create FormData object to send data
    const formData = new FormData(document.getElementById('loginForm'));

    // Send the request to the backend
    fetch('../backend/login.php', {
        method: 'POST',
        body: formData
    })
        .then(response => response.json())
        .then(data => {
            // Handle the response
            if (data.error) {
                // errorMessage.textContent = data.error;
                errorMessage.style.display = 'block';
                showToast(data.error, 'error');
            } else if (data.redirect) {
                // Redirect to the appropriate dashboard
                window.location.href = data.redirect;
            }

            // Reset button state
            submitButton.disabled = false;
            spinner.style.display = 'none';
            buttonText.textContent = 'Login';
        })
        .catch(error => {
            console.error('Error:', error);
            showToast('An unexpected error occurred. Please try again.', 'error');
            submitButton.disabled = false;
            spinner.style.display = 'none';
            buttonText.textContent = 'Login';
        });
}

function handleForgot(event) {
    event.preventDefault();

    const email = document.getElementById('forgotEmail').value;
    const submitButton = document.getElementById('forgotSubmitButton');
    const spinner = document.getElementById('forgotSpinner');
    const buttonText = document.getElementById('forgotButtonText');
    const errorMessage = document.getElementById('forgotErrorMessage');
    const successMessage = document.getElementById('forgotSuccessMessage');

    // Reset messages
    errorMessage.style.display = 'none';
    successMessage.style.display = 'none';

    // Show loading state
    submitButton.disabled = true;
    spinner.style.display = 'inline-block';
    buttonText.textContent = 'Sending...';

    // Create FormData object to send data
    const formData = new FormData(document.getElementById('forgotForm'));

    // Send the request to the backend
    fetch('../backend/forgot_password.php', {
        method: 'POST',
        body: formData
    })
        .then(response => response.json())
        .then(data => {
            // Handle the response
            if (data.error) {
                errorMessage.textContent = data.error;
                errorMessage.style.display = 'block';
                showToast(data.error, 'error');
            } else if (data.success) {
                successMessage.textContent = data.success;
                successMessage.style.display = 'block';
                showToast(data.success, 'success');
            }

            // Reset button state
            submitButton.disabled = false;
            spinner.style.display = 'none';
            buttonText.textContent = 'Send';
        })
        .catch(error => {
            console.error('Error:', error);
            showToast('An unexpected error occurred. Please try again.', 'error');
            submitButton.disabled = false;
            spinner.style.display = 'none';
            buttonText.textContent = 'Send';
        });
}

function handleReset(event) {
    event.preventDefault();

    const password = document.getElementById('password').value;
    const confirmPassword = document.getElementById('confirm_password').value;
    const submitButton = document.getElementById('submitButton');
    const spinner = document.getElementById('spinner');
    const buttonText = document.getElementById('buttonText');
    const errorMessage = document.getElementById('errorMessage');
    const successMessage = document.getElementById('successMessage');

    // Reset messages
    errorMessage.style.display = 'none';
    successMessage.style.display = 'none';

    // Validate passwords
    if (password !== confirmPassword) {
        errorMessage.textContent = 'Passwords do not match.';
        errorMessage.style.display = 'block';
        return;
    }

    // Show loading state
    submitButton.disabled = true;
    spinner.style.display = 'inline-block';
    buttonText.textContent = 'Resetting...';

    // Create FormData object to send data
    const formData = new FormData(document.getElementById('resetPasswordForm'));

    // Send the request to the backend
    fetch('../backend/reset_password.php', {
        method: 'POST',
        body: formData
    })
        .then(response => response.json())
        .then(data => {
            // Handle the response
            if (data.error) {
                errorMessage.textContent = data.error;
                errorMessage.style.display = 'block';
                showToast(data.error, 'error');
            } else if (data.success) {
                successMessage.textContent = data.success;
                successMessage.style.display = 'block';
                showToast(data.success, 'success');
            }

            // Reset button state
            submitButton.disabled = false;
            spinner.style.display = 'none';
            buttonText.textContent = 'Reset Password';
        })
        .catch(error => {
            console.error('Error:', error);
            showToast('An unexpected error occurred. Please try again.', 'error');
            submitButton.disabled = false;
            spinner.style.display = 'none';
            buttonText.textContent = 'Reset Password';
        });
}


// Password visibility toggle
const passwordInput = document.getElementById('password');
const passwordToggle = document.getElementById('passwordToggle');

passwordToggle.addEventListener('click', function () {
    if (passwordInput.type === 'password') {
        passwordInput.type = 'text';
        passwordToggle.classList.remove('fa-eye');
        passwordToggle.classList.add('fa-eye-slash');
    } else {
        passwordInput.type = 'password';
        passwordToggle.classList.remove('fa-eye-slash');
        passwordToggle.classList.add('fa-eye');
    }
});