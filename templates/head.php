<meta charset="UTF-8">
<meta name="viewport" content="width=device-width, initial-scale=1.0">
<link rel="preconnect" href="https://fonts.googleapis.com">
<link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
<link href="https://fonts.googleapis.com/css2?family=Bebas+Neue&family=DM+Sans:ital,opsz,wght@0,9..40,100..1000;1,9..40,100..1000&display=swap" rel="stylesheet">
<script src="https://cdn.tailwindcss.com"></script>
<link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css">
<script>
    tailwind.config = {
        darkMode: 'class',
        theme: {
            extend: {
                colors: {
                    navy: '#0F172A',
                    'sport-blue': '#1E40AF',
                    'sport-blue-light': '#3B82F6',
                    'nature-green': '#10B981',
                    'flame-orange': '#F97316',
                },
                fontFamily: {
                    heading: ['Bebas Neue', 'sans-serif'],
                    body: ['DM Sans', 'sans-serif'],
                },
            }
        }
    }
</script>
<style type="text/tailwindcss">
    @layer base {
        body {
            @apply bg-navy text-slate-200 font-body;
        }
        h1, h2, h3, h4, h5, h6 {
            @apply font-heading tracking-wider;
        }
    }
    @layer components {
        .glass {
            @apply bg-white/10 backdrop-blur-md border border-white/20;
        }
        .btn-premium {
            @apply relative overflow-hidden transition-all duration-300 transform hover:scale-105 active:scale-95;
        }
    }
</style>

