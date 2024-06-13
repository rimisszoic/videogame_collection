// Prevenir el envío del formulario si no se han realizado cambios
document.getElementById('profileForm').addEventListener('submit', function(event) {
    if (document.getElementById('name').disabled && document.getElementById('nick').disabled && document.getElementById('dob').disabled && document.getElementById('email').disabled && document.getElementById('current_password').disabled && document.getElementById('new_password').disabled && document.getElementById('confirm_password').disabled) {
        event.preventDefault();
    }
});

// Anular el copiado de contraseñas y pegado en campos de contraseña
document.querySelectorAll('input[type="password"]').forEach(function(input) {
    input.addEventListener('paste', function(event) {
        event.preventDefault();
        return false;
    });
    input.addEventListener('copy', function(event) {
        event.preventDefault();
        return false;
    });
});

function editProfile() {
    document.getElementById('name').disabled = false;
    document.getElementById('name').setAttribute('aria-disabled', 'false');
    document.getElementById('nick').disabled = false;
    document.getElementById('nick').setAttribute('aria-disabled', 'false');
    document.getElementById('dob').disabled = false;
    document.getElementById('dob').setAttribute('aria-disabled', 'false');
    document.getElementById('email').disabled = false;
    document.getElementById('email').setAttribute('aria-disabled', 'false');
    document.getElementById('current_password').disabled = false;
    document.getElementById('new_password').disabled = false;
    document.getElementById('confirm_password').disabled = false;
    document.getElementById('editProfileBtn').style.display = 'none';
    document.getElementById('saveChangesBtn').style.display = 'inline-block';
    document.getElementById('cancelEditBtn').style.display = 'inline-block';
}

function cancelEdit() {
    document.getElementById('name').disabled = true;
    document.getElementById('name').setAttribute('aria-disabled', 'true');
    document.getElementById('nick').disabled = true;
    document.getElementById('nick').setAttribute('aria-disabled', 'true');
    document.getElementById('dob').disabled = true;
    document.getElementById('dob').setAttribute('aria-disabled', 'true');
    document.getElementById('email').disabled = true;
    document.getElementById('email').setAttribute('aria-disabled', 'true');
    document.getElementById('current_password').disabled = true;
    document.getElementById('new_password').disabled = true;
    document.getElementById('confirm_password').disabled = true;
    document.getElementById('editProfileBtn').style.display = 'inline-block';
    document.getElementById('saveChangesBtn').style.display = 'none';
    document.getElementById('cancelEditBtn').style.display = 'none';
}

document.addEventListener('DOMContentLoaded', function() {
    document.getElementById('editProfileBtn').addEventListener('click', editProfile);
    document.getElementById('cancelEditBtn').addEventListener('click', cancelEdit);
});