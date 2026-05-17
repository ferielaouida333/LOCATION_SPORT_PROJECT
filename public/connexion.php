<?php
session_start();
require_once '../config.php';
require_once '../classes/Database.php';
require_once '../classes/User.php';
require_once '../classes/UserDAO.php';

if (isset($_SESSION['user_id'])) {
    header('Location: index.php');
    exit;
}

$erreur = '';

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $email = trim($_POST['email']        ?? '');
    $mdp   =      $_POST['mot_de_passe'] ?? '';

    if (empty($email) || empty($mdp)) {
        $erreur = 'Veuillez remplir tous les champs.';
    } else {
        $dao  = new UserDAO();
        $user = $dao->connecter($email, $mdp);

        if ($user === null) {
            $erreur = 'Email ou mot de passe incorrect.';
        } else {
            $_SESSION['user_id']   = $user->getId();
            $_SESSION['user_nom']  = $user->getNom();
            $_SESSION['user_role'] = $user->getRole();

            if ($user->isAdmin()) {
                header('Location: ../admin/dashboard.php');
            } else {
                header('Location: index.php');
            }
            exit;
        }
    }
}
?>
<!DOCTYPE html>
<html lang="fr" class="dark">
<head>
    <title>Connexion — SportLoc</title>
    <?php include '../templates/head.php'; ?>
</head>
<body class="bg-navy text-slate-200 font-body min-h-screen flex flex-col">

<?php include '../templates/header.php'; ?>

<main class="flex-grow flex items-center justify-center px-6 py-20 relative overflow-hidden">
    <!-- Background Elements -->
    <div class="absolute top-1/4 -left-20 w-96 h-96 bg-sport-blue-light/10 blur-[120px] rounded-full"></div>
    <div class="absolute bottom-1/4 -right-20 w-96 h-96 bg-emerald-500/10 blur-[120px] rounded-full"></div>

    <div class="w-full max-w-5xl grid grid-cols-1 lg:grid-cols-2 glass rounded-[40px] overflow-hidden shadow-2xl">
        <!-- Left Side: Image/Branding -->
        <div class="hidden lg:block relative">
            <img src="https://images.unsplash.com/photo-1522163182402-834f871fd851?auto=format&fit=crop&q=80&w=2003" 
                 class="w-full h-full object-cover" alt="Climber">
            <div class="absolute inset-0 bg-gradient-to-t from-navy via-navy/20 to-transparent"></div>
            <div class="absolute bottom-12 left-12 right-12">
                <h2 class="text-4xl text-white mb-4 leading-tight">VOTRE PROCHAINE CONQUÊTE <br><span class="text-sport-blue-light font-bold">COMMENCE ICI.</span></h2>
                <p class="text-slate-300 text-sm leading-relaxed">Connectez-vous pour accéder à votre équipement premium et gérer vos réservations d'aventure.</p>
            </div>
        </div>

        <!-- Right Side: Form -->
        <div class="p-12 lg:p-20 flex flex-col justify-center">
            <div class="mb-10">
                <h2 class="text-4xl text-white mb-2">BON RETOUR</h2>
                <p class="text-slate-500">Heureux de vous revoir parmi nous.</p>
            </div>

            <?php if ($erreur): ?>
                <div class="mb-8 p-4 bg-red-500/10 border border-red-500/20 rounded-xl flex items-center gap-3 text-red-400 text-sm">
                    <i class="fa-solid fa-circle-exclamation"></i>
                    <?= $erreur ?>
                </div>
            <?php endif; ?>

            <form method="POST" action="connexion.php" class="space-y-6">
                <div class="space-y-2">
                    <label for="email" class="text-xs font-bold tracking-widest text-slate-400 uppercase">Adresse Email</label>
                    <div class="relative">
                        <i class="fa-solid fa-envelope absolute left-4 top-1/2 -translate-y-1/2 text-slate-500 text-sm"></i>
                        <input type="email" id="email" name="email" required
                               placeholder="aventurier@email.com"
                               value="<?= htmlspecialchars($_POST['email'] ?? '') ?>"
                               class="w-full bg-white/5 border border-white/10 rounded-2xl pl-12 pr-6 py-4 text-white focus:outline-none focus:border-sport-blue-light transition-colors placeholder:text-slate-600">
                    </div>
                </div>

                <div class="space-y-2">
                    <div class="flex justify-between items-center">
                        <label for="mot_de_passe" class="text-xs font-bold tracking-widest text-slate-400 uppercase">Mot de passe</label>
                        <a href="#" class="text-[10px] font-bold text-sport-blue-light hover:text-white transition-colors uppercase tracking-widest">Oublié ?</a>
                    </div>
                    <div class="relative">
                        <i class="fa-solid fa-lock absolute left-4 top-1/2 -translate-y-1/2 text-slate-500 text-sm"></i>
                        <input type="password" id="mot_de_passe" name="mot_de_passe" required
                               placeholder="••••••••"
                               class="w-full bg-white/5 border border-white/10 rounded-2xl pl-12 pr-6 py-4 text-white focus:outline-none focus:border-sport-blue-light transition-colors placeholder:text-slate-600">
                    </div>
                </div>

                <button type="submit" class="w-full bg-sport-blue-light hover:bg-blue-400 text-white font-bold py-5 rounded-2xl transition-all btn-premium shadow-lg shadow-sport-blue-light/20">
                    SE CONNECTER
                </button>
            </form>

            <div class="mt-12 pt-8 border-t border-white/5 text-center">
                <p class="text-slate-500 text-sm mb-6">Pas encore de compte ?</p>
                <a href="inscription.php" class="inline-flex items-center gap-2 text-sm font-bold text-white hover:text-sport-blue-light transition-colors group">
                    CRÉER UN COMPTE <i class="fa-solid fa-arrow-right group-hover:translate-x-1 transition-transform"></i>
                </a>
            </div>

            <!-- Demo accounts box -->
            <div class="mt-8 p-6 rounded-2xl bg-white/[0.02] border border-white/5">
                <div class="flex items-center gap-2 text-[10px] font-bold text-slate-500 uppercase tracking-[0.2em] mb-3">
                    <i class="fa-solid fa-flask"></i> Comptes de test
                </div>
                <div class="grid grid-cols-2 gap-4 text-xs">
                    <div>
                        <div class="text-slate-300 font-bold mb-1">Admin</div>
                        <div class="text-slate-500">admin@sport.com</div>
                        <div class="text-slate-500">admin123</div>
                    </div>
                    <div>
                        <div class="text-slate-300 font-bold mb-1">Client</div>
                        <div class="text-slate-500">jean@email.com</div>
                        <div class="text-slate-500">password</div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</main>

<?php include '../templates/footer.php'; ?>

</body>
</html>

