# JCUBSB WordPress Child Theme – Project Repository

This repository contains only the custom WordPress child theme developed for the **JCUB Student Board Website**, created as part of our Master's ICT Project at James Cook University Brisbane (CP5046 & CP5047).

## Project Overview

The website serves as a digital platform for the JCUB Student Board to:
- Manage events and student registrations
- Share student resources and updates
- Streamline board-to-student communication

Built using **WordPress** and developed with Agile/XP methodologies across six iterations, the project emphasizes functionality, responsiveness, and user-friendly content management with minor adjustment needed.

## Related Repository

This repository contains only the **custom child theme** developed for the JCUB Student Board Website.

To access the full local WordPress site (including plugins, uploads, and full `/app` structure), visit the main project repository here:

👉 [JCUBSB Local WordPress Site Repository](https://github.com/cbms26/wordpress-project-jcubsb)

## 🗂️ Database Dump (local.sql)

You can download the SQL file directly from this repository:

🔽 [Download local.sql](https://github.com/cbms26/wordpress-project-jcubsb/blob/main/local.sql)

Once downloaded, import it into your local database using:

```bash
mysql -u root -p bitnami_wordpress < /path/to/local.sql
```

## Technologies Used

- **WordPress** (CMS)
- **PHP**, **HTML**, **CSS**, **JavaScript**
- **Smart Slider 3**
- **The Events Calendar Plugin**
- **PHPUnit** (for Test-Driven Development)
- **GitHub Actions** (CI/CD Pipeline)
- **Local by Flywheel** (Local development)
- **AWS Lightsail** (Live deployment)

## Key Features

- Dynamic Event Management System  
- Student Registration Forms with Email Confirmation  
- Hero Carousel for Announcements (Smart Slider 3)  
- Custom Shortcodes for Event Display  
- Responsive Layout using Elementor & Custom Media Queries  
- PHPUnit Testing with 8 Tests & 26 Assertions  
- Continuous Integration via GitHub Actions  
- Admin panel configured for non-technical users

## 📂 Folder Structure

```plaintext
my-child-theme/
├── assets/                   # Custom assets (images, fonts, etc.)
├── functions.php             # Theme functions (enqueue scripts, shortcodes)
├── header.php                # Custom header section
├── footer.php                # Footer layout and content
├── style.css                 # Child theme styling
├── script.js                 # JavaScript for interactivity
├── tests/                    # PHPUnit test cases
├── vendor/                   # Composer dependencies
└── .github/workflows/        # GitHub Actions CI configuration
```

## Running Unit Tests

To run PHPUnit tests locally:

```bash
vendor/bin/phpunit wp-content/themes/my-child-theme/tests/ThemeFunctionsTest.php
```

## Development Summary

- **6 Iterations Completed**: Development was structured using Agile XP practices across six iterations, allowing for incremental releases and continuous refinement.
- **Unit Testing with PHPUnit**: 8 test cases and 26 assertions were implemented. Initially, 3 failed due to shortcode rendering and missing hooks, but all were resolved successfully.
- **Continuous Integration via GitHub Actions**: Every push triggered automated tests, identifying issues like PHP version conflicts, missing composer files, and directory errors — all fixed in CI setup.
- **Performance Optimization**: Implemented caching, minification, and lazy loading using W3 Total Cache, resulting in faster load times and smoother navigation.
- **Responsive and Cross-Device Testing**: Media queries were added to address issues across various devices. Manual testing confirmed compatibility on desktop, tablet, and mobile views.
- **Stakeholder Feedback Integration**: Regular feedback sessions with Mr. Quentin Underhill guided major UI updates, QR code-based registration, and homepage layout improvements.
- **Velocity and Burndown Tracking**: Sprint progress was visualized through charts, helping to monitor backlog, resolve bottlenecks, and ensure timely delivery (see Appendix B in the report).

## Admin Access & Security

Admin login credentials are **not included** in this repository for privacy and security reasons.

For demo or internal review access, please contact the development team directly.

## Contributors

- **Pema Chezom**  
- **Dorji Norbu**  
- **Ngawang Tenzin**  
- **Karma Wangchuk**  
- **Kinley Wangdi**

## Live Demo (If applicable)

> Not publicly deployed. The final website was hosted on **AWS Lightsail** and used internally at James Cook University Brisbane for academic purposes.

## License

This project was developed solely for **academic submission** as part of the CP5047 unit at James Cook University Brisbane.  
It is **not intended for commercial use or redistribution**.

All code, assets, and configurations are protected and should not be reused outside the project context without written consent.


 
