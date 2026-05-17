<nav class="fixed top-0 left-0 right-0 z-50 transition-all duration-300 glass border-b border-white/10 px-6 py-4 flex items-center justify-between">
    <a href="dashboard.php" class="flex items-center gap-3 group">
        <div class="relative">
            <div class="w-12 h-12 bg-gradient-to-br from-emerald-500 to-teal-700 rounded-xl flex items-center justify-center text-white text-2xl shadow-xl shadow-emerald-500/30 group-hover:rotate-[15deg] transition-all duration-500">
                <i class="fa-solid fa-shield-halved"></i>
            </div>
        </div>
        <div class="flex flex-col">
            <span class="text-2xl font-heading text-white tracking-[0.2em] leading-none">SportLoc <span class="text-emerald-500">ADMIN</span></span>
            <span class="text-[8px] font-bold text-emerald-500 tracking-[0.3em] uppercase">Control Center</span>
        </div>
    </a>

    <div class="hidden md:flex items-center gap-8">
        <a href="dashboard.php" class="text-sm font-medium hover:text-emerald-500 transition-colors <?= basename($_SERVER['PHP_SELF']) == 'dashboard.php' ? 'text-emerald-500' : 'text-slate-300' ?>">DASHBOARD</a>
        <a href="materiel.php" class="text-sm font-medium hover:text-emerald-500 transition-colors <?= basename($_SERVER['PHP_SELF']) == 'materiel.php' ? 'text-emerald-500' : 'text-slate-300' ?>">MATÉRIEL</a>
        <a href="categories.php" class="text-sm font-medium hover:text-emerald-500 transition-colors <?= basename($_SERVER['PHP_SELF']) == 'categories.php' ? 'text-emerald-500' : 'text-slate-300' ?>">CATÉGORIES</a>
        <a href="reservations.php" class="text-sm font-medium hover:text-emerald-500 transition-colors <?= basename($_SERVER['PHP_SELF']) == 'reservations.php' ? 'text-emerald-500' : 'text-slate-300' ?>">RÉSERVATIONS</a>
    </div>

    <div class="flex items-center gap-4">
        <a href="../public/index.php" class="text-xs font-bold text-slate-400 hover:text-white transition-colors uppercase tracking-widest">Voir le site</a>
        <a href="../public/deconnexion.php" class="px-6 py-2 bg-red-500/10 hover:bg-red-500/20 text-red-400 border border-red-500/20 rounded-full font-bold text-sm transition-all">
            QUITTER
        </a>
    </div>
</nav>

<!-- Spacer for fixed nav -->
<div class="h-20"></div>

