<?php
use Includes\Migration;

return new Migration(
    'Products',
    '0001_init_tables',
    [
        'CREATE TABLE taxonomies (
            id INT AUTO_INCREMENT PRIMARY KEY,
            name VARCHAR(50) NOT NULL,
            type VARCHAR(50) NOT NULL,
            slug VARCHAR(50) NOT NULL,

            icon_html TEXT NULL,
            background_color VARCHAR(20) NULL
        );',
        'CREATE TABLE products (
            id          INT AUTO_INCREMENT PRIMARY KEY,
            title       VARCHAR(50) NOT NULL,
            slug        VARCHAR(50) NOT NULL,
            excerpt     VARCHAR(250) NOT NULL,
            poster_url   VARCHAR(255) NULL,
            image_url   VARCHAR(255) NULL,

            description TEXT NOT NULL,
            original_url VARCHAR(255) NOT NULL,
            original_price FLOAT NOT NULL,
            edition     VARCHAR(50) DEFAULT "Standart edition",
            sales       INT DEFAULT 0,

            created_at  TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
            updated_at  TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
            platform_id INT NOT NULL,
            region_id   INT NOT NULL,

            FOREIGN KEY (platform_id) REFERENCES taxonomies(id) ON DELETE RESTRICT,
            FOREIGN KEY (region_id) REFERENCES taxonomies(id) ON DELETE RESTRICT
        );',
        'CREATE TABLE product_keys (
            id          INT AUTO_INCREMENT PRIMARY KEY,
            product_id  INT NOT NULL,
            key_code    VARCHAR(255) NOT NULL,
            price       FLOAT NOT NULL,
            original_price FLOAT NOT NULL,
            order_id    INT NULL,

            bought_at   TIMESTAMP NULL,
            created_at  TIMESTAMP DEFAULT CURRENT_TIMESTAMP,

            FOREIGN KEY (product_id) REFERENCES products(id) ON DELETE CASCADE
        );',
        'CREATE TABLE wishlist (
            id          INT AUTO_INCREMENT PRIMARY KEY,
            product_id  INT NOT NULL,
            user_id     INT NOT NULL,

            FOREIGN KEY (product_id) REFERENCES products(id) ON DELETE CASCADE,
            FOREIGN KEY (user_id) REFERENCES users(id) ON DELETE CASCADE
        );'
    ]
);