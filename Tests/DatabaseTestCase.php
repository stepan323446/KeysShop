<?php
namespace Tests;

use PHPUnit\Framework\TestCase;

class DatabaseTestCase extends TestCase {
    protected function setUp(): void {
        global $pdo;
        $pdo->beginTransaction();
    }
    
    protected function tearDown(): void {
        global $pdo;
        if ($pdo->inTransaction()) {
            $pdo->rollBack();
        }
    }
}