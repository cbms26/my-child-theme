# JCUBSB WordPress Child Theme – Project Repository

This repository contains the custom WordPress child theme developed for the **JCUB Student Board Website**, created as part of our Master's ICT Project at James Cook University Brisbane.

## 📁 Project Overview
The website serves as a digital platform for the JCUB Student Board to:
- Manage events and student registrations
- Share student resources and updates
- Streamline board-to-student communication

Developed using **WordPress**, with a custom child theme built on the Twenty Twenty-Five theme.

## 🛠️ Technologies Used
- WordPress (CMS)
- PHP
- HTML / CSS / JavaScript
- ACF (Advanced Custom Fields)
- Smart Slider 3
- The Events Calendar Plugin
- PHPUnit (for TDD)
- GitHub Actions (CI/CD)
- Local by Flywheel (Local development)
- AWS Lightsail (Deployment)

## 🚀 Key Features
- Custom Event Registration Forms
- Dynamic Event Calendar (via The Events Calendar)
- Hero Section for Announcements (via Smart Slider 3)
- Responsive Layout using Elementor and custom CSS
- Unit Testing with PHPUnit (See `/tests` folder)
- CI/CD with GitHub Actions

## 📂 Folder Structure
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
