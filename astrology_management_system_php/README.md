# Astrology Management System

## Overview
Astrology Management System is a web application built with PHP and MySQL that connects users with astrologers, provides horoscope details, and facilitates astrology-based services like appointment booking and feedback.

## Features
- User registration, login, and profile management
- Astrologer dashboard and horoscope management
- Admin panel for managing users, astrologers, horoscopes, and reports
- Zodiac signs listing and detailed descriptions
- Appointment booking and viewing system
- Contact form for users to reach out
- Export reports in PDF and Excel formats

## Project Structure
config/
includes/
auth/
admin/
astrologer/
user/
zodiac/
reports/
assets/
index.php
about.php
contact.php
README.md
markdown
CopyEdit

## Setup Instructions
1. Import the database schema from `database.sql`.
2. Update `config/db.php` with your database credentials.
3. Place the project files in your web server's root directory.
4. Ensure dependencies like TCPDF and PhpSpreadsheet are installed.
5. Access the site via your browser.

## Technologies Used
- PHP 7.x or above
- MySQL
- Bootstrap 5 (for UI styling)
- TCPDF (PDF generation)
- PhpSpreadsheet (Excel export)

## License
This project is open-source and free to use.
