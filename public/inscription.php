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

$erreur  = '';
$succes  = '';

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $nom   = trim(htmlspecialchars($_POST['nom']   ?? ''));
    $email = trim(htmlspecialchars($_POST['email'] ?? ''));
    $mdp   = $_POST['mot_de_passe'] ?? '';

    if (empty($nom)) {
        $erreur = 'Le nom est obligatoire.';
    } elseif (!filter_var($email, FILTER_VALIDATE_EMAIL)) {
        $erreur = 'Adresse email invalide.';
    } elseif (strlen($mdp) < 6) {
        $erreur = 'Le mot de passe doit faire au moins 6 caractères.';
    } else {
        $dao = new UserDAO();
        if ($dao->inscrire($nom, $email, $mdp)) {
            $succes = 'Compte créé avec succès ! Vous pouvez maintenant vous connecter.';
        } else {
            $erreur = 'Cette adresse email est déjà utilisée.';
        }
    }
}
?>
<!DOCTYPE html>
<html lang="fr" class="dark">
<head>
    <title>Inscription — SportLoc</title>
    <?php include '../templates/head.php'; ?>
</head>
<body class="bg-navy text-slate-200 font-body min-h-screen flex flex-col">

<?php include '../templates/header.php'; ?>

<main class="flex-grow flex items-center justify-center px-6 py-20 relative overflow-hidden">
    <!-- Background Elements -->
    <div class="absolute top-1/4 -right-20 w-96 h-96 bg-sport-blue-light/10 blur-[120px] rounded-full"></div>
    <div class="absolute bottom-1/4 -left-20 w-96 h-96 bg-orange-500/10 blur-[120px] rounded-full"></div>

    <div class="w-full max-w-5xl grid grid-cols-1 lg:grid-cols-2 glass rounded-[40px] overflow-hidden shadow-2xl">
        <!-- Left Side: Form -->
        <div class="p-12 lg:p-20 flex flex-col justify-center order-2 lg:order-1">
            <div class="mb-10">
                <h2 class="text-4xl text-white mb-2">REJOINDRE L'AVENTURE</h2>
                <p class="text-slate-500">Créez votre compte en quelques secondes.</p>
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
            <?php endif; ?>

            <form method="POST" action="inscription.php" class="space-y-6">
                <div class="space-y-2">
                    <label for="nom" class="text-xs font-bold tracking-widest text-slate-400 uppercase">Nom complet</label>
                    <div class="relative">
                        <i class="fa-solid fa-user absolute left-4 top-1/2 -translate-y-1/2 text-slate-500 text-sm"></i>
                        <input type="text" id="nom" name="nom" required
                               placeholder="Jean Dupont"
                               value="<?= htmlspecialchars($_POST['nom'] ?? '') ?>"
                               class="w-full bg-white/5 border border-white/10 rounded-2xl pl-12 pr-6 py-4 text-white focus:outline-none focus:border-sport-blue-light transition-colors placeholder:text-slate-600">
                    </div>
                </div>

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
                    <label for="mot_de_passe" class="text-xs font-bold tracking-widest text-slate-400 uppercase">Mot de passe</label>
                    <div class="relative">
                        <i class="fa-solid fa-lock absolute left-4 top-1/2 -translate-y-1/2 text-slate-500 text-sm"></i>
                        <input type="password" id="mot_de_passe" name="mot_de_passe" required
                               placeholder="Minimum 6 caractères"
                               class="w-full bg-white/5 border border-white/10 rounded-2xl pl-12 pr-6 py-4 text-white focus:outline-none focus:border-sport-blue-light transition-colors placeholder:text-slate-600">
                    </div>
                </div>

                <button type="submit" class="w-full bg-sport-blue-light hover:bg-blue-400 text-white font-bold py-5 rounded-2xl transition-all btn-premium shadow-lg shadow-sport-blue-light/20">
                    CRÉER MON COMPTE
                </button>
            </form>

            <div class="mt-12 pt-8 border-t border-white/5 text-center">
                <p class="text-slate-500 text-sm mb-6">Déjà un compte ?</p>
                <a href="connexion.php" class="inline-flex items-center gap-2 text-sm font-bold text-white hover:text-sport-blue-light transition-colors group">
                    SE CONNECTER <i class="fa-solid fa-arrow-right group-hover:translate-x-1 transition-transform"></i>
                </a>
            </div>
        </div>

        <!-- Right Side: Image/Branding -->
        <div class="hidden lg:block relative order-1 lg:order-2">
            <img src="https://images.unsplash.com/photo-1551524559-8af4e6624178?auto=format&fit=crop&q=80&w=1926" 
                 class="w-full h-full object-cover" alt="Skier">
            <div class="absolute inset-0 bg-gradient-to-t from-navy via-navy/20 to-transparent"></div>
            <div class="absolute bottom-12 left-12 right-12">
                <h2 class="text-4xl text-white mb-4 leading-tight">PLUS QU'UNE LOCATION, <br><span class="text-emerald-400 font-bold">UNE EXPÉRIENCE.</span></h2>
                <p class="text-slate-300 text-sm leading-relaxed">Accédez à des remises exclusives, un historique de vos aventures et un support prioritaire.</p>
            </div>
        </div>
    </div>
</main>

<?php include '../templates/footer.php'; ?>

</body>
</html>
