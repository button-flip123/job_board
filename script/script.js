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
});