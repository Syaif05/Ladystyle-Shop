<?php // index.php
require __DIR__ . '/includes/config.php';
require __DIR__ . '/includes/auth.php';
?>
<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <title>LadyStyle Shop - Fashion Wanita Kekinian</title>
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <script src="https://cdn.tailwindcss.com"></script>
    <script>
        tailwind.config = {
            theme: {
                extend: {
                    colors: {
                        'ls-bg': '#fdf2f8',
                        'ls-bg-soft': '#fee2f2',
                        'ls-pink': '#fb7185',
                        'ls-pink-soft': '#fecdd3',
                        'ls-ink': '#0f172a'
                    },
                    boxShadow: {
                        'ls-soft': '0 18px 45px rgba(251, 113, 133, 0.35)'
                    },
                    borderRadius: {
                        '3xl': '1.75rem'
                    }
                }
            }
        };
    </script>
    <link href="https://fonts.googleapis.com/css2?family=Poppins:wght@400;500;600;700&display=swap" rel="stylesheet">
    <link href="/ladystyle-shop/assets/css/style.css" rel="stylesheet">
</head>
<body class="min-h-screen bg-gradient-to-b from-white via-ls-bg-soft to-white text-ls-ink">
    <div class="relative overflow-hidden">
        <div class="pointer-events-none absolute -top-40 -left-40 h-80 w-80 rounded-full bg-ls-bg blur-3xl opacity-70"></div>
        <div class="pointer-events-none absolute -bottom-40 -right-32 h-96 w-96 rounded-full bg-ls-bg-soft blur-3xl opacity-80"></div>

        <header class="sticky top-0 z-20 bg-white/70 backdrop-blur-xl border-b border-pink-100/70">
            <nav class="mx-auto flex max-w-6xl items-center justify-between px-4 py-3">
                <div class="flex items-center gap-2">
                    <div class="flex h-9 w-9 items-center justify-center rounded-2xl bg-gradient-to-tr from-ls-pink to-rose-400 shadow-ls-soft">
                        <span class="text-xs font-semibold tracking-widest text-white">LS</span>
                    </div>
                    <div class="leading-tight">
                        <p class="text-sm font-semibold tracking-[0.18em] text-ls-ink/80 uppercase">LadyStyle</p>
                        <p class="text-[11px] text-ls-ink/60">Soft & Modern Fashion Store</p>
                    </div>
                </div>
                <div class="hidden items-center gap-6 text-sm font-medium text-ls-ink/70 md:flex">
                    <a href="#hero" class="transition-colors hover:text-ls-pink">Beranda</a>
                    <a href="/ladystyle-shop/products.php" class="transition-colors hover:text-ls-pink">Koleksi</a>
                    <a href="#blog" class="transition-colors hover:text-ls-pink">Style Blog</a>
                    <a href="#contact" class="transition-colors hover:text-ls-pink">Kontak</a>
                </div>
                <div class="flex items-center gap-2">
                    <a href="/ladystyle-shop/login.php" class="hidden rounded-full border border-pink-200 px-3 py-1.5 text-xs font-medium text-ls-ink/80 shadow-sm transition hover:border-ls-pink hover:bg-ls-pink-soft/50 md:inline-flex">
                        Login Admin
                    </a>
                    <button class="inline-flex h-9 items-center justify-center rounded-full bg-ls-pink px-4 text-xs font-semibold text-white shadow-ls-soft transition hover:scale-[1.02] hover:bg-rose-500 active:scale-95">
                        Shop Now
                    </button>
                </div>
            </nav>
        </header>

        <main class="mx-auto max-w-6xl px-4 pb-16 pt-10">
            <section id="hero" class="grid gap-10 md:grid-cols-[1.15fr_1fr] md:items-center">
                <div class="space-y-5">
                    <div class="inline-flex items-center gap-2 rounded-full bg-ls-bg-soft px-3 py-1 text-[11px] font-medium text-ls-pink shadow-sm ring-1 ring-pink-100/70">
                        <span class="h-1.5 w-1.5 rounded-full bg-ls-pink animate-pulse"></span>
                        Koleksi baru minggu ini
                    </div>
                    <h1 class="text-3xl font-semibold leading-tight text-ls-ink sm:text-4xl lg:text-5xl">
                        Fashion wanita manis,<br class="hidden sm:block">
                        <span class="bg-gradient-to-r from-ls-pink to-rose-400 bg-clip-text text-transparent">untuk setiap momen spesial.</span>
                    </h1>
                    <p class="max-w-xl text-sm leading-relaxed text-ls-ink/70 sm:text-base">
                        LadyStyle menghadirkan dress, blouse, dan hijab dengan nuansa pastel lembut yang nyaman dipakai,
                        manis di kamera, dan cocok untuk daily outfit sampai acara penting.
                    </p>
                    <div class="flex flex-wrap items-center gap-3">
                        <a href="/ladystyle-shop/products.php" class="inline-flex items-center justify-center rounded-full bg-ls-pink px-5 py-2 text-xs font-semibold text-white shadow-ls-soft transition hover:translate-y-0.5 hover:bg-rose-500 active:scale-95 md:text-sm">
                            Lihat Koleksi Lengkap
                        </a>
                        <a href="#new-arrivals" class="inline-flex items-center justify-center rounded-full border border-pink-200 bg-white/70 px-4 py-2 text-xs font-medium text-ls-ink/80 shadow-sm transition hover:border-ls-pink hover:bg-ls-bg-soft/80 md:text-sm">
                            Lihat New Arrivals
                        </a>
                    </div>
                    <div class="flex flex-wrap gap-4 pt-2 text-[11px] text-ls-ink/70 sm:text-xs">
                        <div class="flex items-center gap-2">
                            <span class="inline-flex h-6 w-6 items-center justify-center rounded-full bg-ls-bg-soft text-[11px] text-ls-pink">★</span>
                            <div>
                                <p class="font-semibold">Bahan nyaman</p>
                                <p>Kain lembut dan tidak menerawang.</p>
                            </div>
                        </div>
                        <div class="flex items-center gap-2">
                            <span class="inline-flex h-6 w-6 items-center justify-center rounded-full bg-ls-bg-soft text-[11px] text-ls-pink">❤</span>
                            <div>
                                <p class="font-semibold">Style kekinian</p>
                                <p>Cutting manis ala Korean look.</p>
                            </div>
                        </div>
                    </div>
                </div>

                <div class="relative flex items-center justify-center">
                    <div class="relative h-[320px] w-[260px] rounded-3xl bg-gradient-to-b from-white via-ls-bg-soft to-white shadow-ls-soft ring-1 ring-pink-100/80 md:h-[360px] md:w-[280px]">
                        <div class="absolute inset-0 flex flex-col items-center justify-center gap-3">
                            <div class="h-40 w-40 rounded-full bg-gradient-to-br from-ls-pink-soft via-white to-ls-bg-soft shadow-inner"></div>
                            <div class="rounded-2xl bg-white/80 px-4 py-3 text-center backdrop-blur">
                                <p class="text-xs font-semibold tracking-[0.18em] text-ls-pink uppercase">LadyStyle Edit</p>
                                <p class="mt-1 text-sm font-semibold text-ls-ink">Soft Pastel Dress</p>
                                <p class="text-xs text-ls-ink/60">Perfect for brunch, date, and hangout.</p>
                            </div>
                        </div>
                        <div class="absolute -right-7 top-8 rounded-2xl bg-white px-3 py-2 text-[11px] shadow-md">
                            <p class="font-semibold text-ls-ink/80">Best Seller</p>
                            <p class="text-[10px] text-ls-ink/60">1.2k+ terjual</p>
                        </div>
                        <div class="absolute -left-6 bottom-7 rounded-full bg-white px-3 py-1.5 text-[10px] font-medium text-ls-pink shadow">
                            Free styling tips
                        </div>
                    </div>
                </div>
            </section>

            <section id="new-arrivals" class="mt-14 space-y-5">
                <div class="flex flex-wrap items-end justify-between gap-3">
                    <div>
                        <h2 class="text-xl font-semibold text-ls-ink sm:text-2xl">New Arrivals</h2>
                        <p class="text-sm text-ls-ink/70">Beberapa contoh koleksi. Nanti bagian ini akan terisi otomatis dari database produk.</p>
                    </div>
                    <a href="/ladystyle-shop/products.php" class="text-xs font-medium text-ls-pink underline-offset-2 hover:underline">
                        Lihat semua produk
                    </a>
                </div>

                <div class="grid gap-4 sm:grid-cols-2 lg:grid-cols-3">
                    <article class="group rounded-3xl bg-white/90 p-4 shadow-md ring-1 ring-pink-100/80 transition hover:-translate-y-1 hover:shadow-ls-soft hover:ring-ls-pink">
                        <div class="relative overflow-hidden rounded-2xl bg-ls-bg-soft">
                            <div class="h-40 w-full bg-[radial-gradient(circle_at_20%_20%,#fecdd3,#fee2e2)]"></div>
                            <span class="absolute left-3 top-3 rounded-full bg-white/80 px-2 py-1 text-[10px] font-medium text-ls-pink">Pastel Pink</span>
                        </div>
                        <div class="mt-3 space-y-1">
                            <h3 class="text-sm font-semibold text-ls-ink">Pastel Bloom Dress</h3>
                            <p class="text-xs text-ls-ink/60">Flowy dress dengan nuansa pink lembut.</p>
                            <p class="text-sm font-semibold text-ls-pink">Rp 249.000</p>
                        </div>
                    </article>

                    <article class="group rounded-3xl bg-white/90 p-4 shadow-md ring-1 ring-pink-100/80 transition hover:-translate-y-1 hover:shadow-ls-soft hover:ring-ls-pink">
                        <div class="relative overflow-hidden rounded-2xl bg-ls-bg-soft">
                            <div class="h-40 w-full bg-[radial-gradient(circle_at_20%_20%,#e0f2fe,#fdf2f8)]"></div>
                            <span class="absolute left-3 top-3 rounded-full bg-white/80 px-2 py-1 text-[10px] font-medium text-ls-pink">Hijab</span>
                        </div>
                        <div class="mt-3 space-y-1">
                            <h3 class="text-sm font-semibold text-ls-ink">Cloudy Voal Scarf</h3>
                            <p class="text-xs text-ls-ink/60">Hijab voal adem, jatuh, dan anti ribet.</p>
                            <p class="text-sm font-semibold text-ls-pink">Rp 89.000</p>
                        </div>
                    </article>

                    <article class="group rounded-3xl bg-white/90 p-4 shadow-md ring-1 ring-pink-100/80 transition hover:-translate-y-1 hover:shadow-ls-soft hover:ring-ls-pink">
                        <div class="relative overflow-hidden rounded-2xl bg-ls-bg-soft">
                            <div class="h-40 w-full bg-[radial-gradient(circle_at_20%_20%,#fee2e2,#fef9c3)]"></div>
                            <span class="absolute left-3 top-3 rounded-full bg-white/80 px-2 py-1 text-[10px] font-medium text-ls-pink">Blouse</span>
                        </div>
                        <div class="mt-3 space-y-1">
                            <h3 class="text-sm font-semibold text-ls-ink">Sunny Linen Blouse</h3>
                            <p class="text-xs text-ls-ink/60">Blouse ringan untuk daily outfit.</p>
                            <p class="text-sm font-semibold text-ls-pink">Rp 179.000</p>
                        </div>
                    </article>
                </div>
            </section>

            <section id="blog" class="mt-14 grid gap-6 md:grid-cols-[1.2fr_1fr] md:items-center">
                <div class="space-y-3">
                    <h2 class="text-xl font-semibold text-ls-ink sm:text-2xl">Style tips manis dan simple</h2>
                    <p class="text-sm text-ls-ink/70">
                        Kamu bisa gunakan website ini bukan hanya untuk belanja, tapi juga sebagai referensi mix and match outfit.
                        Di laporan tugas akhir, bagian ini bisa kamu jelaskan sebagai fitur konten/edukasi fashion untuk user.
                    </p>
                    <ul class="mt-2 space-y-1 text-sm text-ls-ink/75">
                        <li>• Kombinasi satu warna dominan + aksen pastel.</li>
                        <li>• Hijab basic dengan dress bermotif halus.</li>
                        <li>• Gunakan tas kecil dan aksesoris minim supaya look tetap clean.</li>
                    </ul>
                </div>
                <div class="rounded-3xl bg-gradient-to-br from-rose-100 via-white to-pink-100 p-1.5 shadow-md">
                    <div class="flex h-full w-full items-center justify-center rounded-[1.4rem] bg-white/70 px-6 py-6">
                        <div class="space-y-3 text-center">
                            <p class="text-xs font-medium tracking-[0.22em] text-ls-pink uppercase">LadyStyle Mood</p>
                            <p class="text-sm text-ls-ink/80">
                                “Cute, comfy, dan percaya diri setiap hari.”
                            </p>
                        </div>
                    </div>
                </div>
            </section>

            <section id="contact" class="mt-14 border-t border-pink-100 pt-8">
                <div class="flex flex-wrap items-center justify-between gap-3 text-sm">
                    <div>
                        <p class="text-xs font-semibold tracking-[0.22em] text-ls-pink uppercase">Kontak</p>
                        <p class="text-sm text-ls-ink/80">Untuk pemesanan dan kerja sama, hubungi kami via WhatsApp atau Instagram.</p>
                    </div>
                    <div class="flex flex-wrap items-center gap-2">
                        <a href="#" class="inline-flex items-center rounded-full bg-ls-pink px-4 py-1.5 text-xs font-semibold text-white shadow-md transition hover:bg-rose-500">
                            WhatsApp Toko
                        </a>
                        <a href="#" class="inline-flex items-center rounded-full border border-pink-200 bg-white px-3 py-1.5 text-xs font-medium text-ls-ink/80">
                            @ladystyle.official
                        </a>
                    </div>
                </div>
            </section>
        </main>
    </div>
</body>
</html>
