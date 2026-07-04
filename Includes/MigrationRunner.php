<?php
namespace Includes;

class MigrationRunner {
    private \PDO $pdo;
    private array $registered_apps;

    public function __construct(\PDO $pdo, array $registered_apps) {
        $this->pdo = $pdo;
        $this->registered_apps = $registered_apps;
    }

    public function run(): void {
        foreach ($this->registered_apps as $app) {
            $this->run_app($app);
        }
    }

    private function run_app(string $app): void {
        $migrations_dir = APPS_PATH . '/' . $app . '/Migrations';

        if (!is_dir($migrations_dir)) {
            return;
        }

        $files = glob($migrations_dir . '/*.php');
        sort($files); // sort by name and number

        foreach ($files as $file) {
            /** @var Migration $migration */
            $migration = require $file;

            if ($this->is_applied($migration)) {
                continue;
            }

            $this->apply($migration);
        }
    }

    private function is_applied(Migration $migration): bool {
        $info = $migration->getInformation();

        $stmt = $this->pdo->prepare(
            'SELECT COUNT(*) FROM migrations WHERE app = :app AND name = :name'
        );
        $stmt->execute($info);

        return (bool) $stmt->fetchColumn();
    }

    private function apply(Migration $migration): void {
        $info = $migration->getInformation();

        $this->pdo->beginTransaction();
        try {
            foreach ($migration->getSQL() as $query) {
                $this->pdo->exec($query);
            }

            $stmt = $this->pdo->prepare(
                'INSERT INTO migrations (app, name) VALUES (:app, :name)'
            );
            $stmt->execute($info);

            if ($this->pdo->inTransaction()) {
                $this->pdo->commit();
            }
            echo "Applied: {$info['app']}/{$info['name']}\n";
        } catch (\PDOException $e) {
            if ($this->pdo->inTransaction()) {
                $this->pdo->rollBack();
            }
            throw $e;
        }
    }
}