const form = document.querySelector(".form-wizard");
const progress = form.querySelector(".progress");
const stepsContainer = form.querySelector(".steps-container");
const steps = form.querySelectorAll(".step");
const stepIndicators = form.querySelectorAll(".progress-container li");
const prevButton = form.querySelector(".prev-btn");
const nextButton = form.querySelector(".next-btn");
const submitButton = form.querySelector(".submit-btn");

document.documentElement.style.setProperty("--steps", stepIndicators.length);

let currentStep = 0;

const updateProgress = () => {
    const width = currentStep / (steps.length - 1);
    progress.style.transform = `scaleX(${width})`;

    requestAnimationFrame(() => {
        stepsContainer.style.height = `${steps[currentStep].offsetHeight}px`;
    });

    stepIndicators.forEach((indicator, index) => {
        indicator.classList.toggle("current", currentStep === index);
        indicator.classList.toggle("done", currentStep > index);
    });

    steps.forEach((step, index) => {
        const percentage = document.documentElement.dir === "rtl" ? 100 : -100;
        step.style.transform = `translateX(${currentStep * percentage}%)`;
        step.classList.toggle("current", currentStep === index);
    });

    updateButtons();
};

const updateButtons = () => {
    prevButton.hidden = currentStep === 0;
    nextButton.hidden = currentStep >= steps.length - 1;
    submitButton.hidden = !nextButton.hidden;
};

