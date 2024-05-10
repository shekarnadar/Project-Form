CREATE TABLE contact_form(
    id INT AUTO_INCREMENT PRIMARY KEY,
    full_name VARCHAR(100) NOT NULL,
    phone_number VARCHAR(20) NOT NULL,
    email VARCHAR(100) NOT NULL,
    subject VARCHAR(100) NOT NULL,
    message TEXT NOT NULL,
    ip_address VARCHAR(20) NOT NULL,
    time_stamp TIMESTAMP DEFAULT CURRENT_TIMESTAMP
);