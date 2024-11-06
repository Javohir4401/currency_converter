CREATE DATABASE homework;

USE homework;

CREATE TABLE time (
    id INT AUTO_INCREMENT PRIMARY KEY,
    name VARCHAR(50) NOT NULL,
    arrived_at DATETIME NOT NULL,
    left_at DATETIME NOT NULL,
    required_of INT NOT NULL,
    worked_seconds INT
);