-- Create the database
CREATE DATABASE IF NOT EXISTS travel_portal;
USE travel_portal;

-- Create users table
CREATE TABLE users (
    id INT PRIMARY KEY AUTO_INCREMENT,
    username VARCHAR(50) UNIQUE NOT NULL,
    password VARCHAR(255) NOT NULL,
    email VARCHAR(100) UNIQUE NOT NULL,
    full_name VARCHAR(100) NOT NULL,
    role ENUM('admin', 'user') DEFAULT 'user',
    created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP
);

-- Create tasks table
CREATE TABLE tasks (
    id INT PRIMARY KEY AUTO_INCREMENT,
    short_description VARCHAR(100) NOT NULL,
    long_description TEXT NOT NULL,
    deadline DATETIME NOT NULL,
    status ENUM('pending', 'completed') DEFAULT 'pending',
    assigned_to INT,
    created_by INT,
    created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
    FOREIGN KEY (assigned_to) REFERENCES users(id),
    FOREIGN KEY (created_by) REFERENCES users(id)
);

-- Create emergency_contacts table
CREATE TABLE emergency_contacts (
    id INT PRIMARY KEY AUTO_INCREMENT,
    name VARCHAR(100) NOT NULL,
    phone_number VARCHAR(20) NOT NULL,
    description TEXT,
    created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP
);

-- Insert default admin user (password: admin123)
INSERT INTO users (username, password, email, full_name, role) 
VALUES ('admin', '$2y$10$92IXUNpkjO0rOQ5byMi.Ye4oKoEa3Ro9llC/.og/at2.uheWG/igi', 'admin@travelportal.com', 'Administrator', 'admin');

-- Insert some emergency contacts
INSERT INTO emergency_contacts (name, phone_number, description) VALUES
('Police Emergency', '100', '24/7 Police Emergency Services'),
('Tourist Helpline', '1363', '24/7 Tourist Information and Assistance'),
('Medical Emergency', '108', '24/7 Medical Emergency Services'); 