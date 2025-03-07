document.addEventListener("DOMContentLoaded", () => {
    // Welcome section animation
    const welcomeSection = document.querySelector(".welcome-section")
    if (welcomeSection) {
        welcomeSection.style.opacity = "0"
        welcomeSection.style.transform = "translateY(20px)"
        welcomeSection.style.transition = "opacity 0.5s ease, transform 0.5s ease"

        setTimeout(() => {
            welcomeSection.style.opacity = "1"
            welcomeSection.style.transform = "translateY(0)"
        }, 300)
    }

    // Slider functionality
    let slideIndex = 0
    const slides = document.querySelectorAll(".slide")
    const dots = document.querySelectorAll(".dot")
    const progressBar = document.querySelector(".progress-bar")

    if (slides.length > 0) {
        // Initialize the first slide
        showSlide(slideIndex)

        // Set up automatic slideshow
        let slideInterval = startSlideTimer()

        // Add click events to prev/next buttons
        document.querySelector(".prev").addEventListener("click", () => {
            clearInterval(slideInterval)
            showSlide(slideIndex - 1)
            slideInterval = startSlideTimer()
        })

        document.querySelector(".next").addEventListener("click", () => {
            clearInterval(slideInterval)
            showSlide(slideIndex + 1)
            slideInterval = startSlideTimer()
        })

        // Add click events to dots
        dots.forEach((dot, index) => {
            dot.addEventListener("click", () => {
                clearInterval(slideInterval)
                showSlide(index)
                slideInterval = startSlideTimer()
            })
        })

        // Pause slideshow when hovering over the slider
        document.querySelector(".slide-container").addEventListener("mouseenter", () => {
            clearInterval(slideInterval)
            if (progressBar) {
                progressBar.style.transition = "none"
                progressBar.style.width = progressBar.offsetWidth + "px"
            }
        })

        document.querySelector(".slide-container").addEventListener("mouseleave", () => {
            slideInterval = startSlideTimer()
        })
    }

    function showSlide(n) {
        // Reset progress bar
        if (progressBar) {
            progressBar.style.width = "0"
            progressBar.style.transition = "width 0.3s linear"
        }

        // Handle index bounds
        if (n >= slides.length) {
            slideIndex = 0
        } else if (n < 0) {
            slideIndex = slides.length - 1
        } else {
            slideIndex = n
        }

        // Hide all slides
        slides.forEach((slide) => {
            slide.classList.remove("active")
            slide.style.display = "none"
        })

        // Remove active class from all dots
        dots.forEach((dot) => {
            dot.classList.remove("active")
        })

        // Show the current slide and activate the corresponding dot
        slides[slideIndex].style.display = "block"
        slides[slideIndex].classList.add("active", "fade")

        if (dots.length > 0 && slideIndex < dots.length) {
            dots[slideIndex].classList.add("active")
        }

        // Animate progress bar
        if (progressBar) {
            setTimeout(() => {
                progressBar.style.width = "100%"
            }, 50)
        }
    }

    function startSlideTimer() {
        return setInterval(() => {
            showSlide(slideIndex + 1)
        }, 5000) // Change slide every 5 seconds
    }

    // Add touch swipe functionality for mobile
    let touchStartX = 0
    let touchEndX = 0

    const slider = document.querySelector(".slide-container")
    if (slider) {
        slider.addEventListener(
            "touchstart",
            (e) => {
                touchStartX = e.changedTouches[0].screenX
            },
            false,
        )

        slider.addEventListener(
            "touchend",
            (e) => {
                touchEndX = e.changedTouches[0].screenX
                handleSwipe()
            },
            false,
        )
    }

    function handleSwipe() {
        if (touchEndX < touchStartX) {
            // Swipe left - next slide
            document.querySelector(".next").click()
        } else if (touchEndX > touchStartX) {
            // Swipe right - previous slide
            document.querySelector(".prev").click()
        }
    }
})

