// MultipleFiles/script.js

// Function to show a custom alert message (can be replaced with a more sophisticated modal)
function showCustomAlert(message, type = 'success') {
    const alertBox = document.createElement('div');
    alertBox.textContent = message;
    alertBox.style.padding = '10px';
    alertBox.style.margin = '10px 0';
    alertBox.style.borderRadius = '5px';
    alertBox.style.textAlign = 'center';
    alertBox.style.fontWeight = 'bold';
    alertBox.style.color = 'white';
    alertBox.style.position = 'fixed'; // Make it fixed
    alertBox.style.top = '10px'; // Position at the top
    alertBox.style.left = '50%'; // Center horizontally
    alertBox.style.transform = 'translateX(-50%)'; // Adjust for centering
    alertBox.style.zIndex = '1000'; // Ensure it's on top of other content
    alertBox.style.width = 'fit-content'; // Adjust width to content
    alertBox.style.minWidth = '250px'; // Minimum width
    alertBox.style.boxShadow = '0 2px 10px rgba(0,0,0,0.2)'; // Add shadow

    if (type === 'success') {
        alertBox.style.backgroundColor = '#4CAF50'; // Green
    } else if (type === 'error') {
        alertBox.style.backgroundColor = '#f44336'; // Red
    } else {
        alertBox.style.backgroundColor = '#2196F3'; // Blue (info)
    }

    document.body.prepend(alertBox); // Add to the top of the body

    setTimeout(() => {
        alertBox.remove(); // Remove after 5 seconds
    }, 5000);
}

// --- Form Validation Functions ---

function validateRegisterForm(event) {
    const name = document.querySelector('input[name="name"]').value;
    const mobile = document.querySelector('input[name="mobile"]').value;
    const email = document.querySelector('input[name="email"]').value;
    const password = document.querySelector('input[name="password"]').value;

    if (name.trim() === '') {
        showCustomAlert('Name cannot be empty.', 'error');
        event.preventDefault();
        return false;
    }
    if (!/^\d{10}$/.test(mobile)) {
        showCustomAlert('Mobile number must be 10 digits.', 'error');
        event.preventDefault();
        return false;
    }
    if (!/^[^\s@]+@[^\s@]+\.[^\s@]+$/.test(email)) {
        showCustomAlert('Please enter a valid email address.', 'error');
        event.preventDefault();
        return false;
    }
    if (password.length < 6) {
        showCustomAlert('Password must be at least 6 characters long.', 'error');
        event.preventDefault();
        return false;
    }
    return true;
}

function validateContactForm(event) {
    const name = document.querySelector('input[name="name"]').value;
    const email = document.querySelector('input[name="email"]').value;
    const message = document.querySelector('textarea[name="message"]').value;

    if (name.trim() === '' || email.trim() === '' || message.trim() === '') {
        showCustomAlert('All fields are required.', 'error');
        event.preventDefault();
        return false;
    }
    if (!/^[^\s@]+@[^\s@]+\.[^\s@]+$/.test(email)) {
        showCustomAlert('Please enter a valid email address.', 'error');
        event.preventDefault();
        return false;
    }
    return true;
}

function validatePatientForm(event) {
    const name = document.querySelector('input[name="name"]').value;
    const age = document.querySelector('input[name="age"]').value;
    const gender = document.querySelector('input[name="gender"]:checked');
    const phoneNumber = document.querySelector('input[name="number"]').value;
    const email = document.querySelector('input[name="email"]').value;

    if (name.trim() === '' || age.trim() === '' || !gender || phoneNumber.trim() === '') {
        showCustomAlert('Name, Age, Gender, and Phone Number are required.', 'error');
        event.preventDefault();
        return false;
    }
    if (isNaN(age) || parseInt(age) <= 0) {
        showCustomAlert('Age must be a positive number.', 'error');
        event.preventDefault();
        return false;
    }
    if (!/^\d{10}$/.test(phoneNumber)) {
        showCustomAlert('Phone number must be 10 digits.', 'error');
        event.preventDefault();
        return false;
    }
    if (email.trim() !== '' && !/^[^\s@]+@[^\s@]+\.[^\s@]+$/.test(email)) {
        showCustomAlert('Please enter a valid email address or leave it empty.', 'error');
        event.preventDefault();
        return false;
    }
    return true;
}

function validateAppointmentForm(event) {
    const name = document.querySelector('input[name="name"]').value;
    const email = document.querySelector('input[name="email"]').value;
    const phone = document.querySelector('input[name="phone"]').value;
    const date = document.querySelector('input[name="date"]').value;
    const time = document.querySelector('input[name="time"]').value;
    const doctor = document.querySelector('select[name="doctor"]').value;
    const symptoms = document.querySelector('textarea[name="symptoms"]').value;

    if (name.trim() === '' || email.trim() === '' || phone.trim() === '' || date.trim() === '' || time.trim() === '' || doctor.trim() === '' || symptoms.trim() === '') {
        showCustomAlert('All fields are required for appointment booking.', 'error');
        event.preventDefault();
        return false;
    }
    if (!/^[^\s@]+@[^\s@]+\.[^\s@]+$/.test(email)) {
        showCustomAlert('Please enter a valid email address.', 'error');
        event.preventDefault();
        return false;
    }
    if (!/^\d{10}$/.test(phone)) {
        showCustomAlert('Phone number must be 10 digits.', 'error');
        event.preventDefault();
        return false;
    }
    return true;
}

