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

// Form validation for all forms
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

// Doctor availability check (called from appointment modal)
function checkAvailability() {
    const doctorId = document.getElementById('doctor_select')?.value;
    const date = document.getElementById('appointment_date')?.value;
    const statusDiv = document.getElementById('availability-status');
    const bookBtn = document.getElementById('book-btn');
    
    if (!doctorId || !date) {
        if (statusDiv) statusDiv.innerHTML = '';
        if (bookBtn) bookBtn.disabled = true;
        return;
    }
    
    fetch(`check_availability.php?doctor_id=${doctorId}&date=${date}`)
        .then(response => response.json())
        .then(data => {
            if (data.available) {
                statusDiv.innerHTML = `<span style="color:green;">✅ Available! ${data.remaining} slot(s) remaining.</span>`;
                bookBtn.disabled = false;
            } else {
                statusDiv.innerHTML = `<span style="color:red;">❌ Not available. Max ${data.max_patients} patients, already ${data.current_count} booked.</span>`;
                bookBtn.disabled = true;
            }
        })
        .catch(error => {
            console.error('Error checking availability:', error);
            statusDiv.innerHTML = `<span style="color:red;">Error checking availability.</span>`;
            bookBtn.disabled = true;
        });
}