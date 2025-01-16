<?php

class Database
{
    private $db;
    private $rows;

    public function __construct()
    {
        try {
            $this->db = new PDO(
                'mysql:host=' . $_ENV['DB_HOST'] . ';dbname=' . $_ENV['DB_NAME'] . ';charset=utf8',
                $_ENV['DB_USER'],
                $_ENV['DB_PASSWORD']
            );
            $this->db->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
        } catch (Exception $event) {
            die('Erreur de connexion à la base de données : ' . $event->getMessage());
        }
    }

    private function exec($request, $values = null)
    {
        $req = $this->db->prepare($request);
        $req->execute($values);
        return $req;
    }

    public function setFetchMode($fetchMode)
    {
        $this->db->setAttribute(PDO::ATTR_DEFAULT_FETCH_MODE, $fetchMode);
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
}