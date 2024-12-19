const sidebar = document.querySelector(".sidebar");
const sidebarToggler = document.querySelector(".sidebar-toggler");

sidebarToggler.addEventListener("click", () => {
    if (window.innerWidth >= 768) {  // Only toggle the sidebar on larger screens
        sidebar.classList.toggle("collapsed");
        adjustMainContent();
    }
});

// This function adjusts the layout of the sidebar when collapsed
function adjustMainContent() {
    const mainContent = document.getElementById('main-content');
    if (sidebar.classList.contains('collapsed')) {
        mainContent.style.marginLeft = '60px';  // Adjust for collapsed sidebar
    } else {
        mainContent.style.marginLeft = '250px';  // Adjust for expanded sidebar
    }
}

// Adjust sidebar visibility when resizing
function handleTogglerVisibility() {
    if (window.innerWidth < 768) {
        sidebarToggler.style.display = 'none';  // Hide toggler button on small screens
    } else {
        sidebarToggler.style.display = 'block';  // Show toggler on larger screens
    }
}

// Call this function to adjust sidebar state and visibility on page load and when resizing
window.addEventListener('resize', () => {
    handleTogglerVisibility();
    adjustMainContent();
});

// Initial call to handle sidebar position on page load
handleTogglerVisibility();
adjustMainContent();
