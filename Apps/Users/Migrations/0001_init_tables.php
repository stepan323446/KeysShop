<?php
use Includes\Migration;

return new Migration(
    'Users',
    '0001_init_tables',
    [
        'CREATE TABLE users (
            id INT AUTO_INCREMENT PRIMARY KEY,
            username VARCHAR(20) UNIQUE NOT NULL,
            email VARCHAR(40) UNIQUE NOT NULL,
            fname VARCHAR(20) NULL,
            lname VARCHAR(20) NULL,
            password VARCHAR(255) NOT NULL,
            is_admin TINYINT(1) DEFAULT 0,

            register_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP
        );',
        'CREATE TABLE recovery_password (
            id INT AUTO_INCREMENT PRIMARY KEY,

            user_id INT NOT NULL,
            recovery_slug VARCHAR(255) UNIQUE NOT NULL,
            is_used TINYINT(1) DEFAULT 0,
            created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP,

            FOREIGN KEY (user_id) REFERENCES users(id) ON DELETE CASCADE
        );'
    ]
);