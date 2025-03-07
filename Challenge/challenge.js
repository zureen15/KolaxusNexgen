document.addEventListener("DOMContentLoaded", function () {
    // Function to initialize Flatpickr
    function initializeFlatpickr(selector) {
        flatpickr(selector, {
            enableTime: true,
            dateFormat: "Y-m-d H:i", // Format to include date and time
            altInput: true,
            altFormat: "F j, Y (h:i K)", // Corrected the format
            time_24hr: false,
        });
    }

    // Initialize Flatpickr for each input field
    initializeFlatpickr("#start_date");
    initializeFlatpickr("#end_date");
    initializeFlatpickr("#registration_deadline");
});

function toggleLocationField() {
    var challengeType = document.getElementById("challenge_type").value;
    var locationField = document.getElementById("location_field");
    var locationInput = document.getElementById("location");

    if (challengeType === "Offline") {
        locationField.style.display = "block"; // Show the location field
        locationInput.required = true; // Make it required
    } else {
        locationField.style.display = "none"; // Hide the location field
        locationInput.value = ""; // Clear the value
        locationInput.required = false; // Remove the requirement
    }
}

document.addEventListener("DOMContentLoaded", function () {
    toggleLocationField(); // Call the function to set the initial state
});

document.addEventListener("DOMContentLoaded", function () {
    
    toggleLocationField(); // Call the function to set the initial state
    const imageInput = document.getElementById("image");
    const imagePreview = document.getElementById("imagePreview");

    function previewImages() {
        imagePreview.innerHTML = "";
        const files = imageInput.files;
        for (let i = 0; i < files.length; i++) {
            const file = files[i];
            const reader = new FileReader();
            reader.onload = function (e) {
                const img = document.createElement("img");
                img.src = e.target.result;
                img.width = 50;
                img.height = 50;
                imagePreview.appendChild(img);
            };
            reader.readAsDataURL(file);
        }
    }

    imageInput.addEventListener("change", previewImages);
});