// Modal functionality
const modals = document.querySelectorAll('.modal');
const modalTriggers = document.querySelectorAll('[href*="#"]');
const closeButtons = document.querySelectorAll('.close');

modalTriggers.forEach(trigger => {
    trigger.addEventListener('click', (e) => {
        e.preventDefault();
        const targetModal = document.querySelector(trigger.getAttribute('href'));
        if (targetModal) {
            targetModal.style.display = 'block';
        }
    });
});

closeButtons.forEach(button => {
    button.addEventListener('click', () => {
        button.closest('.modal').style.display = 'none';
    });
});

window.addEventListener('click', (e) => {
    if (e.target.classList.contains('modal')) {
        e.target.style.display = 'none';
    }
});

// Form validation
document.querySelectorAll('form').forEach(form => {
    form.addEventListener('submit', (e) => {
        let isValid = true;
        form.querySelectorAll('input[required], select[required], textarea[required]').forEach(field => {
            if (!field.value.trim()) {
                isValid = false;
                field.style.borderColor = '#e74c3c';
            } else {
                field.style.borderColor = '#3498db';
            }
        });
        
        if (!isValid) {
            e.preventDefault();
            alert('Please fill all required fields');
        }
    });
});

// Dynamic doctor availability check
function checkDoctorAvailability(doctorId, date) {
    // AJAX call to check availability
    fetch(`check_availability.php?doctor_id=${doctorId}&date=${date}`)
        .then(response => response.json())
        .then(data => {
            if (data.available) {
                document.getElementById('book-btn').disabled = false;
            } else {
                document.getElementById('book-btn').disabled = true;
                alert('Doctor has reached daily limit');
            }
        });
}