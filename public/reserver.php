<?php
session_start();
require_once '../config.php';
require_once '../classes/Database.php';
require_once '../classes/Materiel.php';
require_once '../classes/MaterielDAO.php';
require_once '../classes/Reservation.php';
require_once '../classes/ReservationDAO.php';

if (!isset($_SESSION['user_id'])) {
    header('Location: connexion.php');
    exit;
}

$materielDAO    = new MaterielDAO();
$reservationDAO = new ReservationDAO();
$erreur         = '';
$succes         = '';

$materiel_id = (int)($_GET['id'] ?? 0);
$materiel    = $materielDAO->getById($materiel_id);

if (!$materiel) {
    header('Location: index.php');
    exit;
}

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $date_debut = $_POST['date_debut'] ?? '';
    $date_fin   = $_POST['date_fin']   ?? '';

    if (empty($date_debut) || empty($date_fin)) {
        $erreur = 'Veuillez choisir les deux dates.';
    } elseif ($date_debut >= $date_fin) {
        $erreur = 'La date de fin doit être après la date de début.';
    } elseif ($date_debut < date('Y-m-d')) {
        $erreur = 'La date de début ne peut pas être dans le passé.';
    } else {
        $reservation = new Reservation(
            id:          0,
            user_id:     $_SESSION['user_id'],
            materiel_id: $materiel->getId(),
            date_debut:  $date_debut,
            date_fin:    $date_fin
        );

        if ($reservationDAO->add($reservation)) {
            $succes = 'Réservation envoyée ! Elle sera confirmée bientôt.';
        } else {
            $erreur = 'Erreur lors de la réservation. Réessayez.';
        }
    }
}

$nb_jours   = 0;
$prix_total = 0;
if (!empty($_POST['date_debut']) && !empty($_POST['date_fin'])) {
    $d1 = new DateTime($_POST['date_debut']);
    $d2 = new DateTime($_POST['date_fin']);
    $nb_jours   = max(0, (int)$d1->diff($d2)->days);
    $prix_total = $nb_jours * $materiel->getPrixJour();
}
?>
<!DOCTYPE html>
<html lang="fr" class="dark">
<head>
    <title>Réserver — <?= htmlspecialchars($materiel->getNom()) ?></title>
    <?php include '../templates/head.php'; ?>
</head>
<body class="bg-navy text-slate-200 font-body min-h-screen flex flex-col">

<?php include '../templates/header.php'; ?>

