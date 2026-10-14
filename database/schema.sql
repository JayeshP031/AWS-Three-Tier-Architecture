CREATE DATABASE IF NOT EXISTS registration_db
  CHARACTER SET utf8mb4
  COLLATE utf8mb4_unicode_ci;

USE registration_db;

CREATE TABLE IF NOT EXISTS users (
    id INT AUTO_INCREMENT PRIMARY KEY,
    username VARCHAR(50) NOT NULL UNIQUE,
    password VARCHAR(255) NOT NULL,
    mobile VARCHAR(10) NOT NULL
);
