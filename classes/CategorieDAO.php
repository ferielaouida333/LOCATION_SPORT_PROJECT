<?php


class CategorieDAO {

    private PDO $pdo;

    public function __construct() {
        // On récupère la connexion PDO via le Singleton
        $this->pdo = Database::getInstance()->getPdo();
    }

  
    public function getAll(): array {
        // query() = pas de variable dans la requête → pas besoin de prepare()
        $stmt = $this->pdo->query("SELECT * FROM categorie ORDER BY nom ASC");

        return $stmt->fetchAll(PDO::FETCH_CLASS, 'Categorie');
    }

   
    public function getById(int $id): ?Categorie {
        $stmt = $this->pdo->prepare("SELECT * FROM categorie WHERE id = ?");
        $stmt->execute([$id]);

        $result = $stmt->fetchObject('Categorie');
        return $result ?: null; // si false → retourne null
    }

    public function add(Categorie $c): bool {
        $stmt = $this->pdo->prepare(
            "INSERT INTO categorie (nom, description) VALUES (?, ?)"
        );
        try {
            return $stmt->execute([$c->getNom(), $c->getDescription()]);
        } catch (PDOException $e) {
            // Si le nom est en double (UNIQUE), ça lance une exception
            return false;
        }
    }

   
    public function update(Categorie $c): bool {
        $stmt = $this->pdo->prepare(
            "UPDATE categorie SET nom = ?, description = ? WHERE id = ?"
        );
        try {
            return $stmt->execute([
                $c->getNom(),
                $c->getDescription(),
                $c->getId()   // le WHERE — identifie quelle ligne modifier
            ]);
        } catch (PDOException $e) {
            return false;
        }
    }

   -
    public function delete(int $id): bool {
        $stmt = $this->pdo->prepare("DELETE FROM categorie WHERE id = ?");
        return $stmt->execute([$id]);
    }
}

