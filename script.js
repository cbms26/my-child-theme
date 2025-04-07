document.addEventListener("DOMContentLoaded", function () {
  // 1. Submenu toggle
  document
    .querySelectorAll(".wp-block-navigation-item.has-child > a")
    .forEach((item) => {
      item.addEventListener("click", function (e) {
        e.preventDefault(); // Prevent default navigation
        let submenu = this.nextElementSibling;
        if (submenu) {
          submenu.classList.toggle("active");
          submenu.style.display =
            submenu.style.display === "block" ? "none" : "block";
        }
      });
    });

  // 2. Hamburger open/close logic
  const openBtn = document.querySelector(
    ".wp-block-navigation__responsive-container-open"
  );
  const closeBtn = document.querySelector(
    ".wp-block-navigation__responsive-container-close"
  );
  const menuContainer = document.querySelector(
    ".wp-block-navigation__responsive-container"
  );

  if (openBtn && menuContainer) {
    openBtn.addEventListener("click", function (e) {
      e.preventDefault(); // prevent scroll to top
      menuContainer.classList.add("is-menu-open");
    });
  }

  if (closeBtn && menuContainer) {
    closeBtn.addEventListener("click", function (e) {
      e.preventDefault(); // prevent scroll to top
      menuContainer.classList.remove("is-menu-open");
    });
  }
});

// 4. Header scroll behavior
document.addEventListener("scroll", function () {
  const header = document.querySelector(".site-header");
  if (window.scrollY > 50) {
    header.classList.add("header-scrolled"); // Smooth expand when scrolling
  } else {
    header.classList.remove("header-scrolled"); // Return to default height
  }
});
