<?php


class Categorie {


    public function __construct(
        private int    $id          = 0,   // 0 = pas encore en BDD
        private string $nom         = '',
        private string $description = ''
    ) {}
   

    public function getId(): int {
        return $this->id;
    }

    public function getNom(): string {
        return $this->nom;
    }

    public function getDescription(): string {
        return $this->description;
    }

   

    public function setNom(string $nom): void {
        $this->nom = $nom;
    }

    public function setDescription(string $desc): void {
        $this->description = $desc;
    }
}

