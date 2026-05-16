<?php
class MaterielDAO {

    private PDO $pdo;

    public function __construct() {
        $this->pdo = Database::getInstance()->getPdo();
    }

    
    public function getAll(): array {
       
        $stmt = $this->pdo->query("
            SELECT m.*, c.nom AS categorie_nom
            FROM materiel m
            LEFT JOIN categorie c ON m.categorie_id = c.id
            ORDER BY m.nom ASC
        ");
        return $stmt->fetchAll(PDO::FETCH_CLASS, 'Materiel');
    }

   
    public function getDisponibles(): array {
        $stmt = $this->pdo->query("
            SELECT m.*, c.nom AS categorie_nom
            FROM materiel m
            LEFT JOIN categorie c ON m.categorie_id = c.id
            WHERE m.disponible = 1
            ORDER BY m.nom ASC
        ");
        return $stmt->fetchAll(PDO::FETCH_CLASS, 'Materiel');
    }

    
    public function getByCategorie(int $categorie_id): array {
        $stmt = $this->pdo->prepare("
            SELECT m.*, c.nom AS categorie_nom
            FROM materiel m
            LEFT JOIN categorie c ON m.categorie_id = c.id
            WHERE m.categorie_id = ? AND m.disponible = 1
        ");
        $stmt->execute([$categorie_id]);
        return $stmt->fetchAll(PDO::FETCH_CLASS, 'Materiel');
    }

   
    public function getById(int $id): ?Materiel {
        $stmt = $this->pdo->prepare("
            SELECT m.*, c.nom AS categorie_nom
            FROM materiel m
            LEFT JOIN categorie c ON m.categorie_id = c.id
            WHERE m.id = ?
        ");
        $stmt->execute([$id]);
        $result = $stmt->fetchObject('Materiel');
        return $result ?: null;
    }

   
    public function rechercher(string $mot): array {
        $stmt = $this->pdo->prepare("
            SELECT m.*, c.nom AS categorie_nom
            FROM materiel m
            LEFT JOIN categorie c ON m.categorie_id = c.id
            WHERE m.nom LIKE ?
            ORDER BY m.prix_jour ASC
        ");
        $stmt->execute(['%' . $mot . '%']);
        return $stmt->fetchAll(PDO::FETCH_CLASS, 'Materiel');
    }

    
    public function add(Materiel $m): bool {
        $stmt = $this->pdo->prepare("
            INSERT INTO materiel (nom, description, prix_jour, photo, disponible, categorie_id)
            VALUES (?, ?, ?, ?, ?, ?)
        ");
        try {
            return $stmt->execute([
                $m->getNom(),
                $m->getDescription(),
                $m->getPrixJour(),
                $m->getPhoto(),
                $m->isDisponible(),
                $m->getCategorieId()
            ]);
        } catch (PDOException $e) {
            return false;
        }
    }


    public function update(Materiel $m): bool {
        $stmt = $this->pdo->prepare("
            UPDATE materiel
            SET nom=?, description=?, prix_jour=?, photo=?, disponible=?, categorie_id=?
            WHERE id=?
        ");
        try {
            return $stmt->execute([
                $m->getNom(),
                $m->getDescription(),
                $m->getPrixJour(),
                $m->getPhoto(),
                $m->isDisponible(),
                $m->getCategorieId(),
                $m->getId()
            ]);
        } catch (PDOException $e) {
            return false;
        }
    }

    
    public function delete(int $id): bool {
        $stmt = $this->pdo->prepare("DELETE FROM materiel WHERE id = ?");
        return $stmt->execute([$id]);
    }
}


