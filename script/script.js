document.addEventListener('DOMContentLoaded', function() {
    // Toggle password visibility (for login and register)
    const toggleButtons = document.querySelectorAll('#togglePassword');
    toggleButtons.forEach(button => {
        button.addEventListener('click', function() {
            const passwordInput = this.previousElementSibling;
            const type = passwordInput.getAttribute('type') === 'password' ? 'text' : 'password';
            passwordInput.setAttribute('type', type);
            this.textContent = type === 'password' ? 'Show' : 'Hide';
        });
    });

    // Form validation (basic frontend validation)
    const forms = document.querySelectorAll('form');
    forms.forEach(form => {
        form.addEventListener('submit', function(event) {
            event.preventDefault(); // Prevent submit for demo
            let isValid = true;

            // Check required fields
            const requiredInputs = form.querySelectorAll('[required]');
            requiredInputs.forEach(input => {
                if (!input.value.trim()) {
                    isValid = false;
                    input.classList.add('is-invalid');
                } else {
                    input.classList.remove('is-invalid');
                }
            });

            // Email validation
            const emailInput = form.querySelector('input[type="email"]');
            if (emailInput && !/\S+@\S+\.\S+/.test(emailInput.value)) {
                isValid = false;
                emailInput.classList.add('is-invalid');
            }

            // Password length (for login/register)
            const passwordInput = form.querySelector('#password');
            if (passwordInput && passwordInput.value.length < 6) {
                isValid = false;
                passwordInput.classList.add('is-invalid');
            }

            if (isValid) {
                alert('Form submitted successfully! (Demo)');
            } else {
                alert('Please fix the errors.');
            }
        });
    });
});