<?php
class Database {
    const DATABASE_HOST = 'localhost';
    const DATABASE_NAME = 'project';
    const DATABASE_USERNAME = 'root';
    const DATABASE_PASSWORD = '';
    private $connection = null;
    
    public function __construct()
    {
        $dsn = sprintf('mysql:dbname=%s;host=%s', static::DATABASE_NAME, static::DATABASE_HOST);
        try {
            $this->connection = new PDO($dsn, static::DATABASE_USERNAME, static::DATABASE_PASSWORD);
            $this->connection->exec("set names utf8");
        } catch (PDOException $e) {
            echo 'Connection failed: '.$e->getMessage();
        }
    }
    
    /**
     * Execute any SQL query
     *
     * @param   string  SQL query
     * @return  int     number of affected rows
     */
    public function prepare($sql)
    {
        return $this->connection->prepare($sql);
    }
}

?>