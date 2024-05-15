document.addEventListener('DOMContentLoaded', () => {
    const passwordInputs = document.querySelectorAll('.form-control[type="password"]');
    const eyeIcons = document.querySelectorAll('.password-toggle-icon');

    eyeIcons.forEach((icon, index) => {
        icon.addEventListener('click', function() {
            const passwordInput = passwordInputs[index];
            togglePasswordVisibility(passwordInput, icon);
        });
    });

    passwordInputs.forEach((input) => {
        input.addEventListener('focus', function(event) {
            let parent = event.target.parentElement;
            while (parent) {
                const passwordTooltip = parent.querySelector('.pswd_info');
                if (passwordTooltip) {
                    const tooltipPosition = event.target.getBoundingClientRect();
                    passwordTooltip.style.top = tooltipPosition.bottom + 'px';
                    passwordTooltip.classList.add('active');
                    break;
                }
                parent = parent.parentElement;
            }
        });

        input.addEventListener('blur', function(event) {
            let parent = event.target.parentElement;
            while (parent) {
                const passwordTooltip = parent.querySelector('.pswd_info');
                if (passwordTooltip) {
                    passwordTooltip.classList.remove('active');
                    break;
                }
                parent = parent.parentElement;
            }
        });

        input.addEventListener('keyup', function(event) {
            const password = event.target.value;
            const p1 = document.getElementById('registerPassword').value;
            const p2 = document.getElementById('confirmPassword').value;
            const noValido = /\s/;

            // Validar longitud de contraseña
            if (password.length < 8) {
                document.getElementById('length').classList.remove('valid');
                document.getElementById('length').classList.add('invalid');
            } else {
                document.getElementById('length').classList.remove('invalid');
                document.getElementById('length').classList.add('valid');
            }

            // Validar letras
            if (password.match(/[A-z]/)) {
                document.getElementById('letter').classList.remove('invalid');
                document.getElementById('letter').classList.add('valid');
            } else {
                document.getElementById('letter').classList.remove('valid');
                document.getElementById('letter').classList.add('invalid');
            }

            // Validar mayúsculas
            if (password.match(/[A-Z]/)) {
                document.getElementById('capital').classList.remove('invalid');
                document.getElementById('capital').classList.add('valid');
            } else {
                document.getElementById('capital').classList.remove('valid');
                document.getElementById('capital').classList.add('invalid');
            }

            // Validar números
            if (password.match(/\d/)) {
                document.getElementById('number').classList.remove('invalid');
                document.getElementById('number').classList.add('valid');
            } else {
                document.getElementById('number').classList.remove('valid');
                document.getElementById('number').classList.add('invalid');
            }

            // Validar espacios
            if (noValido.test(p1) || noValido.test(p2)) {
                document.getElementById('blank').classList.remove('valid');
                document.getElementById('blank').classList.add('invalid');
            } else {
                document.getElementById('blank').classList.remove('invalid');
                document.getElementById('blank').classList.add('valid');
            }

            // Validar contraseñas iguales
            if (p1 !== p2) {
                document.getElementById('match').classList.remove('valid');
                document.getElementById('match').classList.add('invalid');
            } else {
                document.getElementById('match').classList.remove('invalid');
                document.getElementById('match').classList.add('valid');
            }
        });
    });

    const registerPasswordInput = document.querySelector('#registerPassword');
    const registerStrengthContainer = document.querySelector('.password-strength');

    if (registerPasswordInput && registerStrengthContainer) {
        registerPasswordInput.addEventListener('input', function(event) {
            const password = event.target.value;
            const strengthText = registerStrengthContainer.querySelector('.strength-text');
            const strengthBar = registerStrengthContainer.querySelector('.strength-bar');

            // Calcular la fortaleza de la contraseña
            const strength = calculatePasswordStrength(password);

            // Actualizar la barra de fortaleza de la contraseña
            updatePasswordStrength(strength, strengthText, strengthBar);
        });
    }
});

function togglePasswordVisibility(passwordInput, eyeIcon) {
    if (passwordInput.getAttribute('type') === 'password') {
        passwordInput.setAttribute('type', 'text');
        eyeIcon.querySelector('i').classList.remove('fa-eye');
        eyeIcon.querySelector('i').classList.add('fa-eye-slash');
    } else {
        passwordInput.setAttribute('type', 'password');
        eyeIcon.querySelector('i').classList.remove('fa-eye-slash');
        eyeIcon.querySelector('i').classList.add('fa-eye');
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
    if (strengthBar) {
        strengthBar.style.width = (strength * 20) + '%';

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
}