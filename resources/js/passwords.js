document.addEventListener('DOMContentLoaded', function() {
    const passwordInputs = document.querySelectorAll('.form-control[type="password"]');
    const eyeIcons = document.querySelectorAll('.password-toggle-icon i');

    eyeIcons.forEach(function(icon) {
        icon.addEventListener('click', function() {
            const passwordInput = this.parentElement.querySelector('.form-control');
            togglePasswordVisibility(passwordInput, this);
        });
    });

    passwordInputs.forEach(function(input) {
        input.addEventListener('input', function() {
            const password = this.value;
            const strengthContainer = this.closest('.modal-body').querySelector('.password-strength');
            const strengthText = strengthContainer.querySelector('.strength-text');
            const strengthBar = strengthContainer.querySelector('.strength-bar');

            const strength = calculatePasswordStrength(password);
            updatePasswordStrength(strength, strengthText, strengthBar);
        });
    });
});

function togglePasswordVisibility(passwordInput, eyeIcon) {
    if (passwordInput.getAttribute('type') === 'password') {
        passwordInput.setAttribute('type', 'text');
        eyeIcon.classList.remove('fa-eye');
        eyeIcon.classList.add('fa-eye-slash');
    } else {
        passwordInput.setAttribute('type', 'password');
        eyeIcon.classList.remove('fa-eye-slash');
        eyeIcon.classList.add('fa-eye');
    }
}

function calculatePasswordStrength(password) {
    let strength = 0;

    if (password.length >= 8) {
        strength++;
    }

    if (password.match(/[a-z]+/)) {
        strength++;
    }

    if (password.match(/[A-Z]+/)) {
        strength++;
    }

    if (password.match(/[0-9]+/)) {
        strength++;
    }

    if (password.match(/[^A-Za-z0-9]+/)) {
        strength++;
    }

    return strength;
}

function updatePasswordStrength(strength, strengthText, strengthBar) {
    // Actualizar la barra de fuerza
    strengthBar.style.width = (strength * 20) + '%';

    // Actualizar el texto de la fuerza
    switch (strength) {
        case 0:
        case 1:
            strengthText.textContent = 'Muy débil';
            strengthText.style.color = '#d32f2f';
            strengthBar.style.backgroundColor = '#d32f2f';
            break;
        case 2:
            strengthText.textContent = 'Débil';
            strengthText.style.color = '#f57c00';
            strengthBar.style.backgroundColor = '#f57c00';
            break;
        case 3:
            strengthText.textContent = 'Moderada';
            strengthText.style.color = '#fdd835';
            strengthBar.style.backgroundColor = '#fdd835';
            break;
        case 4:
            strengthText.textContent = 'Fuerte';
            strengthText.style.color = '#7cb342';
            strengthBar.style.backgroundColor = '#7cb342';
            break;
        case 5:
            strengthText.textContent = 'Muy fuerte';
            strengthText.style.color = '#43a047';
            strengthBar.style.backgroundColor = '#43a047';
            break;
        default:
            strengthText.textContent = '';
    }
}