const isValidStep = () => {
    const fields = steps[currentStep].querySelectorAll("input, textarea, select");
    const password = document.getElementById("password").value;
    const confirmPassword = document.getElementById("confirm-password").value;

    if (currentStep === 1) {
        if (password !== confirmPassword) {
            alert("Passwords do not match!");
            return false;
        }

        // Check password validity
        const passwordPattern = /^(?=.*[a-z])(?=.*[A-Z])(?=.*\d)(?=.*[!@#$%^&*])[A-Za-z\d!@#$%^&*]{8,}$/;
        if (!passwordPattern.test(password)) {
            alert("Password must be at least 8 characters long and contain at least one uppercase letter, one lowercase letter, one number, and one special character.");
            return false;
        }
    }

    let isValid = true;
    [...fields].forEach(field => {
        if (!field.reportValidity()) {
            isValid = false;
            return; // Exit the loop early if a field is invalid
        }

        // Explicit email validation for email fields:
        if (field.type === 'email') {
            const emailRegex = /^[^\s@]+@[^\s@]+\.[^\s@]+$/; // Updated regex
            if (!emailRegex.test(field.value)) {
                alert("Invalid email address."); // Or provide more specific feedback
                field.setCustomValidity("Invalid email address."); // Show error message in the UI
                isValid = false;
            } else {
                field.setCustomValidity(""); // Clear the error message
            }
        }
    });

    requestAnimationFrame(() => {
        stepsContainer.style.height = `${steps[currentStep].offsetHeight}px`;
    });

    return isValid;
};

document.addEventListener('DOMContentLoaded', function () {
    const togglePassword = document.getElementById('toggle-password');
    const toggleConfirmPassword = document.getElementById('toggle-confirm-password');
    const passwordField = document.getElementById('password');
    const confirmPasswordField = document.getElementById('confirm-password');

    togglePassword.addEventListener('click', function () {
        const type = passwordField.getAttribute('type') === 'password' ? 'text' : 'password';
        passwordField.setAttribute('type', type);
        this.querySelector('svg').classList.toggle('visible');
    });

    toggleConfirmPassword.addEventListener('click', function () {
        const type = confirmPasswordField.getAttribute('type') === 'password' ? 'text' : 'password';
        confirmPasswordField.setAttribute('type', type);
        this.querySelector('svg').classList.toggle('visible');
    });
});

document.addEventListener('DOMContentLoaded', function () {
    const studentIdInput = document.getElementById('image');

    studentIdInput.addEventListener('change', function () {
        const file = studentIdInput.files[0];
        if (!file) {
            studentIdInput.setCustomValidity('Please upload your student ID card image.');
        } else if (!file.type.startsWith('image/')) {
            studentIdInput.setCustomValidity('Please upload a valid image file.');
            alert('Please upload a valid image file.');
        } else {
            studentIdInput.setCustomValidity('');
        }
    });

});

const inputs = form.querySelectorAll("input, textarea, select");
inputs.forEach((input) =>
    input.addEventListener("focus", (e) => {
        const focusedElement = e.target;
        const focusedStep = [...steps].findIndex((step) =>
            step.contains(focusedElement)
        );

        if (focusedStep !== -1 && focusedStep !== currentStep) {
            if (!isValidStep()) return;

            currentStep = focusedStep;
            updateProgress();
        }

        requestAnimationFrame(() => {
            stepsContainer.scrollTop = 0;
            stepsContainer.scrollLeft = 0;
        });
    })
);

form.addEventListener("submit", (e) => {
    e.preventDefault();

    if (!form.checkValidity()) return;

    const checkedInterests = Array.from(form.querySelectorAll('input[name="interest[]"]:checked'));

    if (checkedInterests.length < 2) {
        alert("Please select at least 2 interests.");
        return; // Prevent form submission
    }

    // Check if "Other" is selected for university and course
    const universitySelect = document.getElementById('university');
    const customUniversityInput = document.getElementById('custom_universities');
    const courseSelect = document.getElementById('course');
    const customCourseInput = document.getElementById('custom_courses');
    const studentIdInput = document.getElementById('image');

    // Create FormData object
    const formData = new FormData(form);
    formData.append('image', studentIdInput.files[0]);

    // Validate custom university input if "Other" is selected
    if (universitySelect.value === 'other') {
        if (customUniversityInput.value.trim() === '') {
            alert("Please enter your custom university.");
            return; // Prevent form submission
        }
        formData.set('universities', customUniversityInput.value); // Set custom university value
    } else {
        formData.set('universities', universitySelect.value); // Set selected university id
    }

    // Validate custom course input if "Other" is selected
    if (courseSelect.value === 'other') {
        if (customCourseInput.value.trim() === '') {
            alert("Please enter your custom course.");
            return; // Prevent form submission
        }
        formData.set('courses', customCourseInput.value); // Set custom course value
    } else {
        formData.set('courses', courseSelect.value); // Set selected course id
    }



    // Log form data for debugging
    //console.log("Form Data:", Object.fromEntries(formData.entries()));

    submitButton.disabled = true; // Disable the submit button to prevent multiple submissions
    submitButton.textContent = "Submitting...";

    fetch("../backend/register.php", {
        method: "POST",
        body: formData
    })
        .then(response => response.json()) // Parse the JSON response
        .then(data => {
            if (data.status === "success") {
                alert(data.message); // Show success message
                const completedMessage = form.querySelector(".completed");
                completedMessage.hidden = false;

                setTimeout(() => {
                    window.location.href = "../frontend/login_form.php";
                }, 3000);
            } else {
                alert(data.message); // Show error message
            }
        })
        .catch(error => {
            console.error("Error:", error);
            alert("There was an error submitting the form. Please try again.");
        })
        .finally(() => {
            submitButton.disabled = false; // Re-enable the submit button
            submitButton.textContent = "Submit"; // Reset button text
        });
});

function toggleCountryField() {
    const nationalitySelect = document.getElementById('nationality');
    const countrySelect = document.getElementById('country');

    if (nationalitySelect.value === 'non-malaysian') {
        countrySelect.parentElement.style.display = 'block';
        countrySelect.required = true;
    } else {
        countrySelect.parentElement.style.display = 'none';
        countrySelect.required = false;
        countrySelect.value = '';
    }

    // Adjust the height of the step container dynamically
    setTimeout(() => {
        stepsContainer.style.height = `${steps[currentStep].offsetHeight}px`;
    }, 50);
}

function toggleCustomUniversityInput() {
    const universitySelect = document.getElementById('university');
    const customUniversityInput = document.getElementById('custom_universities');
    const uniIdInput = document.getElementById('uni_id');

    if (universitySelect.value === 'other') {
        customUniversityInput.style.display = 'block';
        customUniversityInput.required = true; // Make it required
        uniIdInput.value = ''; // Clear uni_id
    } else {
        customUniversityInput.style.display = 'none';
        customUniversityInput.required = false; // Make it not required
        customUniversityInput.value = ''; // Clear the input
        uniIdInput.value = universitySelect.value; // Set uni_id to selected value
    }
    // Adjust the height of the step container dynamically
    setTimeout(() => {
        stepsContainer.style.height = `${steps[currentStep].offsetHeight}px`;
    }, 50);
}

function toggleCustomCourseInput() {
    const courseSelect = document.getElementById('course');
    const customCourseInput = document.getElementById('custom_courses');
    const courseIdInput = document.getElementById('course_id');

    if (courseSelect.value === 'other') {
        customCourseInput.style.display = 'block';
        customCourseInput.required = true; // Make it required
        courseIdInput.value = ''; // Clear course_id
    } else {
        customCourseInput.style.display = 'none';
        customCourseInput.required = false; // Make it not required
        customCourseInput.value = ''; // Clear the input
        courseIdInput.value = courseSelect.value; // Set course_id to selected value
    }
    // Adjust the height of the step container dynamically
    setTimeout(() => {
        stepsContainer.style.height = `${steps[currentStep].offsetHeight}px`;
    }, 50);
}

prevButton.addEventListener("click", (e) => {
    e.preventDefault();

    if (currentStep > 0) {
        currentStep--;
        updateProgress();
    }
});

nextButton.addEventListener("click", (e) => {
    e.preventDefault();

    if (!isValidStep()) return;

    if (currentStep < steps.length - 1) {
        currentStep++;
        updateProgress();
    }
});

updateProgress();

window.addEventListener("load", () => {
    setTimeout(() => {
        document.body.offsetHeight;
    }, 50);
});