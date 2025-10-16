/* ================================
   E-Health Management | script.js
   Author: Mandeep
   Purpose: Form validation, alerts,
            carousel, and navigation
================================ */

// ---------- Custom Alert ----------
function showCustomAlert(message, type = 'info') {
    const alertBox = document.createElement('div');
    alertBox.textContent = message;
    Object.assign(alertBox.style, {
        padding: '10px',
        margin: '10px 0',
        borderRadius: '5px',
        textAlign: 'center',
        fontWeight: 'bold',
        color: '#fff',
        position: 'fixed',
        top: '15px',
        left: '50%',
        transform: 'translateX(-50%)',
        zIndex: '2000',
        minWidth: '250px',
        boxShadow: '0 2px 10px rgba(0,0,0,0.25)',
        opacity: '0',
        transition: 'opacity 0.3s ease'
    });

    const colors = {
        success: '#4CAF50',
        error: '#f44336',
        info: '#2196F3'
    };
    alertBox.style.backgroundColor = colors[type] || colors.info;

    document.body.appendChild(alertBox);
    setTimeout(() => (alertBox.style.opacity = '1'), 10); // fade in

    // Fade out and remove after 5s
    setTimeout(() => {
        alertBox.style.opacity = '0';
        setTimeout(() => alertBox.remove(), 300);
    }, 5000);
}

// ---------- Generic Validators ----------
const validators = {
    email: value => /^[^\s@]+@[^\s@]+\.[^\s@]+$/.test(value),
    mobile: value => /^\d{10}$/.test(value),
    number: value => !isNaN(value) && parseInt(value) > 0
};

// ---------- Utility ----------
function getValue(selector) {
    const el = document.querySelector(selector);
    return el ? el.value.trim() : '';
}

function prevent(event, msg) {
    event.preventDefault();
    showCustomAlert(msg, 'error');
    return false;
}

// ---------- Individual Form Validators ----------
function validateRegisterForm(e) {
    const name = getValue('input[name="name"]');
    const mobile = getValue('input[name="mobile"]');
    const email = getValue('input[name="email"]');
    const password = getValue('input[name="password"]');

    if (!name) return prevent(e, 'Name cannot be empty.');
    if (!validators.mobile(mobile)) return prevent(e, 'Mobile number must be 10 digits.');
    if (!validators.email(email)) return prevent(e, 'Please enter a valid email address.');
    if (password.length < 6) return prevent(e, 'Password must be at least 6 characters.');
    showCustomAlert('Registration successful!', 'success');
    return true;
}

function validateContactForm(e) {
    const name = getValue('input[name="name"]');
    const email = getValue('input[name="email"]');
    const message = getValue('textarea[name="message"]');

    if (!name || !email || !message) return prevent(e, 'All fields are required.');
    if (!validators.email(email)) return prevent(e, 'Invalid email address.');
    showCustomAlert('Message sent successfully!', 'success');
    return true;
}

function validatePatientForm(e) {
    const name = getValue('input[name="name"]');
    const age = getValue('input[name="age"]');
    const gender = document.querySelector('input[name="gender"]:checked');
    const phone = getValue('input[name="number"]');
    const email = getValue('input[name="email"]');

    if (!name || !age || !gender || !phone)
        return prevent(e, 'Name, Age, Gender & Phone are required.');
    if (!validators.number(age)) return prevent(e, 'Age must be a positive number.');
    if (!validators.mobile(phone)) return prevent(e, 'Phone number must be 10 digits.');
    if (email && !validators.email(email)) return prevent(e, 'Invalid email address.');
    showCustomAlert('Patient details submitted.', 'success');
    return true;
}

function validateAppointmentForm(e) {
    const fields = ['name', 'email', 'phone', 'date', 'time', 'doctor'];
    for (const f of fields) {
        if (!getValue(`input[name="${f}"], select[name="${f}"]`))
            return prevent(e, 'All fields are required for appointment.');
    }
    const email = getValue('input[name="email"]');
    const phone = getValue('input[name="phone"]');
    if (!validators.email(email)) return prevent(e, 'Invalid email address.');
    if (!validators.mobile(phone)) return prevent(e, 'Phone number must be 10 digits.');
    showCustomAlert('Appointment booked successfully!', 'success');
    return true;
}

function validateMedicineForm(e) {
    const medicine = getValue('input[name="medicine"]');
    const quantity = getValue('input[name="quantity"]');
    const delivery = getValue('textarea[name="delivery"]');

    if (!medicine || !quantity || !delivery)
        return prevent(e, 'All fields are required.');
    if (!validators.number(quantity))
        return prevent(e, 'Quantity must be a positive number.');
    showCustomAlert('Order placed successfully!', 'success');
    return true;
}

// ---------- Quantity Adjuster ----------
function setupQuantityAdjuster() {
    const qtyInput = document.getElementById('quantity');
    if (!qtyInput) return;

    const wrap = document.createElement('div');
    Object.assign(wrap.style, {
        display: 'flex',
        alignItems: 'center',
        marginBottom: '10px'
    });

    const makeBtn = (text, color, onClick) => {
        const btn = document.createElement('button');
        btn.textContent = text;
        Object.assign(btn.style, {
            width: '30px',
            padding: '5px',
            backgroundColor: color,
            color: 'white',
            border: 'none',
            borderRadius: '3px',
            cursor: 'pointer'
        });
        btn.type = 'button';
        btn.addEventListener('click', onClick);
        return btn;
    };

    const minus = makeBtn('-', '#f44336', () => {
        const val = parseInt(qtyInput.value) || 1;
        if (val > 1) qtyInput.value = val - 1;
    });

    const plus = makeBtn('+', '#4CAF50', () => {
        const val = parseInt(qtyInput.value) || 0;
        qtyInput.value = val + 1;
    });

    qtyInput.parentNode.insertBefore(wrap, qtyInput);
    wrap.append(minus, qtyInput, plus);
    Object.assign(qtyInput.style, {
        width: '50px',
        textAlign: 'center',
        margin: '0 5px'
    });
}

// ---------- Carousel ----------
function setupCarousel() {
    const slide = document.querySelector('.carousel-slide');
    if (!slide) return;

    const imgs = slide.querySelectorAll('img');
    let i = 0;
    const update = () => {
        const offset = -i * imgs[0].clientWidth;
        slide.style.transform = `translateX(${offset}px)`;
    };

    setInterval(() => {
        i = (i + 1) % imgs.length;
        update();
    }, 5000);

    window.addEventListener('resize', update);
}

// ---------- Logout / Login ----------
function logout() {
    showCustomAlert('Logging out...', 'info');
    setTimeout(() => (window.location.href = 'logout.php'), 1000);
}

function login() {
    window.location.href = 'k.php';
}

// ---------- Event Binding ----------
document.addEventListener('DOMContentLoaded', () => {
    const page = window.location.pathname.split('/').pop();

    const forms = {
        'register.php': validateRegisterForm,
        'contact.php': validateContactForm,
        'patient.php': validatePatientForm,
        'appointment.php': validateAppointmentForm,
        'medicine.php': validateMedicineForm
    };

    if (forms[page]) {
        const form = document.querySelector('form');
        if (form) form.addEventListener('submit', forms[page]);
    }

    if (page === 'medicine.php') setupQuantityAdjuster();
    setupCarousel();
});