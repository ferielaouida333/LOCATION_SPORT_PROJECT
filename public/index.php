<?php
session_start();

require_once '../config.php';
require_once '../classes/Database.php';
require_once '../classes/Categorie.php';
require_once '../classes/CategorieDAO.php';
require_once '../classes/Materiel.php';
require_once '../classes/MaterielDAO.php';

$categorieDAO = new CategorieDAO();
$materielDAO  = new MaterielDAO();

$categories = $categorieDAO->getAll();

$materiels = [];
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
    <title>SportLoc — Premium Outdoor Equipment</title>
    <?php include '../templates/head.php'; ?>
</head>
<body class="bg-navy text-slate-200 font-body overflow-x-hidden">
<!-- PREMIUM VERSION LOADED -->

<?php include '../templates/header.php'; ?>

<!-- Hero Section -->
<section class="relative min-h-[80vh] flex items-center justify-center overflow-hidden pt-20">
    <!-- Background Image with Overlay -->
    <div class="absolute inset-0 z-0">
        <img src="https://images.unsplash.com/photo-1551632432-c735e829929d?auto=format&fit=crop&q=80&w=2070" 
             class="w-full h-full object-cover scale-105 animate-[pulse_10s_infinite]" alt="Mountain background">
        <div class="absolute inset-0 bg-gradient-to-b from-navy/60 via-navy/40 to-navy"></div>
    </div>

    <div class="relative z-10 max-w-7xl mx-auto px-6 text-center">
        <div class="inline-flex items-center gap-2 px-4 py-2 rounded-full bg-white/10 backdrop-blur-md border border-white/20 mb-8 animate-bounce">
            <span class="w-2 h-2 rounded-full bg-nature-green animate-ping"></span>
            <span class="text-[10px] font-bold tracking-[0.2em] uppercase">Saison Hiver 2026 Ouverte</span>
        </div>
        
        <h1 class="text-6xl md:text-8xl font-heading text-white mb-6 tracking-tighter leading-none">
            DOMINEZ LES <span class="text-transparent bg-clip-text bg-gradient-to-r from-sport-blue-light to-emerald-400">SOMMETS</span>
        </h1>
        
        <p class="max-w-2xl mx-auto text-lg md:text-xl text-slate-300 mb-12 font-light leading-relaxed">
            Équipement technique de classe mondiale pour vos expéditions les plus audacieuses. 
            Louez, explorez, et repoussez vos limites.
        </p>

        <!-- Search Bar -->
        <form method="GET" action="index.php" class="relative max-w-2xl mx-auto group">
            <div class="absolute inset-0 bg-sport-blue-light/20 blur-2xl group-focus-within:bg-sport-blue-light/40 transition-all duration-500 rounded-2xl"></div>
            <div class="relative flex items-center bg-white/10 backdrop-blur-xl border border-white/20 p-2 rounded-2xl">
                <i class="fa-solid fa-magnifying-glass ml-6 text-slate-400"></i>
                <input type="text" name="q" value="<?= $recherche ?>" 
                       placeholder="Quel sommet allez-vous conquérir ?" 
                       class="w-full bg-transparent border-none px-6 py-4 text-white focus:ring-0 placeholder:text-slate-500 font-medium">
                <button type="submit" class="bg-sport-blue-light hover:bg-blue-400 text-white px-8 py-4 rounded-xl font-bold transition-all btn-premium shadow-lg shadow-sport-blue-light/20">
                    RECHERCHER
                </button>
            </div>
        </form>
    </div>

    <!-- Floating Badge -->
    <div class="absolute bottom-10 right-10 hidden lg:block animate-float">
        <div class="glass p-6 rounded-2xl flex items-center gap-4">
            <div class="w-12 h-12 bg-flame-orange rounded-full flex items-center justify-center text-white text-xl">
                <i class="fa-solid fa-award"></i>
            </div>
            <div>
                <div class="text-white font-bold text-sm">Qualité Premium</div>
                <div class="text-slate-400 text-xs">Entretien pro après chaque location</div>
            </div>
        </div>
    </div>
</section>

