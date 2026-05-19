-- Create database
CREATE DATABASE IF NOT EXISTS wasteflow;
USE wasteflow;

-- Users table
CREATE TABLE IF NOT EXISTS users (
  id INT AUTO_INCREMENT PRIMARY KEY,
  name VARCHAR(100) NOT NULL,
  purok VARCHAR(50),
  contact_number VARCHAR(20) UNIQUE NOT NULL,
  password VARCHAR(255) NOT NULL,
  role ENUM('admin','resident') NOT NULL DEFAULT 'resident'
);

-- Schedules table (with activity column)
CREATE TABLE IF NOT EXISTS schedules (
  id INT AUTO_INCREMENT PRIMARY KEY,
  purok VARCHAR(50),
  activity VARCHAR(100) NOT NULL, -- activity field
  collection_date DATE,
  status VARCHAR(50),
  tag VARCHAR(50)
);

-- Reports table (resident submissions)
CREATE TABLE IF NOT EXISTS reports (
  id INT AUTO_INCREMENT PRIMARY KEY,
  resident_id INT,
  purok VARCHAR(50),
  description TEXT,
  status VARCHAR(50) DEFAULT 'Pending', -- changed from ENUM to VARCHAR
  tag VARCHAR(50),
  created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
  FOREIGN KEY (resident_id) REFERENCES users(id)
);

-- Admin Responses table (tracks admin feedback on reports)
CREATE TABLE IF NOT EXISTS report_responses (
  id INT AUTO_INCREMENT PRIMARY KEY,
  report_id INT NOT NULL,
  admin_id INT NOT NULL,
  response VARCHAR(50) NOT NULL, -- changed from ENUM to VARCHAR
  remarks TEXT,
  responded_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
  FOREIGN KEY (report_id) REFERENCES reports(id),
  FOREIGN KEY (admin_id) REFERENCES users(id)
);

-- Notifications table
CREATE TABLE IF NOT EXISTS notifications (
  id INT AUTO_INCREMENT PRIMARY KEY,
  title VARCHAR(100),
  content TEXT,
  tag VARCHAR(50),
  created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP
);

-- Education table
CREATE TABLE IF NOT EXISTS education (
  id INT AUTO_INCREMENT PRIMARY KEY,
  title VARCHAR(100),
  content TEXT,
  tag VARCHAR(50),
  created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP
);

-- Insert Admin account (password = admin123)
INSERT INTO users (name, purok, contact_number, password, role)
VALUES ('Admin User', 'Purok 1', '09123456789',
        '$2y$10$92IXUNpkjO0rOQ5byMi.Ye4oKoEa3Ro9z7rj2q8z5p4aENa9X9l2u', 'admin');

-- Insert Resident account (password = resident123)
INSERT INTO users (name, purok, contact_number, password, role)
VALUES ('Resident User', 'Purok 2', '09987654321',
        '$2y$10$92IXUNpkjO0rOQ5byMi.Ye4oKoEa3Ro9z7rj2q8z5p4aENa9X9l2u', 'resident');
