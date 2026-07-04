<?php
// Includes/bootstrap_migrations.php

function ensure_migrations_table(\PDO $pdo): void {
    $pdo->exec(
        'CREATE TABLE IF NOT EXISTS migrations (
            id      INT AUTO_INCREMENT PRIMARY KEY,
            app     VARCHAR(255) NOT NULL,
            name    VARCHAR(255) NOT NULL,
            applied DATETIME NOT NULL DEFAULT CURRENT_TIMESTAMP,
            UNIQUE KEY unique_migration (app, name)
        )'
    );
}