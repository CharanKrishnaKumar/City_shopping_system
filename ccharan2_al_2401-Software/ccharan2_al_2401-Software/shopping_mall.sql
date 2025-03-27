
CREATE DATABASE shopping_mall;

USE shopping_mall;

-- Admin Table
CREATE TABLE admin (
    id INT AUTO_INCREMENT PRIMARY KEY,
    username VARCHAR(50) NOT NULL,
    password VARCHAR(255) NOT NULL
);

-- Users Table
CREATE TABLE users (
    id INT AUTO_INCREMENT PRIMARY KEY,
    name VARCHAR(100),
    email VARCHAR(100) UNIQUE,
    password VARCHAR(255),
    created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP
);

-- Products Table
CREATE TABLE products (
    id INT AUTO_INCREMENT PRIMARY KEY,
    name VARCHAR(255),
    category VARCHAR(100),
    image VARCHAR(255),
    description TEXT,
    price DECIMAL(10,2),
    quantity INT,
    created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP
);

-- Basket Table
CREATE TABLE basket (
    id INT AUTO_INCREMENT PRIMARY KEY,
    user_id INT,
    product_id INT,
    quantity INT,
    FOREIGN KEY (user_id) REFERENCES users(id),
    FOREIGN KEY (product_id) REFERENCES products(id)
);

CREATE TABLE orders (
    id INT AUTO_INCREMENT PRIMARY KEY,
    user_email VARCHAR(255) NOT NULL,
    fullname VARCHAR(255) NOT NULL,
    address TEXT NOT NULL,
    phone VARCHAR(20) NOT NULL,
    items TEXT NOT NULL,
    total_amount DECIMAL(10,2) NOT NULL,
    payment_method VARCHAR(50) NOT NULL,
    order_date DATETIME NOT NULL
);


CREATE TABLE events (
    id INT AUTO_INCREMENT PRIMARY KEY,
    title VARCHAR(255),
    description TEXT,
    event_date DATE,
    location VARCHAR(255),
    image VARCHAR(255),
    created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP
);

CREATE TABLE dining (
    id INT AUTO_INCREMENT PRIMARY KEY,
    name VARCHAR(255),
    description TEXT,
    location VARCHAR(255),
    image VARCHAR(255),
    created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP
);

CREATE TABLE event_bookings (
    id INT AUTO_INCREMENT PRIMARY KEY,
    user_email VARCHAR(255),
    event_id INT,
    guest_name VARCHAR(255),
    age INT,
    mobile VARCHAR(15),
    quantity INT,
    total_price DECIMAL(10,2),
    booking_date TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
    FOREIGN KEY (event_id) REFERENCES events(id),
    FOREIGN KEY (user_email) REFERENCES users(email)
);

CREATE TABLE booking_dining (
    id INT AUTO_INCREMENT PRIMARY KEY,
    user_email VARCHAR(255),
    dining_id INT,
    guest_name VARCHAR(255),
    age INT,
    mobile VARCHAR(15),
    dining_date DATE,
    quantity INT,
    total_price DECIMAL(10,2),
    booking_date TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
    FOREIGN KEY (dining_id) REFERENCES dining(id)
);


-- Insert Default Admin
INSERT INTO admin (username, password) VALUES ('admin', MD5('admin1234'));


ALTER TABLE users 
ADD COLUMN fullname VARCHAR(255) AFTER name,
ADD COLUMN address TEXT AFTER fullname,
ADD COLUMN phone VARCHAR(20) AFTER address;

ALTER TABLE orders 
ADD COLUMN status ENUM('Confirmed', 'Canceled') DEFAULT 'Confirmed';

ALTER TABLE products ADD COLUMN offer_price DECIMAL(10,2) DEFAULT NULL;


ALTER TABLE events ADD COLUMN price DECIMAL(10,2) NOT NULL DEFAULT 0.00;
ALTER TABLE events ADD COLUMN offer_price DECIMAL(10,2) NOT NULL DEFAULT 0.00;

ALTER TABLE dining ADD COLUMN price DECIMAL(10,2) NOT NULL DEFAULT 0.00;
ALTER TABLE dining ADD COLUMN offer_price DECIMAL(10,2) NOT NULL DEFAULT 0.00;
