<?php


class Database {

    
    private static ?Database $instance = null;

    
    private PDO $pdo;

    
    private function __construct() {

        $dsn = "mysql:host=" . DB_HOST
             . ";dbname="    . DB_NAME
             . ";charset="   . DB_CHARSET;

        try {
            $this->pdo = new PDO($dsn, DB_USER, DB_PASS, [
               
                PDO::ATTR_ERRMODE            => PDO::ERRMODE_EXCEPTION,
               
                PDO::ATTR_DEFAULT_FETCH_MODE => PDO::FETCH_ASSOC,
            ]);
        } catch (PDOException $e) {
            // En cas d'erreur de connexion, on arrête tout
            die("Erreur connexion BDD : " . $e->getMessage());
        }
    }

    
    public static function getInstance(): Database {
        if (self::$instance === null) {
            self::$instance = new Database(); 
        }
        return self::$instance; 
    }

   
    public function getPdo(): PDO {
        return $this->pdo;
    }
}