function validateMedicineForm(event) {
    const medicine = document.querySelector('input[name="medicine"]').value;
    const quantity = document.querySelector('input[name="quantity"]').value;
    const delivery = document.querySelector('textarea[name="delivery"]').value;

    if (medicine.trim() === '' || quantity.trim() === '' || delivery.trim() === '') {
        showCustomAlert('All fields are required for medicine order.', 'error');
        event.preventDefault();
        return false;
    }
    if (isNaN(quantity) || parseInt(quantity) <= 0) {
        showCustomAlert('Quantity must be a positive number.', 'error');
        event.preventDefault();
        return false;
    }
    return true;
}


// --- Event Listeners for Forms ---
document.addEventListener('DOMContentLoaded', () => {
    // Determine the current page to apply specific event listeners
    const currentPage = window.location.pathname.split('/').pop();

    if (currentPage === 'register.php') {
        const registerForm = document.querySelector('form');
        if (registerForm) {
            registerForm.addEventListener('submit', validateRegisterForm);
        }
    } else if (currentPage === 'contact.php') {
        const contactForm = document.querySelector('form');
        if (contactForm) {
            contactForm.addEventListener('submit', validateContactForm);
        }
    } else if (currentPage === 'patient.php') {
        const patientForm = document.querySelector('.patient-details form');
        if (patientForm) {
            patientForm.addEventListener('submit', validatePatientForm);
        }
    } else if (currentPage === 'appointment.php') {
        const appointmentForm = document.querySelector('.container form');
        if (appointmentForm) {
            appointmentForm.addEventListener('submit', validateAppointmentForm);
        }
    } else if (currentPage === 'medicine.php') {
        const medicineForm = document.querySelector('main form');
        if (medicineForm) {
            medicineForm.addEventListener('submit', validateMedicineForm);
        }

        // --- Quantity Adjuster for Medicine Page ---
        const quantityInput = document.getElementById('quantity');
        if (quantityInput) {
            const quantityContainer = document.createElement('div');
            quantityContainer.style.display = 'flex';
            quantityContainer.style.alignItems = 'center';
            quantityContainer.style.marginBottom = '10px';

            const minusBtn = document.createElement('button');
            minusBtn.textContent = '-';
            minusBtn.type = 'button';
            minusBtn.style.width = '30px';
            minusBtn.style.padding = '5px';
            minusBtn.style.marginRight = '5px';
            minusBtn.style.backgroundColor = '#f44336';
            minusBtn.style.color = 'white';
            minusBtn.style.border = 'none';
            minusBtn.style.borderRadius = '3px';
            minusBtn.style.cursor = 'pointer';
            minusBtn.onclick = () => {
                let currentVal = parseInt(quantityInput.value);
                if (currentVal > 1) {
                    quantityInput.value = currentVal - 1;
                }
            };

            const plusBtn = document.createElement('button');
            plusBtn.textContent = '+';
            plusBtn.type = 'button';
            plusBtn.style.width = '30px';
            plusBtn.style.padding = '5px';
            plusBtn.style.marginLeft = '5px';
            plusBtn.style.backgroundColor = '#4CAF50';
            plusBtn.style.color = 'white';
            plusBtn.style.border = 'none';
            plusBtn.style.borderRadius = '3px';
            plusBtn.style.cursor = 'pointer';
            plusBtn.onclick = () => {
                let currentVal = parseInt(quantityInput.value);
                quantityInput.value = currentVal + 1;
            };

            // Insert buttons around the quantity input
            quantityInput.parentNode.insertBefore(quantityContainer, quantityInput);
            quantityContainer.appendChild(minusBtn);
            quantityContainer.appendChild(quantityInput); // Move input into container
            quantityContainer.appendChild(plusBtn);
            quantityInput.style.width = 'auto'; // Adjust width for input in flex container
            quantityInput.style.flexGrow = '1';
            quantityInput.style.textAlign = 'center';
        }
    }

    // --- Home Page Carousel (Basic Implementation) ---
    const carouselSlide = document.querySelector('.carousel-slide');
    if (carouselSlide) {
        const images = document.querySelectorAll('.carousel-slide img');
        let currentIndex = 0;
        const totalImages = images.length;

        const updateCarousel = () => {
            const offset = -currentIndex * images[0].clientWidth;
            carouselSlide.style.transform = `translateX(${offset}px)`;
        };

        // Auto-advance carousel
        setInterval(() => {
            currentIndex = (currentIndex + 1) % totalImages;
            updateCarousel();
        }, 5000); // Change image every 5 seconds

        // Optional: Add resize listener to adjust carousel on window resize
        window.addEventListener('resize', updateCarousel);
    }
});

// Global functions for logout/login from index.html
function logout() {
    showCustomAlert("Logged out!", 'info');
    // In a real application, this would redirect to a logout.php that destroys the session.
    // For now, it just redirects to index.html.
    window.location.href = "index.html";
}

function login() {
    window.location.href = "k.php";
}