<?php

session_start();
require_once '../config.php';
require_once '../classes/Database.php';
require_once '../classes/Categorie.php';
require_once '../classes/CategorieDAO.php';
require_once '../classes/Materiel.php';
require_once '../classes/MaterielDAO.php';

$materielDAO  = new MaterielDAO();
$categorieDAO = new CategorieDAO();
$categories   = $categorieDAO->getAll();


$materiels  = [];
$recherche  = '';
$cat_active = 0;

if (!empty($_GET['q'])) {
    $recherche = htmlspecialchars($_GET['q']);
    $materiels = $materielDAO->rechercher($recherche);

} elseif (!empty($_GET['categorie'])) {
    $cat_active = (int)$_GET['categorie'];
    $materiels  = $materielDAO->getByCategorie($cat_active);

} else {
    $materiels = $materielDAO->getDisponibles();
}
?>
<!DOCTYPE html>
<html lang="fr">
<head>
    <meta charset="UTF-8">
    <title>Catalogue — SportLoc</title>
    <link rel="stylesheet" href="../css/style.css">
</head>
<body>

<nav class="navbar">
    <div class="nav-logo">🚲 SportLoc</div>
    <div class="nav-links">
        <a href="index.php">Accueil</a>
        <a href="catalogue.php" class="active">Catalogue</a>
        <?php if (isset($_SESSION['user_id'])): ?>
            <a href="mon_compte.php">Mon compte</a>
            <a href="deconnexion.php" class="btn-outline">Déconnexion</a>
        <?php else: ?>
            <a href="connexion.php">Connexion</a>
            <a href="inscription.php" class="btn-primary">S'inscrire</a>
        <?php endif; ?>
    </div>
</nav>

<div class="container">

    
    <form method="GET" action="catalogue.php" style="margin: 24px 0; display:flex; gap:8px;">
        <input type="text" name="q"
               value="<?= $recherche ?>"
               placeholder="Rechercher un matériel..."
               style="flex:1; padding:10px 14px; border:1px solid #ddd;
                      border-radius:8px; font-size:14px;">
        <button type="submit" class="btn-primary" style="padding:10px 20px;">
            Rechercher
        </button>
        <?php if ($recherche): ?>
            <a href="catalogue.php" class="btn-outline" style="padding:10px 16px;">
                Effacer
            </a>
        <?php endif; ?>
    </form>

    
    <div class="cat-pills">
        <a href="catalogue.php"
           class="pill <?= $cat_active === 0 && $recherche === '' ? 'active' : '' ?>">
            Tout
        </a>
        <?php foreach ($categories as $cat): ?>
            <a href="?categorie=<?= $cat->getId() ?>"
               class="pill <?= $cat_active === $cat->getId() ? 'active' : '' ?>">
                <?= htmlspecialchars($cat->getNom()) ?>
OAOAOA            </a>
OAOAOAOAOAOA        <?php endforeach; ?>
    </div>

    
    <?php if ($recherche): ?>
OAOAOA        <p style="color:#777; font-size:14px; margin-bottom:12px;">
            <?= count($materiels) ?> résultat(s) pour
            "<strong><?= $recherche ?></strong>"
        </p>
    <?php endif; ?>

    
    <h2 class="section-title"><?= count($materiels) ?> matériel(s) disponible(s)</h2>

    <?php if (empty($materiels)): ?>
        <div class="empty-state">
            <p>Aucun matériel trouvé.</p>
            <a href="catalogue.php" class="btn-primary">Voir tout le catalogue</a>
        </div>
    <?php else: ?>
        <div class="grid">
            <?php foreach ($materiels as $m): ?>
                <div class="card">
                    <div class="card-img">
                        <img src="../uploads/<?= htmlspecialchars($m->getPhoto()) ?>"
                             alt="<?= htmlspecialchars($m->getNom()) ?>"
                             onerror="this.src='../uploads/default.jpg'">
                    </div>
                    <div class="card-body">
                        <h3 class="card-title"><?= htmlspecialchars($m->getNom()) ?></h3>
                        <p class="card-desc"><?= htmlspecialchars($m->getDescription()) ?></p>
                        <div class="card-price">
                            <?= number_format($m->getPrixJour(), 2) ?>€
                            <span>/ jour</span>
                        </div>
                        <span class="badge-available">✓ Disponible</span>
                        <?php if (isset($_SESSION['user_id'])): ?>
                            <a href="reserver.php?id=<?= $m->getId() ?>"
                               class="btn-card">Réserver</a>
                        <?php else: ?>
                            <a href="connexion.php" class="btn-card">
                                Connectez-vous pour réserver
                            </a>
                        <?php endif; ?>
                    </div>
                </div>
            <?php endforeach; ?>
        </div>
    <?php endif; ?>

</div>

<footer class="footer">
    <p>© 2025 SportLoc — FERIEL · HOUSSEM · ZEINEB</p>
</footer>

</body>
</html>
