// Flash message auto-dismiss
document.addEventListener('DOMContentLoaded', function () {
    const alerts = document.querySelectorAll('.alert[data-auto-dismiss]');
    alerts.forEach(function (alert) {
        setTimeout(function () {
            alert.style.transition = 'opacity .5s ease';
            alert.style.opacity = '0';
            setTimeout(function () { alert.remove(); }, 500);
        }, 4000);
    });

    // Modal open/close
    document.querySelectorAll('[data-modal]').forEach(function (btn) {
        btn.addEventListener('click', function () {
            const target = document.getElementById(btn.dataset.modal);
            if (target) target.classList.add('active');
        });
    });
    document.querySelectorAll('.modal-overlay').forEach(function (overlay) {
        overlay.addEventListener('click', function (e) {
            if (e.target === overlay) overlay.classList.remove('active');
        });
    });
    document.querySelectorAll('.modal-close, .modal-close-btn').forEach(function (btn) {
        btn.addEventListener('click', function () {
            const overlay = btn.closest('.modal-overlay');
            if (overlay) overlay.classList.remove('active');
        });
    });
    document.addEventListener('keydown', function (e) {
        if (e.key === 'Escape') {
            document.querySelectorAll('.modal-overlay.active').forEach(function (overlay) {
                overlay.classList.remove('active');
            });
        }
    });

    // Mobile sidebar toggle
    const sidebarToggle = document.getElementById('sidebarToggle');
    const sidebar = document.querySelector('.sidebar');
    if (sidebarToggle && sidebar) {
        sidebarToggle.addEventListener('click', function () {
            sidebar.classList.toggle('open');
        });
    }

    // Confirm delete
    document.querySelectorAll('[data-confirm]').forEach(function (el) {
        el.addEventListener('click', function (e) {
            if (!confirm(el.dataset.confirm || 'Yakin ingin melanjutkan?')) {
                e.preventDefault();
            }
        });
    });
});
