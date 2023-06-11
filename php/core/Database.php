<?php
namespace app\core;

class Database {
    public $pdo;
    public function __construct($config) {
        $dsn = $config['dsn'] ?? '';
        $user = $config['user'] ?? '';
        $password = $config['password'] ?? '';
        $this->pdo = new \PDO($dsn, $user, $password);
        $this->pdo->setAttribute(\PDO::ATTR_ERRMODE, \PDO::ERRMODE_EXCEPTION);
    }
    public function applyMigrations() {
        $this->createMigrationsTable();
        $appliedMigrations = $this->getAppliedMigrations();
        $files = scandir(Application::getRootDirectory()  . '/migrations');
        $toApplyMigrations = preg_grep('/m\d\d\d\d_.+\.php/', array_diff($files, $appliedMigrations));
        $newMigrations = array();
        foreach ($toApplyMigrations as $migration) {
            require_once Application::getRootDirectory() . '/migrations/' . $migration;
            $className = pathinfo($migration, PATHINFO_FILENAME);
            $instance = new $className();
            $this->log("Apply Migration $className");
            $instance->up();
            $newMigrations[] = $migration;
            $this->log("Applied Migraion $className");
        }
        if (!empty($newMigrations)) {
            $this->saveMigration($newMigrations);
        } else {
            $this->log("All Migrations Are Applied");
        }
    }
    public function createMigrationsTable() {
        $this->pdo->exec("CREATE TABLE IF NOT EXISTS migrations (
            id INT AUTO_INCREMENT PRIMARY KEY,
            migration VARCHAR(255),
            created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP
        ) ENGINE=innoDB;");
    }
    public function getAppliedMigrations() {
        $stmt = $this->pdo->prepare("SELECT migration FROM migrations;");
        $stmt->execute();
        return $stmt->fetchAll(\PDO::FETCH_COLUMN);
    }
    public function saveMigration($migrations) {
        $str = implode(', ', array_map(fn($m) => "('$m')", $migrations));
        $stmt = $this->pdo->prepare("INSERT INTO migrations (migration) VALUES $str");
        $stmt->execute();
    }
    public function log($message) {
        echo "[".date('Y-m-d H:i:s') . "] - " . $message . PHP_EOL;
    }
}
 ?>