<main class="flex-grow max-w-7xl mx-auto px-6 py-12 w-full flex items-center justify-center">
    <div class="w-full grid grid-cols-1 lg:grid-cols-2 glass rounded-[40px] overflow-hidden shadow-2xl">
        <!-- Left Side: Product Info -->
        <div class="relative min-h-[400px] lg:min-h-full">
            <img src="uploads/<?= htmlspecialchars($materiel->getPhoto()) ?>" 
                 alt="<?= htmlspecialchars($materiel->getNom()) ?>"
                 onerror="this.src='https://images.unsplash.com/photo-1551632432-c735e829929d?auto=format&fit=crop&q=80&w=800'"
                 class="absolute inset-0 w-full h-full object-cover">
            <div class="absolute inset-0 bg-gradient-to-t from-navy via-navy/40 to-transparent"></div>
            
            <div class="absolute bottom-12 left-12 right-12">
                <div class="inline-block px-4 py-1.5 rounded-full bg-sport-blue-light/20 backdrop-blur-md border border-sport-blue-light/30 text-[10px] font-bold text-white tracking-[0.2em] uppercase mb-4">
                    Équipement Premium
                </div>
                <h2 class="text-5xl text-white mb-4 leading-tight uppercase font-heading tracking-wider"><?= htmlspecialchars($materiel->getNom()) ?></h2>
                <p class="text-slate-300 text-sm leading-relaxed mb-8 max-w-md">
                    <?= htmlspecialchars($materiel->getDescription()) ?>
                </p>
                <div class="flex items-center gap-8 border-t border-white/10 pt-8">
                    <div>
                        <div class="text-[10px] text-slate-500 uppercase tracking-widest mb-1 font-bold">Prix / Jour</div>
                        <div class="text-3xl font-heading text-white"><?= number_format($materiel->getPrixJour(), 2) ?> DT</div>
                    </div>
                    <div>
                        <div class="text-[10px] text-slate-500 uppercase tracking-widest mb-1 font-bold">Catégorie</div>
                        <div class="text-xl text-slate-300"><?= htmlspecialchars($materiel->getCategorieNom()) ?></div>
                    </div>
                </div>
            </div>
        </div>

        <!-- Right Side: Reservation Form -->
        <div class="p-12 lg:p-20 flex flex-col justify-center">
            <div class="mb-10">
                <h3 class="text-3xl text-white mb-2 uppercase font-heading tracking-widest">Planifiez votre aventure</h3>
                <p class="text-slate-500">Sélectionnez vos dates pour vérifier la disponibilité.</p>
            </div>

            <?php if ($erreur): ?>
                <div class="mb-8 p-4 bg-red-500/10 border border-red-500/20 rounded-xl flex items-center gap-3 text-red-400 text-sm">
                    <i class="fa-solid fa-circle-exclamation"></i>
                    <?= $erreur ?>
                </div>
            <?php endif; ?>

            <?php if ($succes): ?>
                <div class="mb-8 p-4 bg-nature-green/10 border border-nature-green/20 rounded-xl flex items-center gap-3 text-nature-green text-sm">
                    <i class="fa-solid fa-circle-check"></i>
                    <?= $succes ?>
                </div>
                <div class="text-center">
                    <a href="mon_compte.php" class="px-8 py-4 bg-white text-navy font-bold rounded-xl btn-premium inline-block">VOIR MES RÉSERVATIONS</a>
                </div>
            <?php else: ?>
                <form method="POST" action="reserver.php?id=<?= $materiel->getId() ?>" class="space-y-8">
                    <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                        <div class="space-y-2">
                            <label for="date_debut" class="text-xs font-bold tracking-widest text-slate-400 uppercase">Date de début</label>
                            <div class="relative">
                                <i class="fa-solid fa-calendar-day absolute left-4 top-1/2 -translate-y-1/2 text-slate-500 text-sm"></i>
                                <input type="date" id="date_debut" name="date_debut" required
                                       min="<?= date('Y-m-d') ?>"
                                       value="<?= htmlspecialchars($_POST['date_debut'] ?? '') ?>"
                                       class="w-full bg-white/5 border border-white/10 rounded-2xl pl-12 pr-6 py-4 text-white focus:outline-none focus:border-sport-blue-light transition-colors [color-scheme:dark]">
                            </div>
                        </div>

                        <div class="space-y-2">
                            <label for="date_fin" class="text-xs font-bold tracking-widest text-slate-400 uppercase">Date de fin</label>
                            <div class="relative">
                                <i class="fa-solid fa-calendar-check absolute left-4 top-1/2 -translate-y-1/2 text-slate-500 text-sm"></i>
                                <input type="date" id="date_fin" name="date_fin" required
                                       min="<?= date('Y-m-d') ?>"
                                       value="<?= htmlspecialchars($_POST['date_fin'] ?? '') ?>"
                                       class="w-full bg-white/5 border border-white/10 rounded-2xl pl-12 pr-6 py-4 text-white focus:outline-none focus:border-sport-blue-light transition-colors [color-scheme:dark]">
                            </div>
                        </div>
                    </div>

                    <!-- Immersive Price Summary -->
                    <div id="price-summary" class="hidden animate-[fadeIn_0.5s_ease-out]">
                        <div class="p-8 rounded-[32px] bg-white/[0.02] border border-white/5 space-y-4">
                            <div class="flex justify-between text-sm">
                                <span class="text-slate-500 uppercase tracking-widest font-bold text-[10px]">Durée du séjour</span>
                                <span class="text-white"><span id="nb-jours">0</span> Jours</span>
                            </div>
                            <div class="flex justify-between text-sm">
                                <span class="text-slate-500 uppercase tracking-widest font-bold text-[10px]">Tarif journalier</span>
                                <span class="text-white"><?= number_format($materiel->getPrixJour(), 2) ?> DT</span>
                            </div>
                            <div class="pt-4 border-t border-white/5 flex justify-between items-end">
                                <span class="text-slate-500 uppercase tracking-widest font-bold text-[10px]">Total Estimé</span>
                                <span class="text-3xl font-heading text-sport-blue-light"><span id="prix-total">0.00</span> DT</span>
                            </div>
                        </div>
                    </div>

                    <button type="submit" class="w-full bg-sport-blue-light hover:bg-blue-400 text-white font-bold py-5 rounded-2xl transition-all btn-premium shadow-lg shadow-sport-blue-light/20 flex items-center justify-center gap-3">
                        CONFIRMER LA RÉSERVATION <i class="fa-solid fa-chevron-right text-xs"></i>
                    </button>
                </form>
            <?php endif; ?>

            <div class="mt-12 pt-8 border-t border-white/5 text-center">
                <a href="index.php" class="inline-flex items-center gap-2 text-sm font-bold text-slate-500 hover:text-white transition-colors group">
                    <i class="fa-solid fa-arrow-left group-hover:-translate-x-1 transition-transform"></i> RETOUR AU CATALOGUE
                </a>
            </div>
        </div>
    </div>
</main>

<?php include '../templates/footer.php'; ?>

<script>
const prixJour = <?= $materiel->getPrixJour() ?>;

function calculerPrix() {
    const debut = document.getElementById('date_debut').value;
    const fin   = document.getElementById('date_fin').value;
    const summary = document.getElementById('price-summary');
    
    if (debut && fin) {
        if (new Date(fin) > new Date(debut)) {
            const d1 = new Date(debut);
            const d2 = new Date(fin);
            const diff = Math.round((d2 - d1) / (1000 * 60 * 60 * 24));
            document.getElementById('nb-jours').textContent  = diff;
            document.getElementById('prix-total').textContent = (diff * prixJour).toFixed(2);
            summary.classList.remove('hidden');
        } else {
            summary.classList.add('hidden');
        }
    }
}

if (document.getElementById('date_debut')) {
    document.getElementById('date_debut').addEventListener('change', calculerPrix);
    document.getElementById('date_fin').addEventListener('change', calculerPrix);
    // Initial check if values exist (e.g. after post error)
    calculerPrix();
}
</script>

</body>
</html>
