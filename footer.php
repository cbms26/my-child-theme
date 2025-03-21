<?php
/**
 * Footer Template - Traditional PHP Version
 * Matches the design with Quick Links, Contact, Newsletter, and Legal Info.
 *
 * @package WordPress
 * @subpackage Twenty_Twenty_Five_Child
 * @since Twenty Twenty-Five 1.0
 */
?>

<footer class="site-footer">
    <div class="footer-container">
        
        <!-- Logo & Social Media -->
        <div class="footer-branding">
            <?php if ( function_exists( 'the_custom_logo' ) ) {
                the_custom_logo();
            } ?>
            <div class="footer-social">
                <a href="#" target="_blank"><i class="fab fa-facebook"></i></a>
                <a href="#" target="_blank"><i class="fab fa-instagram"></i></a>
                <a href="#" target="_blank"><i class="fab fa-youtube"></i></a>
            </div>
        </div>

        <!-- Footer Navigation Sections -->
        <div class="footer-nav-sections">
            <div class="footer-column">
                <h3>Quick Links</h3>
                <?php
                wp_nav_menu(array(
                    'theme_location' => 'footer_quick_links',
                    'menu_class'     => 'footer-links',
                    'container'      => 'nav',
                    'fallback_cb'    => false
                ));
                ?>
            </div>
            
            <div class="footer-column">
                <h3>Student Board</h3>
                <?php
                wp_nav_menu(array(
                    'theme_location' => 'footer_student_board',
                    'menu_class'     => 'footer-links',
                    'container'      => 'nav',
                    'fallback_cb'    => false
                ));
                ?>
            </div>
            
            <div class="footer-column">
                <h3>Contact</h3>
                <p>Level 2/349 Queen Street, Brisbane City QLD 4000</p>
                <p><a href="mailto:brisbanelibrary@jcub.edu.au">brisbanelibrary@jcub.edu.au</a></p>
            </div>
        </div>

        <!-- Newsletter Signup -->
        <div class="footer-newsletter">
            <h3>Follow the latest trends</h3>
            <p>With our daily newsletter</p>
            <form action="#" method="post">
                <input type="email" name="email" placeholder="you@example.com" required>
                <button type="submit">Submit</button>
            </form>
        </div>

        <!-- Legal & Copyright Section -->
        <div class="footer-bottom">
            <div class="footer-logos">
                <img src="path-to-award-logo.png" alt="Athena SWAN Bronze Award">
                <img src="path-to-university-logo.png" alt="Universities Australia">
                <img src="path-to-tropics-logo.png" alt="State of the Tropics">
            </div>

            <div class="footer-text">
                <p>&copy; James Cook University 1995 to <?php echo date("Y"); ?> | CRICOS Provider Code 00117J | ABN 46252311955</p>
                <p><a href="#">Terms of Use</a> | <a href="#">Privacy</a> | <a href="#">Disclaimer</a></p>
            </div>

            <div class="footer-acknowledgement">
                <p>We acknowledge Aboriginal People and Torres Strait Islander People as the first inhabitants of the nation, and acknowledge Traditional Custodians of the Australian lands where our staff and students live, learn, and work. Aboriginal and Torres Strait Islander peoples are advised that this site may contain names, images or voices of people who have passed away.</p>
            </div>
        </div>

    </div>
</footer>

<?php wp_footer(); ?>
</body>
</html>
