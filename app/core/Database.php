<?php
class Database
{
    private static $instance = null;
    private $pdo;

    private function __construct()
    {
        try {
            $this->pdo = new PDO(
                'mysql:host=' . $_ENV['DB_HOST'] . ';dbname=' . $_ENV['DB_NAME'] . ';charset=utf8',
                $_ENV['DB_USER'],
                $_ENV['DB_PASSWORD']
            );
            $this->pdo->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
        } catch (Exception $event) {
            die('Database connection error : ' . $event->getMessage());
        }
    }

    public static function getInstance()
    {
        if (self::$instance === null) {
            self::$instance = new Database();
        }
        return self::$instance;
    }

    private function exec($request, $values = null)
    {
        try {
            $req = $this->pdo->prepare($request);
            $req->execute($values);
            return $req;
        } catch (PDOException $e) {
            error_log("SQL error: " . $e->getMessage());
            throw $e;
        }
    }

    public function setFetchMode($fetchMode)
    {
        $this->pdo->setAttribute(PDO::ATTR_DEFAULT_FETCH_MODE, $fetchMode);
    }

    public function execute($request, $values = array())
    {
        $results = $this->exec($request, $values);
        return ($results) ? true : false;
    }

    public function fetch($request, $values = null, $all = true)
    {
        $results = $this->exec($request, $values);
        return ($all) ? $results->fetchAll() : $results->fetch();
    }

    public function getPdo(): PDO
    {
        return $this->pdo;
    }

    public function closeConnection()
    {
        $this->pdo = null;
    }

    public function beginTransaction()
    {
        return $this->pdo->beginTransaction();
    }

    public function commit()
    {
        return $this->pdo->commit();
    }

    public function rollBack()
    {
        return $this->pdo->rollBack();
    }

    public function checkInactiveConnections()
    {
        $stmt = $this->pdo->query("SHOW PROCESSLIST");
        $processes = $stmt->fetchAll(PDO::FETCH_ASSOC);

        foreach ($processes as $process) {
            if ($process['Command'] === 'Sleep' && $process['Time'] > 60) {
                $this->pdo->exec("KILL " . $process['Id']);
            }
        }
    }
}