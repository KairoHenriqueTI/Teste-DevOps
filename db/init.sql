CREATE DATABASE IF NOT EXISTS testdb CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci;
USE testdb;

CREATE TABLE IF NOT EXISTS greetings (
  id INT AUTO_INCREMENT PRIMARY KEY,
  message VARCHAR(255) NOT NULL
);

INSERT INTO greetings (message) VALUES ('Ola, mundooo');
