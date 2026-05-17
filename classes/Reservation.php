<?php

class Reservation {
    private int    $id;
    private int    $user_id;
    private int    $materiel_id;
    private string $date_debut;
    private string $date_fin;
    private string $statut;
    private string $created_at;
    private string $user_nom = '';
    private string $materiel_nom = '';
    private float  $prix_jour = 0.0;

    public function __construct(
        int    $id           = 0,
        int    $user_id      = 0,
        int    $materiel_id  = 0,
        string $date_debut   = '',
        string $date_fin     = '',
        string $statut       = 'en attente',
        string $created_at   = '',
        string $user_nom     = '',
        string $materiel_nom = '',
        float  $prix_jour    = 0.0
    ) {
        if (!isset($this->id))           $this->id           = $id;
        if (!isset($this->user_id))      $this->user_id      = $user_id;
        if (!isset($this->materiel_id))  $this->materiel_id  = $materiel_id;
        if (!isset($this->date_debut))   $this->date_debut   = $date_debut;
        if (!isset($this->date_fin))     $this->date_fin     = $date_fin;
        if (!isset($this->statut))       $this->statut       = $statut;
        if (!isset($this->created_at))   $this->created_at   = $created_at;
        if (empty($this->user_nom))      $this->user_nom     = $user_nom;
        if (empty($this->materiel_nom))  $this->materiel_nom = $materiel_nom;
        if ($this->prix_jour == 0.0)     $this->prix_jour    = $prix_jour;
    }

    public function getId(): int            { return $this->id; }
    public function getUserId(): int        { return $this->user_id; }
    public function getMaterielId(): int    { return $this->materiel_id; }
    public function getDateDebut(): string  { return $this->date_debut; }
    public function getDateFin(): string    { return $this->date_fin; }
    public function getStatut(): string     { return $this->statut; }
    public function getCreatedAt(): string  { return $this->created_at; }
    public function getUserNom(): string    { return $this->user_nom; }
    public function getMaterielNom(): string{ return $this->materiel_nom; }
    public function getPrixJour(): float    { return $this->prix_jour; }

    public function setStatut(string $statut): void { $this->statut = $statut; }

    public function getNbJours(): int {
        $debut = new DateTime($this->date_debut);
        $fin   = new DateTime($this->date_fin);
        return (int)$debut->diff($fin)->days;
    }

    public function getPrixTotal(): float {
        return $this->getNbJours() * $this->prix_jour;
    }
}
