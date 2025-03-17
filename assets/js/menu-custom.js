document.addEventListener("DOMContentLoaded", function () {
    let menuContainer = document.querySelector('.wp-block-navigation__responsive-container-content');

    if (menuContainer) {
        // ✅ Create Logo Element
        let logoElement = document.createElement("img");
        logoElement.src = "/wp-content/themes/my-child-theme/assets/images/bw-logo.png"; // Change YOUR-THEME to your theme folder
        logoElement.alt = "Website Logo";
        logoElement.className = "menu-logo";

        // ✅ Create Search Form Element
        let searchElement = document.createElement("div");
        searchElement.innerHTML = '<form role="search" method="get" class="search-form" action="/"><input type="search" class="search-field" placeholder="Quick Search" name="s"><button type="submit" class="search-submit">🔍</button></form>';
        searchElement.className = "menu-search";

        // ✅ Create Wrapper
        let wrapper = document.createElement("div");
        wrapper.className = "menu-header-container";
        wrapper.appendChild(logoElement);
        wrapper.appendChild(searchElement);

        // ✅ Insert Above the Menu Items
        menuContainer.prepend(wrapper);
    }
});

console.log("✅ menu-custom.js is loaded successfully!"); 
