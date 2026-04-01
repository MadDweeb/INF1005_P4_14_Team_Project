/*
 * public/js/account.js
 * Account page interactions
 */

document.addEventListener('DOMContentLoaded', function() {
    // Edit Account Info
    const editAccountBtn = document.getElementById('editAccountBtn');
    const accountInfo = document.getElementById('accountInfo');
    const editForm = document.getElementById('editForm');
    const cancelEditBtn = document.getElementById('cancelEditBtn');

    if (editAccountBtn && editForm) {
        editAccountBtn.addEventListener('click', function() {
            accountInfo.style.display = 'none';
            editForm.classList.remove('hidden');
        });

        cancelEditBtn.addEventListener('click', function() {
            editForm.classList.add('hidden');
            accountInfo.style.display = 'grid';
        });
    }

    // Show Password Form
    const showPasswordBtn = document.getElementById('showPasswordBtn');
    const passwordForm = document.getElementById('passwordForm');
    const cancelPasswordBtn = document.getElementById('cancelPasswordBtn');

    if (showPasswordBtn && passwordForm) {
        showPasswordBtn.addEventListener('click', function() {
            passwordForm.classList.remove('hidden');
        });

        cancelPasswordBtn.addEventListener('click', function() {
            passwordForm.classList.add('hidden');
            passwordForm.reset();
        });
    }

    // Password Confirmation Validation
    const newPassword = document.getElementById('new_password');
    const confirmPassword = document.getElementById('confirm_password');

    if (confirmPassword) {
        confirmPassword.addEventListener('input', function() {
            if (newPassword.value !== confirmPassword.value) {
                confirmPassword.setCustomValidity('Passwords do not match');
            } else {
                confirmPassword.setCustomValidity('');
            }
        });
    }
});