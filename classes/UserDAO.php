<?php

class UserDAO {

    private PDO $pdo;

    public function __construct() {
        $this->pdo = Database::getInstance()->getPdo();
    }

    
    public function inscrire(string $nom, string $email, string $mdp): bool {
        
        $hash = password_hash($mdp, PASSWORD_DEFAULT);

        $stmt = $this->pdo->prepare(
            "INSERT INTO users (nom, email, mot_de_passe) VALUES (?, ?, ?)"
        );
        try {
            return $stmt->execute([$nom, $email, $hash]);
        } catch (PDOException $e) {
            // L'email est UNIQUE en BDD → exception si déjà pris
            return false;
        }
    }

    
    public function connecter(string $email, string $mdp): ?User {
        $stmt = $this->pdo->prepare("SELECT * FROM users WHERE email = ?");
        $stmt->execute([$email]);

       
        $data = $stmt->fetch(PDO::FETCH_ASSOC);

        if (!$data) {
            return null; 
        }

        
        if (!password_verify($mdp, $data['mot_de_passe'])) {
            return null; // mot de passe incorrect
        }

       
        return new User(
            $data['id'],
            $data['nom'],
            $data['email'],
            $data['mot_de_passe'],
            $data['role'],
            $data['created_at']
        );
    }

    
    public function getAll(): array {
        $stmt = $this->pdo->query("SELECT * FROM users ORDER BY created_at DESC");
        return $stmt->fetchAll(PDO::FETCH_CLASS, 'User');
    }

   
    public function getById(int $id): ?User {
        $stmt = $this->pdo->prepare("SELECT * FROM users WHERE id = ?");
        $stmt->execute([$id]);
        $result = $stmt->fetchObject('User');
        return $result ?: null;
    }

    
    public function delete(int $id): bool {
        $stmt = $this->pdo->prepare("DELETE FROM users WHERE id = ?");
        return $stmt->execute([$id]);
    }
}
