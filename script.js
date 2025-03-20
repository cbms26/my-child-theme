document.addEventListener("DOMContentLoaded", function() {
    // Select all menu items with submenus
    document.querySelectorAll(".wp-block-navigation-item.has-child > a").forEach(item => {
        item.addEventListener("click", function(e) {
            e.preventDefault(); // Prevent default navigation
            let submenu = this.nextElementSibling;
            if (submenu) {
                submenu.classList.toggle("active");
                submenu.style.display = submenu.style.display === "block" ? "none" : "block";
            }
        });
    });
});

document.addEventListener("scroll", function() {
    const header = document.querySelector("header");
    if (window.scrollY > 50) {
        header.classList.add("header-scrolled"); // Smooth expand when scrolling
    } else {
        header.classList.remove("header-scrolled"); // Return to default height
    }
});

