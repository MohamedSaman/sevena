document.addEventListener('DOMContentLoaded', function() {
    // Navigation active state
    const currentPage = window.location.pathname.split('/').pop();
    document.querySelectorAll('.nav-item').forEach(item => {
        const link = item.getAttribute('href');
        if (link === currentPage) {
            item.classList.add('active');
        } else {
            item.classList.remove('active');
        }
    });

    // Tab functionality
    document.querySelectorAll('.tabs-container .tab').forEach(tab => {
        tab.addEventListener('click', function() {
            // Remove active class from all tabs in this container
            this.parentNode.querySelectorAll('.tab').forEach(t => {
                t.classList.remove('active');
            });
            // Add active class to clicked tab
            this.classList.add('active');
            
            // You can add content switching logic here
        });
    });

    // Production form calculation
    const productionForm = document.getElementById('production-form');
    if (productionForm) {
        productionForm.addEventListener('input', function(e) {
            // Add calculation logic here
        });
    }

    // Notification button
    const notificationBtn = document.querySelector('.notification-btn');
    if (notificationBtn) {
        notificationBtn.addEventListener('click', function() {
            // Notification logic
        });
    }

    // Logout button
    const logoutBtn = document.querySelector('.logout-btn');
    if (logoutBtn) {
        logoutBtn.addEventListener('click', function() {
            // Logout logic
        });
    }
});