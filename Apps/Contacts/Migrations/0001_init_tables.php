<?php
use Includes\Migration;

return new Migration(
    'Contacts',
    '0001_init_tables',
    [
        'CREATE TABLE feedbacks (
            id INT AUTO_INCREMENT PRIMARY KEY,
            name VARCHAR(50) NOT NULL,
            email VARCHAR(50) NOT NULL,
            content TEXT NOT NULL,
            is_read TINYINT(1) DEFAULT 0,
            created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP
        );',
    ]
);