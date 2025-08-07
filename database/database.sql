-- Create database
CREATE DATABASE IF NOT EXISTS astrology_db;
USE astrology_db;

-- Users table (for all users including admins, astrologers, and normal users)
CREATE TABLE users (
    id INT AUTO_INCREMENT PRIMARY KEY,
    name VARCHAR(100) NOT NULL,
    email VARCHAR(100) NOT NULL UNIQUE,
    password VARCHAR(255) NOT NULL,
    role ENUM('admin', 'astrologer', 'user') NOT NULL DEFAULT 'user',
    created_at DATETIME DEFAULT CURRENT_TIMESTAMP,
    updated_at DATETIME DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP
);

-- Astrologers profile details (extra info)
CREATE TABLE astrologers (
    id INT AUTO_INCREMENT PRIMARY KEY,
    user_id INT NOT NULL,
    bio TEXT,
    expertise VARCHAR(255),
    profile_image VARCHAR(255),
    created_at DATETIME DEFAULT CURRENT_TIMESTAMP,
    updated_at DATETIME DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP,
    FOREIGN KEY (user_id) REFERENCES users(id) ON DELETE CASCADE
);

-- Horoscopes table
CREATE TABLE horoscopes (
    id INT AUTO_INCREMENT PRIMARY KEY,
    astrologer_id INT NOT NULL,
    zodiac_sign VARCHAR(50) NOT NULL,
    type ENUM('daily', 'weekly', 'monthly') NOT NULL,
    content TEXT NOT NULL,
    created_at DATETIME DEFAULT CURRENT_TIMESTAMP,
    updated_at DATETIME DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP,
    FOREIGN KEY (astrologer_id) REFERENCES astrologers(id) ON DELETE CASCADE
);

-- Zodiac signs table
CREATE TABLE zodiac_signs (
    id INT AUTO_INCREMENT PRIMARY KEY,
    name VARCHAR(50) NOT NULL UNIQUE,
    date_range VARCHAR(50) NOT NULL,
    element VARCHAR(50),
    modality VARCHAR(50),
    description TEXT,
    image VARCHAR(255),
    created_at DATETIME DEFAULT CURRENT_TIMESTAMP,
    updated_at DATETIME DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP
);

-- Appointments table
CREATE TABLE appointments (
    id INT AUTO_INCREMENT PRIMARY KEY,
    user_id INT NOT NULL,
    astrologer_id INT NOT NULL,
    appointment_date DATETIME NOT NULL,
    status ENUM('pending', 'confirmed', 'cancelled', 'completed') DEFAULT 'pending',
    created_at DATETIME DEFAULT CURRENT_TIMESTAMP,
    updated_at DATETIME DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP,
    FOREIGN KEY (user_id) REFERENCES users(id) ON DELETE CASCADE,
    FOREIGN KEY (astrologer_id) REFERENCES astrologers(id) ON DELETE CASCADE
);

-- Feedbacks table
CREATE TABLE feedbacks (
    id INT AUTO_INCREMENT PRIMARY KEY,
    user_id INT NOT NULL,
    astrologer_id INT DEFAULT NULL,
    feedback TEXT NOT NULL,
    rating INT CHECK (rating BETWEEN 1 AND 5),
    created_at DATETIME DEFAULT CURRENT_TIMESTAMP,
    FOREIGN KEY (user_id) REFERENCES users(id) ON DELETE CASCADE,
    FOREIGN KEY (astrologer_id) REFERENCES astrologers(id) ON DELETE SET NULL
);

-- Contacts table (for messages sent via contact form)
CREATE TABLE contacts (
    id INT AUTO_INCREMENT PRIMARY KEY,
    name VARCHAR(100) NOT NULL,
    email VARCHAR(100) NOT NULL,
    message TEXT NOT NULL,
    created_at DATETIME DEFAULT CURRENT_TIMESTAMP
);

-- Sample data for zodiac_signs (optional)
INSERT INTO zodiac_signs (name, date_range, element, modality, description, image) VALUES
('Aries', 'March 21 - April 19', 'Fire', 'Cardinal', 'Aries are courageous and energetic.', 'aries.png'),
('Taurus', 'April 20 - May 20', 'Earth', 'Fixed', 'Taurus are reliable and patient.', 'taurus.png'),
('Gemini', 'May 21 - June 20', 'Air', 'Mutable', 'Geminis are adaptable and outgoing.', 'gemini.png'),
('Cancer', 'June 21 - July 22', 'Water', 'Cardinal', 'Cancer is emotional and intuitive.', 'cancer.png'),
('Leo', 'July 23 - August 22', 'Fire', 'Fixed', 'Leo is confident and charismatic.', 'leo.png'),
('Virgo', 'August 23 - September 22', 'Earth', 'Mutable', 'Virgos are analytical and kind.', 'virgo.png'),
('Libra', 'September 23 - October 22', 'Air', 'Cardinal', 'Libra is diplomatic and gracious.', 'libra.png'),
('Scorpio', 'October 23 - November 21', 'Water', 'Fixed', 'Scorpio is resourceful and brave.', 'scorpio.png'),
('Sagittarius', 'November 22 - December 21', 'Fire', 'Mutable', 'Sagittarius is optimistic and adventurous.', 'sagittarius.png'),
('Capricorn', 'December 22 - January 19', 'Earth', 'Cardinal', 'Capricorn is disciplined and wise.', 'capricorn.png'),
('Aquarius', 'January 20 - February 18', 'Air', 'Fixed', 'Aquarius is innovative and independent.', 'aquarius.png'),
('Pisces', 'February 19 - March 20', 'Water', 'Mutable', 'Pisces are compassionate and artistic.', 'pisces.png');
