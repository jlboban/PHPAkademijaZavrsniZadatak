DROP DATABASE IF EXISTS `eventZone`;
CREATE DATABASE `eventZone` CHARACTER SET UTF8 COLLATE UTF8_GENERAL_CI;
USE `eventZone`;

CREATE TABLE `user`(
    id INT PRIMARY KEY AUTO_INCREMENT NOT NULL,
    first_name VARCHAR(255) NOT NULL,
    last_name VARCHAR(255) NOT NULL,
    email VARCHAR(255) NOT NULL,
    password CHAR(60) NOT NULL,
    is_admin TINYINT NOT NULL DEFAULT 0,
    address VARCHAR(255),
    city VARCHAR(255),
    postcode VARCHAR(20),
    country VARCHAR(255)
);

CREATE TABLE `event`(
    id INT PRIMARY KEY AUTO_INCREMENT NOT NULL,
    name VARCHAR(255) NOT NULL,
    description TEXT NOT NULL,
    start_date DATE NOT NULL,
    end_date DATE,
    start_time TIME NOT NULL,
    end_time TIME,
    price DECIMAL(10, 2) DEFAULT 100,
    discount DECIMAL(3, 2) DEFAULT 0 CHECK (discount <= 100 AND discount >= 0),
    image VARCHAR(255) NOT NULL DEFAULT "assets/eventzone_logo_gradient.png"
);

CREATE TABLE `musician`(
    id INT PRIMARY KEY AUTO_INCREMENT NOT NULL,
    name VARCHAR(255) NOT NULL,
    genre VARCHAR(255),
    description TEXT,
    image VARCHAR(255) DEFAULT "assets/eventzone_logo_gradient.png",
    spotify VARCHAR(255)
);

CREATE TABLE `event_musician`(
    event_id INT NOT NULL,
    musician_id INT NOT NULL,
    FOREIGN KEY(event_id) REFERENCES event(id),
    FOREIGN KEY(musician_id) REFERENCES musician(id)
);

CREATE TABLE `venue`(
    id INT PRIMARY KEY AUTO_INCREMENT NOT NULL,
    name VARCHAR(255) NOT NULL,
    capacity INT NOT NULL,
    image VARCHAR(255) NOT NULL DEFAULT "assets/eventzone_logo_gradient.png",
    address VARCHAR(255) NOT NULL,
    city VARCHAR(255) NOT NULL,
    postcode VARCHAR(20) NOT NULL,
    country VARCHAR(255) NOT NULL
);

CREATE TABLE `event_venue`(
    event_id INT NOT NULL,
    venue_id INT NOT NULL,
    FOREIGN KEY(event_id) REFERENCES event(id),
    FOREIGN KEY(venue_id) REFERENCES venue(id)
);

CREATE TABLE `booking`(
    id INT PRIMARY KEY AUTO_INCREMENT NOT NULL,
    user_id INT NOT NULL,
    event_id INT NOT NULL,
    order_time DATETIME NOT NULL DEFAULT CURRENT_TIMESTAMP,
    final_price DECIMAL NOT NULL,
    FOREIGN KEY (user_id) REFERENCES user(id),
    FOREIGN KEY (event_id) REFERENCES event(id)
);

# admin@eventzone.com
# Admin12345
INSERT INTO user(first_name, last_name, email, password, is_admin)
VALUES('EventZone', 'Admin', 'admin@eventzone.com', '$2y$10$I44uZTs/fIQilZeLKXNPkumSXpSsCs4K1NamZW7sIy4W5HwrSlsIS', 1);
