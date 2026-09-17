/* =========================================================
   UBE REPOSITORY — Main JavaScript
   ========================================================= */

// ── Flash message auto-dismiss ──────────────────────────
document.addEventListener('DOMContentLoaded', function () {
    const flash = document.getElementById('flashMsg');
    if (flash) {
        setTimeout(() => {
            flash.style.opacity = '0';
            flash.style.transition = 'opacity .4s';
            setTimeout(() => flash.remove(), 400);
        }, 4500);
    }

    // Hide password toggle buttons initially
    const pwBtns = document.querySelectorAll('.toggle-pw');
    pwBtns.forEach(btn => btn.style.display = 'none');

    // Show/hide toggle buttons when typing in password fields
    const passwordInput = document.getElementById('password');
    const confirmInput = document.getElementById('password_confirmation');
    if (passwordInput) {
        const btn = passwordInput.nextElementSibling;
        passwordInput.addEventListener('input', () => {
            btn.style.display = passwordInput.value.length > 0 ? 'flex' : 'none';
        });
    }
    if (confirmInput) {
        const btn = confirmInput.nextElementSibling;
        confirmInput.addEventListener('input', () => {
            btn.style.display = confirmInput.value.length > 0 ? 'flex' : 'none';
        });
    }
});

// ── Toggle password visibility ──────────────────────────
function togglePassword(fieldId, btn) {
    const input = document.getElementById(fieldId);
    if (!input) return;

    if (input.type === 'password') {
        // Show password — open eye
        input.type = 'text';
        btn.innerHTML = `<svg xmlns="http://www.w3.org/2000/svg" width="18" height="18" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><path d="M1 12s4-8 11-8 11 8 11 8-4 8-11 8-11-8-11-8z"></path><circle cx="12" cy="12" r="3"></circle></svg>`;
    } else {
        // Hide password — open eye
        input.type = 'password';
        btn.innerHTML = `<svg xmlns="http://www.w3.org/2000/svg" width="18" height="18" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><path d="M1 12s4-8 11-8 11 8 11 8-4 8-11 8-11-8-11-8z"></path><circle cx="12" cy="12" r="3"></circle></svg>`;
    }
}

// ── Pin / Unpin research ────────────────────────────────
function togglePin(id, btn) {
    fetch(`/pin/${id}`, {
        method: 'POST',
        headers: {
            'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').content,
            'Accept': 'application/json',
        }
    })
    .then(r => r.json())
    .then(data => {
        if (data.pinned) {
            btn.classList.add('pinned');
            btn.textContent = 'Pinned';
        } else {
            btn.classList.remove('pinned');
            btn.textContent = 'Pin';
        }
    });
}

// ── File upload display ─────────────────────────────────
function updateFileName(input) {
    const nameEl = document.getElementById('fileName');
    if (!nameEl) return;
    if (input.files && input.files.length > 0) {
        const file = input.files[0];
        nameEl.textContent = '✓ ' + file.name;
        nameEl.style.color = 'var(--green)';
    } else {
        nameEl.textContent = 'Click or drag file here';
        nameEl.style.color = '';
    }
}

// ── Drag & drop for file upload ─────────────────────────
document.addEventListener('DOMContentLoaded', function () {
    const area = document.getElementById('fileUploadArea');
    if (!area) return;

    area.addEventListener('dragover', (e) => {
        e.preventDefault();
        area.style.borderColor = 'var(--purple-main)';
        area.style.background  = 'var(--purple-pale)';
    });

    area.addEventListener('dragleave', () => {
        area.style.borderColor = '';
        area.style.background  = '';
    });

    area.addEventListener('drop', (e) => {
        e.preventDefault();
        area.style.borderColor = '';
        area.style.background  = '';
        const fileInput = area.querySelector('input[type="file"]');
        if (fileInput && e.dataTransfer.files.length > 0) {
            fileInput.files = e.dataTransfer.files;
            updateFileName(fileInput);
        }
    });
});

// ── Admin confirm actions ───────────────────────────────
document.addEventListener('DOMContentLoaded', function () {
    document.querySelectorAll('[data-confirm]').forEach(btn => {
        btn.addEventListener('click', function (e) {
            if (!confirm(this.dataset.confirm)) {
                e.preventDefault();
            }
        });
    });
});
// ── Show toggle button on password input ─────────────────
document.addEventListener('DOMContentLoaded', function () {
    document.querySelectorAll('.password-wrap input[type="password"]').forEach(input => {
        const btn = input.parentElement.querySelector('.toggle-pw');
        if (!btn) return;

        input.addEventListener('input', function () {
            if (this.value.length > 0) {
                btn.style.display = 'flex';
            } else {
                btn.style.display = 'none';
            }
        });
    });
});

document.addEventListener('DOMContentLoaded', function () {
    document.querySelectorAll('.ca-password-wrap input[type="password"]').forEach(input => {
        const btn = input.parentElement.querySelector('.ca-toggle-pw');
        if (!btn) return;
        input.addEventListener('input', function () {
            btn.style.display = this.value.length > 0 ? 'flex' : 'none';
        });
    });
});