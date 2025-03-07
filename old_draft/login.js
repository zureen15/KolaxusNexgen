const login_btn = document.querySelector("#login-btn");
// const sign_up_btn = document.querySelector("#sign-up-btn");
const container = document.querySelector(".container");

document.addEventListener('DOMContentLoaded', function () {
    const togglePassword = document.getElementById('toggle-password');
    const passwordField = document.getElementById('password');

    togglePassword.addEventListener('click', function () {
        const type = passwordField.getAttribute('type') === 'password' ? 'text' : 'password';
        passwordField.setAttribute('type', type);
        this.querySelector('svg').classList.toggle('visible');
    });
});

function hideErrorMessage() {
    const errorMessage = document.getElementById('error-message');
    if (errorMessage) {
        setTimeout(() => {
            errorMessage.style.display = 'none';
        }, 5000);
    }
}
window.onload = hideErrorMessage;