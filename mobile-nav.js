document.addEventListener("DOMContentLoaded", function() {
    const navbars = document.querySelectorAll('.navbar');
    
    navbars.forEach(navbar => {
        const navMenu = navbar.querySelector('.navigationbar');
        const signUpBtn = navbar.querySelector('.SignUp');
        
        if (navMenu) {
            // Create hamburger icon
            const hamburger = document.createElement('div');
            hamburger.className = 'mobile-menu-icon';
            hamburger.innerHTML = '<i class="fa fa-bars"></i>';
            
            // Insert before navigationbar
            navbar.insertBefore(hamburger, navMenu);
            
            hamburger.addEventListener('click', function() {
                navMenu.classList.toggle('active');
                if (signUpBtn) signUpBtn.classList.toggle('active');
            });
        }
    });
});
