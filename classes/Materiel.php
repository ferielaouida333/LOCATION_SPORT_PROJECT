<?php

class Materiel {
    private int    $id;
    private string $nom;
    private string $description;
    private float  $prix_jour;
    private string $photo;
    private int    $disponible;
    private int    $categorie_id;
    private string $categorie_nom = '';

    public function __construct(
        int    $id           = 0,
        string $nom          = '',
        string $description  = '',
        float  $prix_jour    = 0.0,
        string $photo        = 'default.jpg',
        int    $disponible   = 1,
        int    $categorie_id = 0,
        string $categorie_nom = ''
    ) {
        // Only set if not already set by PDO
        if (!isset($this->id))           $this->id           = $id;
        if (!isset($this->nom))          $this->nom          = $nom;
        if (!isset($this->description))  $this->description  = $description;
        if (!isset($this->prix_jour))    $this->prix_jour    = $prix_jour;
        if (!isset($this->photo))        $this->photo        = $photo;
        if (!isset($this->disponible))   $this->disponible   = $disponible;
        if (!isset($this->categorie_id)) $this->categorie_id = $categorie_id;
        if (empty($this->categorie_nom)) $this->categorie_nom = $categorie_nom;
    }

    public function getId(): int          { return $this->id; }
    public function getNom(): string      { return $this->nom; }
    public function getDescription(): string { return $this->description; }
    public function getPrixJour(): float  { return $this->prix_jour; }
    public function getPhoto(): string    { return $this->photo; }
    public function isDisponible(): int   { return $this->disponible; }
    public function getCategorieId(): int { return $this->categorie_id; }
    public function getCategorieNom(): string { return $this->categorie_nom; }
}
