CREATE DATABASE homework;

USE homework;

CREATE TABLE data (
    id INT AUTO_INCREMENT PRIMARY KEY,
    name VARCHAR(50),
    age INT,
    email VARCHAR(100)
);

INSERT INTO data (name, age, email) VALUES
('Alice', 25, 'alice@example.com'),
('Bob', 30, 'bob@example.com'),
('Charlie', 22, 'charlie@example.com');
