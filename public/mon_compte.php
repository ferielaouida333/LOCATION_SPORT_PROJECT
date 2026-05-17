<?php
session_start();
require_once '../config.php';
require_once '../classes/Database.php';
require_once '../classes/User.php';
require_once '../classes/UserDAO.php';
require_once '../classes/Reservation.php';
require_once '../classes/ReservationDAO.php';

if (!isset($_SESSION['user_id'])) {
    header('Location: connexion.php');
    exit;
}

$reservationDAO = new ReservationDAO();
$reservations   = $reservationDAO->getByUser($_SESSION['user_id']);
?>
<!DOCTYPE html>
<html lang="fr" class="dark">
<head>
    <title>Mon Compte — SportLoc</title>
    <?php include '../templates/head.php'; ?>
</head>
<body class="bg-navy text-slate-200 font-body min-h-screen flex flex-col">

<?php include '../templates/header.php'; ?>

<main class="flex-grow max-w-7xl mx-auto px-6 py-12 w-full">
    <!-- Profile Header -->
    <div class="relative rounded-[40px] overflow-hidden bg-slate-900 border border-white/5 p-8 md:p-12 mb-12 shadow-2xl">
        <div class="absolute top-0 right-0 p-12 opacity-5 pointer-events-none">
            <i class="fa-solid fa-user-gear text-[200px] text-white"></i>
        </div>
        
        <div class="relative z-10 flex flex-col md:flex-row items-center gap-8">
            <div class="w-24 h-24 rounded-3xl bg-gradient-to-br from-sport-blue-light to-emerald-500 flex items-center justify-center text-3xl font-heading text-white shadow-xl shadow-sport-blue-light/20 rotate-3 hover:rotate-0 transition-transform duration-500">
                <?= strtoupper(substr($_SESSION['user_nom'], 0, 2)) ?>
            </div>
            <div class="text-center md:text-left">
                <h2 class="text-4xl text-white mb-2"><?= htmlspecialchars($_SESSION['user_nom']) ?></h2>
                <div class="flex flex-wrap justify-center md:justify-start gap-3">
                    <span class="px-4 py-1.5 rounded-full bg-white/5 border border-white/10 text-[10px] font-bold tracking-widest text-slate-400 uppercase">Explorateur Premium</span>
                    <span class="px-4 py-1.5 rounded-full bg-sport-blue-light/10 border border-sport-blue-light/20 text-[10px] font-bold tracking-widest text-sport-blue-light uppercase"><?= count($reservations) ?> Réservations</span>
                </div>
            </div>
            <div class="md:ml-auto flex gap-4">
                <a href="#" class="px-6 py-3 rounded-2xl bg-white/5 hover:bg-white/10 border border-white/10 text-sm font-bold transition-all">ÉDITER LE PROFIL</a>
                <a href="deconnexion.php" class="px-6 py-3 rounded-2xl bg-red-500/10 hover:bg-red-500/20 border border-red-500/20 text-red-400 text-sm font-bold transition-all">DÉCONNEXION</a>
            </div>
        </div>
    </div>

    <!-- Dashboard Content -->
    <div class="grid grid-cols-1 lg:grid-cols-3 gap-12">
        <!-- Sidebar/Stats -->
        <div class="lg:col-span-1 space-y-8">
            <div class="glass p-8 rounded-[32px] border-white/5">
                <h3 class="text-xl text-white mb-6 font-bold tracking-wider uppercase text-xs">Aperçu de l'activité</h3>
                <div class="space-y-6">
                    <div class="flex items-center justify-between">
                        <div class="flex items-center gap-4">
                            <div class="w-10 h-10 rounded-xl bg-sport-blue-light/10 flex items-center justify-center text-sport-blue-light">
                                <i class="fa-solid fa-calendar-check"></i>
                            </div>
                            <span class="text-slate-400 text-sm">Total réservations</span>
                        </div>
                        <span class="text-white font-bold"><?= count($reservations) ?></span>
                    </div>
                    <div class="flex items-center justify-between">
                        <div class="flex items-center gap-4">
                            <div class="w-10 h-10 rounded-xl bg-emerald-500/10 flex items-center justify-center text-emerald-500">
                                <i class="fa-solid fa-coins"></i>
                            </div>
                            <span class="text-slate-400 text-sm">Points fidélité</span>
                        </div>
                        <span class="text-white font-bold"><?= count($reservations) * 50 ?> pts</span>
                    </div>
                    <div class="flex items-center justify-between">
                        <div class="flex items-center gap-4">
                            <div class="w-10 h-10 rounded-xl bg-flame-orange/10 flex items-center justify-center text-flame-orange">
                                <i class="fa-solid fa-trophy"></i>
                            </div>
                            <span class="text-slate-400 text-sm">Niveau</span>
                        </div>
                        <span class="text-white font-bold">Basecamp</span>
                    </div>
                </div>
            </div>

            <div class="relative rounded-[32px] overflow-hidden p-8 bg-gradient-to-br from-sport-blue-light to-blue-700">
                <div class="relative z-10">
                    <h3 class="text-white font-bold mb-4">BESOIN D'AIDE ?</h3>
                    <p class="text-blue-100 text-sm mb-6 leading-relaxed">Nos guides sont disponibles 24/7 pour vous accompagner dans votre prochaine expédition.</p>
                    <a href="#" class="inline-block px-6 py-3 bg-white text-sport-blue-light rounded-xl text-xs font-bold hover:shadow-xl transition-all">CONTACTER UN EXPERT</a>
                </div>
                <i class="fa-solid fa-headset absolute bottom-[-20px] right-[-20px] text-[120px] text-white/10 rotate-[-15deg]"></i>
            </div>
        </div>

        <!-- Main Content: Reservations -->
        <div class="lg:col-span-2">
            <div class="flex items-center justify-between mb-8">
                <h3 class="text-2xl text-white">HISTORIQUE DES RÉSERVATIONS</h3>
                <div class="flex gap-2">
                    <button class="px-4 py-2 rounded-lg bg-white/5 border border-white/10 text-[10px] font-bold text-slate-400 uppercase tracking-widest hover:text-white transition-all">Filtrer</button>
                    <button class="px-4 py-2 rounded-lg bg-white/5 border border-white/10 text-[10px] font-bold text-slate-400 uppercase tracking-widest hover:text-white transition-all">Exporter</button>
                </div>
            </div>

            <?php if (empty($reservations)): ?>
                <div class="text-center py-20 glass rounded-[32px] border-white/5">
                    <div class="w-20 h-20 bg-white/5 rounded-full flex items-center justify-center text-3xl text-slate-600 mx-auto mb-6">
                        <i class="fa-solid fa-box-open"></i>
                    </div>
                    <h4 class="text-xl text-white mb-2 tracking-widest">AUCUNE RÉSERVATION</h4>
                    <p class="text-slate-500 mb-8">Votre historique est vide. Prêt pour votre première aventure ?</p>
                    <a href="index.php" class="px-8 py-4 bg-sport-blue-light text-white font-bold rounded-xl btn-premium inline-block">PARCOURIR LE CATALOGUE</a>
                </div>
            <?php else: ?>
                <div class="space-y-6">
                    <?php foreach ($reservations as $r): ?>
                        <div class="group glass p-6 md:p-8 rounded-[32px] border-white/5 hover:border-white/10 transition-all duration-300">
                            <div class="flex flex-col md:flex-row md:items-center gap-6">
                                <div class="w-16 h-16 rounded-2xl bg-white/5 flex items-center justify-center text-2xl text-sport-blue-light border border-white/5 group-hover:bg-sport-blue-light group-hover:text-white transition-all">
                                    <i class="fa-solid fa-mountain"></i>
                                </div>
                                <div class="flex-grow">
                                    <div class="flex flex-col md:flex-row md:items-center justify-between mb-2">
                                        <h4 class="text-xl text-white font-bold tracking-tight"><?= htmlspecialchars($r->getMaterielNom()) ?></h4>
                                        <div class="flex items-center gap-4 mt-2 md:mt-0">
                                            <span class="text-2xl font-heading text-white"><?= number_format($r->getPrixTotal(), 2) ?> DT</span>
                                            <span class="px-3 py-1 rounded-full text-[10px] font-bold uppercase tracking-widest
                                                <?= $r->getStatut() === 'confirmée' ? 'bg-nature-green/10 text-nature-green border border-nature-green/20' : 
                                                   ($r->getStatut() === 'en attente' ? 'bg-flame-orange/10 text-flame-orange border border-flame-orange/20' : 'bg-white/5 text-slate-400 border border-white/10') ?>">
                                                <?= htmlspecialchars($r->getStatut()) ?>
                                            </span>
                                        </div>
                                    </div>
                                    <div class="grid grid-cols-2 md:grid-cols-3 gap-4 text-xs">
                                        <div>
                                            <div class="text-slate-500 uppercase tracking-widest mb-1 font-bold text-[10px]">Période</div>
                                            <div class="text-slate-300"><?= date('d M Y', strtotime($r->getDateDebut())) ?> → <?= date('d M Y', strtotime($r->getDateFin())) ?></div>
                                        </div>
                                        <div>
                                            <div class="text-slate-500 uppercase tracking-widest mb-1 font-bold text-[10px]">Durée</div>
                                            <div class="text-slate-300"><?= $r->getNbJours() ?> Jours</div>
                                        </div>
                                        <div class="col-span-2 md:col-span-1">
                                            <div class="text-slate-500 uppercase tracking-widest mb-1 font-bold text-[10px]">ID Réservation</div>
                                            <div class="text-slate-300">#SL-<?= str_pad($r->getId(), 5, '0', STR_PAD_LEFT) ?></div>
                                        </div>
                                    </div>
                                </div>
                                <div class="md:ml-4 flex md:flex-col gap-2">
                                    <button class="flex-1 md:flex-none p-3 rounded-xl bg-white/5 hover:bg-white/10 border border-white/10 text-slate-400 hover:text-white transition-all" title="Détails">
                                        <i class="fa-solid fa-eye"></i>
                                    </button>
                                    <button class="flex-1 md:flex-none p-3 rounded-xl bg-white/5 hover:bg-white/10 border border-white/10 text-slate-400 hover:text-white transition-all" title="Facture">
                                        <i class="fa-solid fa-file-invoice-dollar"></i>
                                    </button>
                                </div>
                            </div>
                        </div>
                    <?php endforeach; ?>
                </div>
            <?php endif; ?>
        </div>
    </div>
</main>

<?php include '../templates/footer.php'; ?>

</body>
</html>
