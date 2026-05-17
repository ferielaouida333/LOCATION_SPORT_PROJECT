<nav class="fixed top-0 left-0 right-0 z-50 transition-all duration-300 glass border-b border-white/10 px-6 py-4 flex items-center justify-between">
    <a href="index.php" class="flex items-center gap-3 group">
        <div class="relative">
            <div class="w-12 h-12 bg-gradient-to-br from-sport-blue-light to-blue-700 rounded-xl flex items-center justify-center text-white text-2xl shadow-xl shadow-sport-blue-light/30 group-hover:rotate-[15deg] transition-all duration-500">
                <i class="fa-solid fa-mountain-sun"></i>
            </div>
            <div class="absolute -bottom-1 -right-1 w-5 h-5 bg-nature-green rounded-lg flex items-center justify-center text-[10px] text-white border-2 border-navy">
                <i class="fa-solid fa-check"></i>
            </div>
        </div>
        <div class="flex flex-col">
            <span class="text-2xl font-heading text-white tracking-[0.2em] leading-none">SportLoc</span>
            <span class="text-[8px] font-bold text-sport-blue-light tracking-[0.3em] uppercase">Premium Equipment</span>
        </div>
    </a>

    <div class="hidden md:flex items-center gap-8">
        <a href="index.php" class="text-xs font-bold tracking-widest hover:text-sport-blue-light transition-all duration-300 <?= basename($_SERVER['PHP_SELF']) == 'index.php' ? 'text-sport-blue-light border-b-2 border-sport-blue-light pb-1' : 'text-slate-400' ?>">CATALOGUE</a>
        <a href="catalogue.php" class="text-xs font-bold tracking-widest hover:text-sport-blue-light transition-all duration-300 <?= basename($_SERVER['PHP_SELF']) == 'catalogue.php' ? 'text-sport-blue-light border-b-2 border-sport-blue-light pb-1' : 'text-slate-400' ?>">EXPLORER</a>
    </div>

    <div class="flex items-center gap-4">
        <?php if (isset($_SESSION['user_id'])): ?>
            <div class="flex items-center gap-4">
                <a href="mon_compte.php" class="flex items-center gap-2 px-4 py-2 rounded-full bg-white/5 hover:bg-white/10 transition-all border border-white/10">
                    <div class="w-6 h-6 rounded-full bg-sport-blue-light flex items-center justify-center text-[10px] text-white">
                        <i class="fa-solid fa-user"></i>
                    </div>
                    <span class="text-sm font-medium"><?= htmlspecialchars($_SESSION['user_nom']) ?></span>
                </a>
                <a href="deconnexion.php" class="p-2 text-slate-400 hover:text-white transition-colors" title="Déconnexion">
                    <i class="fa-solid fa-arrow-right-from-bracket"></i>
                </a>
            </div>
        <?php else: ?>
            <a href="connexion.php" class="text-sm font-medium text-slate-300 hover:text-white transition-colors">CONNEXION</a>
            <a href="inscription.php" class="px-6 py-2 bg-sport-blue-light hover:bg-blue-400 text-white rounded-full font-bold text-sm btn-premium shadow-lg shadow-sport-blue-light/30">
                REJOINDRE
            </a>
        <?php endif; ?>
        
        <button class="md:hidden text-white text-2xl">
            <i class="fa-solid fa-bars"></i>
        </button>
    </div>
</nav>

<!-- Spacer for fixed nav -->
<div class="h-20"></div>

