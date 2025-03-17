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
