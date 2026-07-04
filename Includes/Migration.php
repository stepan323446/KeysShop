<?php
namespace Includes;

class Migration {
    private string $app;
    private string $name;
    private array $sql;

    public function __construct(string $app, string $name, array $sql) {
        $this->app = $app;
        $this->name = $name;
        $this->sql = $sql;
    }

    public function getInformation() {
        return [
            'app' => $this->app,
            'name' => $this->name
        ];
    }
    public function getSQL() {
        return $this->sql;
    }
}