CREATE DATABASE IF NOT EXISTS internship_db;
USE internship_db;

CREATE TABLE IF NOT EXISTS categories (
    id INT AUTO_INCREMENT PRIMARY KEY,
    name VARCHAR(100) NOT NULL UNIQUE,
    icon VARCHAR(50) -- FontAwesome icon class
);

CREATE TABLE IF NOT EXISTS internships (
    id INT AUTO_INCREMENT PRIMARY KEY,
    title VARCHAR(255) NOT NULL,
    company VARCHAR(100) NOT NULL,
    location VARCHAR(100) NOT NULL,
    category_id INT,
    stipend VARCHAR(50),
    duration VARCHAR(50),
    posted_date DATE,
    description TEXT,
    apply_link VARCHAR(255),
    logo_url VARCHAR(255),
    FOREIGN KEY (category_id) REFERENCES categories(id)
);

-- Seed data for categories
INSERT IGNORE INTO categories (name, icon) VALUES 
('IT & Software', 'fas fa-code'),
('Marketing', 'fas fa-bullhorn'),
('Finance', 'fas fa-chart-line'),
('Design', 'fas fa-paint-brush'),
('Engineering', 'fas fa-cog');

CREATE TABLE IF NOT EXISTS users (
    id INT AUTO_INCREMENT PRIMARY KEY,
    username VARCHAR(50) NOT NULL UNIQUE,
    email VARCHAR(100) NOT NULL UNIQUE,
    password VARCHAR(255) NOT NULL,
    created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP
);

CREATE TABLE IF NOT EXISTS applications (
    id INT AUTO_INCREMENT PRIMARY KEY,
    user_id INT NOT NULL,
    internship_id INT NOT NULL,
    full_name VARCHAR(100) NOT NULL,
    email VARCHAR(100) NOT NULL,
    phone VARCHAR(20),
    qualifications TEXT,
    resume_path VARCHAR(255),
    cover_letter TEXT,
    status ENUM('pending', 'reviewed', 'accepted', 'rejected') DEFAULT 'pending',
    applied_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
    FOREIGN KEY (user_id) REFERENCES users(id),
    FOREIGN KEY (internship_id) REFERENCES internships(id)
);

-- Seed data for internships
INSERT IGNORE INTO internships (title, company, location, category_id, stipend, duration, posted_date, description, apply_link, logo_url) VALUES 
('Frontend Developer Intern', 'Tech Lanka Solutons', 'Colombo', 1, 'Rs. 25,000', '6 Months', '2024-01-15', 'Looking for a passionate frontend intern with knowledge in HTML, CSS, and JS.', '#', 'https://via.placeholder.com/100'),
('Digital Marketing Intern', 'Creative Minds', 'Kandy', 2, 'Rs. 15,000', '3 Months', '2024-01-18', 'Join our marketing team to manage social media and SEO.', '#', 'https://via.placeholder.com/100'),
('UI/UX Design Intern', 'Design Pro', 'Galle', 4, 'Unpaid', '4 Months', '2024-01-20', 'Learn how to create stunning user interfaces and experiences.', '#', 'https://via.placeholder.com/100'),
('Software Engineer Intern', 'Global Tech', 'Remote', 1, 'Rs. 30,000', '6 Months', '2024-01-10', 'Experience in Java or Python is preferred.', '#', 'https://via.placeholder.com/100');
