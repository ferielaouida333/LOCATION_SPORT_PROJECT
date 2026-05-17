<?php

$uploadsDir = __DIR__ . '/public/uploads/';

if (!is_dir($uploadsDir)) {
    mkdir($uploadsDir, 0777, true);
}

$images = [
    'vtt_pro.jpg'      => 'https://images.unsplash.com/photo-1576435728678-68d0fbf94e91?auto=format&fit=crop&q=80&w=800',
    'route_carbon.jpg' => 'https://images.unsplash.com/photo-1485965120184-e220f721d03e?auto=format&fit=crop&q=80&w=800',
    'wilson.jpg'       => 'https://images.unsplash.com/photo-1595435066313-3151b712c91b?auto=format&fit=crop&q=80&w=800',
    'atomic.jpg'       => 'https://images.unsplash.com/photo-1551698618-1dfe5d97d256?auto=format&fit=crop&q=80&w=800',
    'jones.jpg'        => 'https://images.unsplash.com/photo-1522056615691-da7b8106c665?auto=format&fit=crop&q=80&w=800',
    'climbing.jpg'     => 'https://images.unsplash.com/photo-1522163182402-834f871fd851?auto=format&fit=crop&q=80&w=800',
    'ebike.jpg'        => 'https://images.unsplash.com/photo-1571068316341-21c11865b10a?auto=format&fit=crop&q=80&w=800',
    'kayak.jpg'        => 'https://images.unsplash.com/photo-1544551763-46a013bb70d5?auto=format&fit=crop&q=80&w=800'
];

$context = stream_context_create([
    "http" => [
        "header" => "User-Agent: Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/91.0.4472.124 Safari/537.36\r\n"
    ]
]);

$results = [];
foreach ($images as $filename => $url) {
    if (!file_exists($uploadsDir . $filename)) {
        $content = @file_get_contents($url, false, $context);
        if ($content !== false) {
            file_put_contents($uploadsDir . $filename, $content);
            $results[] = "✅ $filename : Downloaded";
        } else {
            $results[] = "❌ $filename : Failed to download";
        }
    } else {
        $results[] = "ℹ️ $filename : Already exists";
    }
}

?>
<!DOCTYPE html>
<html lang="fr">
<head>
    <meta charset="UTF-8">
    <title>SportLoc Setup</title>
    <script src="https://cdn.tailwindcss.com"></script>
    <link href="https://fonts.googleapis.com/css2?family=Bebas+Neue&family=DM+Sans:wght@400;700&display=swap" rel="stylesheet">
    <style>
        body { font-family: 'DM Sans', sans-serif; background: #0F172A; color: white; }
        h1 { font-family: 'Bebas Neue', sans-serif; }
    </style>
</head>
<body class="min-h-screen flex items-center justify-center p-6">
    <div class="max-w-2xl w-full bg-white/5 border border-white/10 backdrop-blur-xl rounded-[40px] p-12 shadow-2xl">
        <h1 class="text-6xl mb-8 text-center tracking-widest">SportLoc <span class="text-blue-500">Setup</span></h1>
        
        <div class="space-y-8">
            <section>
                <h2 class="text-xl font-bold mb-4 text-blue-400 uppercase tracking-widest text-xs">1. Vérification des images</h2>
                <div class="bg-black/20 rounded-2xl p-6 text-sm font-mono space-y-1">
                    <?php foreach ($results as $res) echo "<div>$res</div>"; ?>
                </div>
            </section>

            <section>
                <h2 class="text-xl font-bold mb-4 text-blue-400 uppercase tracking-widest text-xs">2. Base de données</h2>
                <p class="text-slate-400 text-sm mb-4">
                    Pour voir les nouveaux titres et prix en DT, votre collaborateur doit importer le fichier :
                </p>
                <div class="bg-blue-500/10 border border-blue-500/20 rounded-xl p-4 flex items-center gap-4">
                    <div class="text-blue-500 text-2xl"><i class="fas fa-database"></i></div>
                    <code class="text-blue-300 font-bold">database_premium.sql</code>
                </div>
            </section>

            <div class="pt-8 text-center">
                <a href="public/index.php" class="inline-block px-10 py-5 bg-blue-600 hover:bg-blue-500 text-white font-bold rounded-2xl transition-all shadow-xl shadow-blue-600/20 uppercase tracking-widest text-xs">
                    Accéder au site Premium
                </a>
            </div>
        </div>
    </div>
</body>
</html>

