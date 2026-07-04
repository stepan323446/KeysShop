<?php
use Includes\Migration;

return new Migration(
    'Order',
    '0001_init_tables',
    [
        'CREATE TABLE orders (
            id INT AUTO_INCREMENT PRIMARY KEY,
            order_number VARCHAR(255) NOT NULL,
            method VARCHAR(50) NOT NULL,
            created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
            user_id    INT NOT NULL,

            FOREIGN KEY (user_id) REFERENCES users(id) ON DELETE RESTRICT
        );',
    ]
);