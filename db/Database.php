<?php


namespace parthkapatel\phpmvc\db;


use parthkapatel\phpmvc\Application;

class Database
{
    public \PDO $pdo;
    public function __construct(array $config)
    {
        $dsn = $config["dsn"] ?? '';
        $user = $config["user"] ?? '';
        $password = $config["password"] ?? '';
        $this->pdo = new \PDO($dsn,$user,$password);
        $this->pdo->setAttribute(\PDO::ATTR_ERRMODE,\PDO::ERRMODE_EXCEPTION);
    }

    public function applyMigrations()
    {
        $this->createMigrationTable();
        $appliedMigrations = $this->getAppliedMigrations();

        $newMigrations = [];
        $files = scandir(Application::$ROOT_DIR.'/migrations');
        $toApplyMigrations = array_diff($files,$appliedMigrations);
        foreach ($toApplyMigrations as $migration) {
            if($migration === '.' || $migration === '..'){
                continue;
            }

            require_once Application::$ROOT_DIR.'/migrations/'.$migration;
            $className = pathinfo($migration,PATHINFO_FILENAME);
            $instance = new $className();
            $this->log("Applying migration $migration".PHP_EOL);
            $instance->up();
            $this->log("Applied migration $migration".PHP_EOL);
            $newMigrations[] = $migration;
        }

        if(!empty($newMigrations )){
            $this->saveMIgrations($newMigrations);
        }else{
            $this->log("All migrations are applied");
        }

    }

    public function createMigrationTable()
    {
        $this->pdo->exec("
                    create table if not exists migrations(
                        id int auto_increment primary key,
                        migration varchar(255),
                        created_at timestamp default current_timestamp
                    ) engine=innodb;");
    }

    public function getAppliedMigrations(): array
    {
        $statement = $this->pdo->prepare("select migration from migrations");
        $statement->execute();

        return $statement->fetchAll(\PDO::FETCH_COLUMN);
    }
    public function saveMigrations(array $migrations)
    {

        $str = implode(",",array_map(fn($m) => "('$m')",$migrations));
        $statement = $this->pdo->prepare("insert into migrations (migration) values $str ");
        $statement->execute();
    }

    public function prepare($sql){
        return $this->pdo->prepare($sql);
    }

    protected function log($message){
        echo '['.date('Y-m-d H:i:s').'] - ' . $message . PHP_EOL;
    }
}