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
<html lang="fr" class="dark">
<head>
    <title>Catalogue — SportLoc</title>
    <?php include '../templates/head.php'; ?>
</head>
<body class="bg-navy text-slate-200 font-body min-h-screen flex flex-col">


<?php include '../templates/header.php'; ?>

<main class="flex-grow max-w-7xl mx-auto px-6 py-12 w-full">
    <div class="mb-12">
        <h1 class="text-5xl text-white mb-2 uppercase font-heading tracking-widest">Le Catalogue</h1>
        <p class="text-slate-500">Explorez notre sélection d'équipement de sport de haute montagne.</p>
    </div>

    <!-- Search and Filters -->
    <div class="glass p-8 rounded-[40px] border-white/5 mb-12">
        <form method="GET" action="catalogue.php" class="flex flex-col md:flex-row gap-6 mb-8">
            <div class="relative flex-grow">
                <i class="fa-solid fa-magnifying-glass absolute left-6 top-1/2 -translate-y-1/2 text-slate-500"></i>
                <input type="text" name="q" value="<?= $recherche ?>" 
                       placeholder="Rechercher un matériel..." 
                       class="w-full bg-white/5 border border-white/10 rounded-2xl pl-16 pr-6 py-4 text-white focus:outline-none focus:border-sport-blue-light transition-colors">
            </div>
            <button type="submit" class="bg-sport-blue-light hover:bg-blue-400 text-white px-10 py-4 rounded-2xl font-bold transition-all btn-premium shadow-lg shadow-sport-blue-light/20 uppercase tracking-widest text-xs">
                Rechercher
            </button>
            <?php if ($recherche): ?>
                <a href="catalogue.php" class="px-6 py-4 bg-white/5 hover:bg-white/10 border border-white/10 text-white rounded-2xl font-bold transition-all text-center uppercase tracking-widest text-xs flex items-center justify-center">
                    <i class="fa-solid fa-xmark mr-2"></i> Effacer
                </a>
            <?php endif; ?>
        </form>

        <div class="flex flex-wrap gap-3">
            <a href="catalogue.php" 
               class="px-6 py-2 rounded-full text-[10px] font-bold tracking-widest uppercase transition-all <?= $cat_active === 0 && $recherche === '' ? 'bg-white text-navy' : 'bg-white/5 text-slate-400 hover:bg-white/10' ?>">
                TOUT
            </a>
            <?php foreach ($categories as $cat): ?>
                <a href="?categorie=<?= $cat->getId() ?>" 
                   class="px-6 py-2 rounded-full text-[10px] font-bold tracking-widest uppercase transition-all <?= $cat_active === $cat->getId() ? 'bg-sport-blue-light text-white shadow-lg shadow-sport-blue-light/20' : 'bg-white/5 text-slate-400 hover:bg-white/10 hover:text-white' ?>">
                    <?= strtoupper(htmlspecialchars($cat->getNom())) ?>
                </a>
            <?php endforeach; ?>
        </div>
    </div>

    <?php if ($recherche): ?>
        <p class="text-slate-500 mb-8 uppercase tracking-[0.2em] text-[10px] font-bold">
            <span class="text-white"><?= count($materiels) ?></span> résultat(s) pour "<span class="text-sport-blue-light"><?= $recherche ?></span>"
        </p>
    <?php endif; ?>

    <!-- Grid -->
    <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 gap-8">
        <?php if (empty($materiels)): ?>
            <div class="col-span-full text-center py-32 glass rounded-[40px] border-white/5">
                <div class="w-20 h-20 bg-white/5 rounded-full flex items-center justify-center text-4xl text-slate-600 mx-auto mb-6">
                    <i class="fa-solid fa-mountain-slash"></i>
                </div>
                <h3 class="text-2xl text-white mb-2 uppercase font-heading tracking-widest">Aucun résultat</h3>
                <p class="text-slate-500 mb-8">Essayez une autre recherche ou parcourez les catégories.</p>
                <a href="catalogue.php" class="px-8 py-4 bg-white text-navy font-bold rounded-xl btn-premium inline-block">RÉINITIALISER</a>
            </div>
        <?php else: ?>
            <?php foreach ($materiels as $m): ?>
                <div class="group relative bg-white/5 border border-white/10 rounded-3xl overflow-hidden transition-all duration-500 hover:border-white/20 hover:shadow-2xl hover:shadow-black/50 hover:-translate-y-2">
                    <div class="relative h-64 overflow-hidden">
                        <img src="uploads/<?= htmlspecialchars($m->getPhoto()) ?>?v=<?= time() ?>" 
                             alt="<?= htmlspecialchars($m->getNom()) ?>"
                             onerror="this.src='https://images.unsplash.com/photo-1517176118179-65244903d13c?auto=format&fit=crop&q=80&w=800'"
                             class="w-full h-full object-cover transition-transform duration-700 group-hover:scale-110">
                        
                        <div class="absolute top-4 left-4">
                            <span class="px-3 py-1 rounded-full bg-navy/80 backdrop-blur-md text-[10px] font-bold text-white tracking-widest uppercase border border-white/10">
                                <?= htmlspecialchars($m->getCategorieNom()) ?>
                            </span>
                        </div>
                        
                        <div class="absolute inset-0 bg-navy/40 opacity-0 group-hover:opacity-100 transition-opacity duration-300 flex items-center justify-center">
                            <?php if (isset($_SESSION['user_id'])): ?>
                                <a href="reserver.php?id=<?= $m->getId() ?>" class="px-8 py-3 bg-white text-navy font-bold rounded-full transform translate-y-4 group-hover:translate-y-0 transition-all duration-500 text-xs uppercase tracking-widest">
                                    Réserver
                                </a>
                            <?php else: ?>
                                <a href="connexion.php" class="px-8 py-3 bg-white text-navy font-bold rounded-full transform translate-y-4 group-hover:translate-y-0 transition-all duration-500 text-xs uppercase tracking-widest">
                                    S'identifier
                                </a>
                            <?php endif; ?>
                        </div>
                    </div>

                    <div class="p-6">
                        <div class="flex justify-between items-start mb-4">
                            <h3 class="text-xl text-white group-hover:text-sport-blue-light transition-colors font-bold tracking-tight uppercase"><?= htmlspecialchars($m->getNom()) ?></h3>
                            <div class="text-right">
                                <div class="text-xl font-heading text-white"><?= number_format($m->getPrixJour(), 2) ?> DT</div>
                                <div class="text-[10px] text-slate-500 uppercase tracking-widest">/ jour</div>
                            </div>
                        </div>
                        <p class="text-slate-400 text-sm leading-relaxed line-clamp-2 mb-6">
                            <?= htmlspecialchars($m->getDescription()) ?>
                        </p>
                        <div class="pt-4 border-t border-white/5 flex items-center justify-between">
                            <span class="text-[10px] font-bold text-nature-green tracking-[0.2em] uppercase">✓ En Stock</span>
                            <div class="flex gap-2">
                                <div class="w-6 h-6 rounded bg-white/5 flex items-center justify-center text-slate-500 text-[10px]"><i class="fa-solid fa-shield-halved"></i></div>
                                <div class="w-6 h-6 rounded bg-white/5 flex items-center justify-center text-slate-500 text-[10px]"><i class="fa-solid fa-hand-holding-heart"></i></div>
                            </div>
                        </div>
                    </div>
                </div>
            <?php endforeach; ?>
        <?php endif; ?>
    </div>
</main>

<?php include '../templates/footer.php'; ?>

</body>
</html>