<!-- Categories Ticker -->
<div class="py-12 border-y border-white/5 bg-white/[0.02]">
    <div class="max-w-7xl mx-auto px-6">
        <div class="flex flex-wrap justify-center gap-4">
            <a href="index.php" class="px-8 py-3 rounded-full text-xs font-bold tracking-widest transition-all <?= $cat_active === 0 && $recherche === '' ? 'bg-white text-navy' : 'bg-white/5 text-slate-400 hover:bg-white/10' ?>">
                TOUT EXPLORER
            </a>
            <?php foreach ($categories as $cat): ?>
                <a href="?categorie=<?= $cat->getId() ?>" 
                   class="px-8 py-3 rounded-full text-xs font-bold tracking-widest transition-all <?= $cat_active === $cat->getId() ? 'bg-sport-blue-light text-white shadow-lg shadow-sport-blue-light/20' : 'bg-white/5 text-slate-400 hover:bg-white/10 hover:text-white' ?>">
                    <?= strtoupper(htmlspecialchars($cat->getNom())) ?>
                </a>
            <?php endforeach; ?>
        </div>
    </div>
</div>

<div class="max-w-7xl mx-auto px-6 py-20">
    <?php if ($recherche): ?>
        <div class="mb-12 flex items-center justify-between">
            <h2 class="text-3xl text-white">RÉSULTATS POUR "<span class="text-sport-blue-light"><?= $recherche ?></span>"</h2>
            <a href="index.php" class="text-sm text-slate-500 hover:text-white transition-colors flex items-center gap-2">
                <i class="fa-solid fa-xmark"></i> EFFACER LA RECHERCHE
            </a>
        </div>
    <?php else: ?>
        <div class="flex items-end justify-between mb-12">
            <div>
                <h2 class="text-4xl text-white mb-2">NOTRE SÉLECTION</h2>
                <p class="text-slate-500"><?= count($materiels) ?> équipements disponibles pour votre prochaine aventure.</p>
            </div>
            <div class="flex gap-2">
                <button class="w-10 h-10 rounded-full border border-white/10 flex items-center justify-center text-slate-400 hover:bg-white/5 transition-all"><i class="fa-solid fa-chevron-left"></i></button>
                <button class="w-10 h-10 rounded-full border border-white/10 flex items-center justify-center text-slate-400 hover:bg-white/5 transition-all"><i class="fa-solid fa-chevron-right"></i></button>
            </div>
        </div>
    <?php endif; ?>

    <?php if (empty($materiels)): ?>
        <div class="text-center py-32 glass rounded-3xl">
            <div class="w-20 h-20 bg-white/5 rounded-full flex items-center justify-center text-4xl text-slate-600 mx-auto mb-6">
                <i class="fa-solid fa-mountain-slash"></i>
            </div>
            <h3 class="text-2xl text-white mb-2">AUCUN MATÉRIEL TROUVÉ</h3>
            <p class="text-slate-500 mb-8">Essayez une autre recherche ou parcourez nos catégories.</p>
            <a href="index.php" class="px-8 py-4 bg-white text-navy font-bold rounded-xl btn-premium">VOIR TOUT LE CATALOGUE</a>
        </div>
    <?php else: ?>
        <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 gap-8">
            <?php foreach ($materiels as $m): ?>
                <div class="group relative bg-white/5 border border-white/10 rounded-3xl overflow-hidden transition-all duration-500 hover:border-white/20 hover:shadow-2xl hover:shadow-black/50 hover:-translate-y-2">
                    <!-- Image Container -->
                    <div class="relative h-72 overflow-hidden">
                        <img src="uploads/<?= htmlspecialchars($m->getPhoto()) ?>?v=<?= time() ?>" 
                             alt="<?= htmlspecialchars($m->getNom()) ?>"
                             onerror="this.src='https://images.unsplash.com/photo-1517176118179-65244903d13c?auto=format&fit=crop&q=80&w=800'"
                             class="w-full h-full object-cover transition-transform duration-700 group-hover:scale-110">
                        
                        <!-- Badges -->
                        <div class="absolute top-4 left-4 flex gap-2">
                            <span class="px-3 py-1 rounded-full bg-navy/80 backdrop-blur-md text-[10px] font-bold text-white tracking-widest uppercase border border-white/10">
                                <?= htmlspecialchars($m->getCategorieNom()) ?>
                            </span>
                        </div>
                        
                        <div class="absolute top-4 right-4">
                            <div class="w-10 h-10 rounded-full bg-nature-green/90 backdrop-blur-md flex items-center justify-center text-white shadow-lg">
                                <i class="fa-solid fa-check text-xs"></i>
                            </div>
                        </div>

                        <!-- Hover Overlay with Button -->
                        <div class="absolute inset-0 bg-navy/40 opacity-0 group-hover:opacity-100 transition-opacity duration-300 flex items-center justify-center">
                            <?php if (isset($_SESSION['user_id'])): ?>
                                <a href="reserver.php?id=<?= $m->getId() ?>" class="px-8 py-3 bg-white text-navy font-bold rounded-full transform translate-y-4 group-hover:translate-y-0 transition-all duration-500">
                                    RÉSERVER MAINTENANT
                                </a>
                            <?php else: ?>
                                <a href="connexion.php" class="px-8 py-3 bg-white text-navy font-bold rounded-full transform translate-y-4 group-hover:translate-y-0 transition-all duration-500">
                                    S'IDENTIFIER
                                </a>
                            <?php endif; ?>
                        </div>
                    </div>

                    <!-- Content -->
                    <div class="p-8">
                        <div class="flex justify-between items-start mb-4">
                            <h3 class="text-2xl text-white group-hover:text-sport-blue-light transition-colors"><?= htmlspecialchars($m->getNom()) ?></h3>
                            <div class="text-right">
                                <div class="text-2xl font-heading text-white"><?= number_format($m->getPrixJour(), 2) ?> DT</div>
                                <div class="text-[10px] text-slate-500 uppercase tracking-widest">Par jour</div>
                            </div>
                        </div>
                        <p class="text-slate-400 text-sm leading-relaxed line-clamp-2 mb-6">
                            <?= htmlspecialchars($m->getDescription()) ?>
                        </p>
                        
                        <div class="pt-6 border-t border-white/5 flex items-center justify-between">
                            <div class="flex gap-3">
                                <div class="w-8 h-8 rounded-lg bg-white/5 flex items-center justify-center text-slate-400" title="Équipement Pro">
                                    <i class="fa-solid fa-shield-halved text-xs"></i>
                                </div>
                                <div class="w-8 h-8 rounded-lg bg-white/5 flex items-center justify-center text-slate-400" title="Assurance Incluse">
                                    <i class="fa-solid fa-hand-holding-heart text-xs"></i>
                                </div>
                            </div>
                            <span class="text-[10px] font-bold text-nature-green tracking-[0.2em] uppercase">Disponible</span>
                        </div>
                    </div>
                </div>
            <?php endforeach; ?>
        </div>
    <?php endif; ?>
