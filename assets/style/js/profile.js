
document.addEventListener('DOMContentLoaded', function() {
    const form = document.querySelector('#passwordModal form');
    const newPwd = document.getElementById('new_password');
    const confirmPwd = document.getElementById('new_password_confirm');
    
    form.addEventListener('submit', function(e) {
        if (newPwd.value !== confirmPwd.value) {
            e.preventDefault();
            Swal.fire({
                title: "Erreur",
                text: "Les mots de passe ne correspondent pas.",
                icon: "error",
                confirmButtonText: "OK"
            });
            confirmPwd.focus();
            confirmPwd.style.borderColor = 'red';
        }
    });
    
    confirmPwd.addEventListener('input', function() {
        if (this.value === newPwd.value) {
            this.style.borderColor = 'green';
        } else {
            this.style.borderColor = 'red';
        }
    });
});
