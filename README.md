# 🌟 Astrology Management System

## 📌 Overview
The **Astrology Management System** is a full-featured web application developed using **PHP** and **MySQL**. It enables users to explore astrological services, connect with astrologers, view horoscopes, and book appointments. Admins and astrologers have access to dedicated dashboards to manage services, users, and content.

## ✨ Features
- 🔐 User registration, login & profile management  
- 🧙 Astrologer dashboard with horoscope management tools  
- 🛠️ Admin panel to manage users, astrologers, horoscopes, appointments, and reports  
- ♈ Zodiac signs listing with detailed descriptions  
- 📅 Appointment booking and viewing system  
- 📬 Contact form to reach the system admin  

## 📁 Project Structure
astrology_management_system/
│
├── admin/ # Admin panel files
├── astrologer/ # Astrologer dashboard
├── auth/ # Login & registration scripts
├── config/ # Database configuration
├── includes/ # Header, footer, and shared UI
├── reports/ 
├── user/ # User dashboard
├── zodiac/ # Zodiac sign pages
├── assets/ # Images, CSS, JS, etc.
│
├── index.php
├── about.php
├── contact.php
├── database.sql # MySQL database dump
└── README.md


## ⚙️ Setup Instructions

1. 🔽 **Clone the Repository**
   git clone https://github.com/yourusername/astrology_management_system_php.git
🗃️ Import the Database

Open phpMyAdmin or another MySQL tool.

Import the database.sql file from the project root.

🔧 Configure Database Connection

Open config/db.php

Set your own database host, username, password, and database name:

$conn = new mysqli("localhost", "root", "", "astrology_db");
🌐 Run the Application

Place the project in your web server directory (e.g., htdocs for XAMPP).

Start Apache and MySQL.

Visit http://localhost/astrology_management_system/ in your browser.

🧪 Default Credentials
Role	email	Password
Admin	admin	12345678
Astrologer	astro@example.com	12345678
User	user1@gmail.com	12345678

⚠️ Change the default credentials after first login.

🛠️ Technologies Used
PHP 7.x or above
MySQL
Bootstrap 5

🎥 YouTube Channel
📺 Watch tutorials and walkthroughs of this project on my channel:
https://www.youtube.com/@ExcelCodebyRASHMIMAM
👉 Don’t forget to Like, Share, and Subscribe!

## 📝 License
This project is open-source and free to use under the MIT License.

🔧 Developed with ❤️ by Rashmi Prava Mishra.