</div>

<!-- Newsletter / CTA -->
<section class="max-w-7xl mx-auto px-6 mb-20">
    <div class="relative rounded-[40px] overflow-hidden bg-sport-blue p-12 md:p-20">
        <div class="absolute inset-0">
            <img src="https://images.unsplash.com/photo-1464822759023-fed622ff2c3b?auto=format&fit=crop&q=80&w=2070" 
                 class="w-full h-full object-cover opacity-30 mix-blend-overlay" alt="Mountain range">
            <div class="absolute inset-0 bg-gradient-to-r from-sport-blue to-transparent"></div>
        </div>
        
        <div class="relative z-10 max-w-2xl">
            <h2 class="text-5xl text-white mb-6">PRÊT POUR VOTRE PROCHAINE <span class="text-emerald-300">AVENTURE ?</span></h2>
            <p class="text-blue-100 text-lg mb-10 leading-relaxed">
                Rejoignez la communauté SportLoc et profitez de -15% sur votre première location d'équipement premium.
            </p>
            <div class="flex flex-col sm:flex-row gap-4">
                <a href="inscription.php" class="px-10 py-5 bg-white text-sport-blue font-bold rounded-2xl btn-premium text-center">CRÉER UN COMPTE</a>
                <a href="#" class="px-10 py-5 bg-white/10 backdrop-blur-md border border-white/20 text-white font-bold rounded-2xl hover:bg-white/20 transition-all text-center">EN SAVOIR PLUS</a>
            </div>
        </div>
    </div>
</section>

<?php include '../templates/footer.php'; ?>

<style>
    @keyframes float {
        0% { transform: translateY(0px); }
        50% { transform: translateY(-20px); }
        100% { transform: translateY(0px); }
    }
    .animate-float {
        animation: float 6s ease-in-out infinite;
    }
</style>

</body>
</html>

