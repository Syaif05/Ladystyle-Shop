<?php // dashboard/settings.php
require __DIR__ . '/../includes/config.php';
require __DIR__ . '/../includes/auth.php';
require __DIR__ . '/../includes/functions.php';

require_login();
$message = '';
$msgType = '';

// Handle Update Settings
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $hero_title = trim($_POST['hero_title']);
    $hero_subtitle = trim($_POST['hero_subtitle']);
    $cta_text = trim($_POST['cta_text']);
    $cta_link = trim($_POST['cta_link']);
    
    // Default image path
    $hero_image = $_POST['current_hero_image'];

    // Handle File Upload
    if (isset($_FILES['hero_image']) && $_FILES['hero_image']['error'] === UPLOAD_ERR_OK) {
        $fileTmp = $_FILES['hero_image']['tmp_name'];
        $fileName = time() . '-' . $_FILES['hero_image']['name'];
        $uploadDir = '../assets/images/banners/';
        
        if (!is_dir($uploadDir)) mkdir($uploadDir, 0777, true);
        
        if (move_uploaded_file($fileTmp, $uploadDir . $fileName)) {
            $hero_image = '/ladystyle-shop/assets/images/banners/' . $fileName;
        }
    } else if (!empty($_POST['hero_image_url'])) {
        $hero_image = trim($_POST['hero_image_url']);
    }

    $stmt = $pdo->prepare("UPDATE landing_settings SET hero_title=?, hero_subtitle=?, hero_image=?, cta_text=?, cta_link=? WHERE id=1");
    if ($stmt->execute([$hero_title, $hero_subtitle, $hero_image, $cta_text, $cta_link])) {
        $message = "Tampilan halaman depan berhasil diperbarui!";
        $msgType = "success";
    } else {
        $message = "Gagal menyimpan pengaturan.";
        $msgType = "error";
    }
}

// Ambil Data Saat Ini
$setting = $pdo->query("SELECT * FROM landing_settings WHERE id=1")->fetch();
?>
<!DOCTYPE html>
<html lang="id">
<head>
    <title>Desain Halaman Depan - Admin</title>
    <?php require __DIR__ . '/../includes/head.php'; ?>
    <script>
        // Script untuk Live Preview Sederhana
        function updatePreview() {
            document.getElementById('prevTitle').innerText = document.querySelector('[name="hero_title"]').value;
            document.getElementById('prevSubtitle').innerText = document.querySelector('[name="hero_subtitle"]').value;
            document.getElementById('prevBtn').innerText = document.querySelector('[name="cta_text"]').value;
        }

        function previewImage(event) {
            const reader = new FileReader();
            reader.onload = function(){
                const output = document.getElementById('prevImgBg');
                output.src = reader.result;
            }
            if(event.target.files[0]) {
                reader.readAsDataURL(event.target.files[0]);
            }
        }
    </script>
</head>
<body class="bg-gray-50/50">
    <div class="flex h-screen overflow-hidden">
        <?php require __DIR__ . '/../includes/sidebar.php'; ?>
        
        <main class="flex-1 overflow-x-hidden overflow-y-auto bg-gray-50/50 p-6 md:p-8">
            <div class="max-w-7xl mx-auto">
                <header class="flex flex-col md:flex-row md:items-center justify-between gap-4 mb-8">
                    <div>
                        <h1 class="text-3xl font-bold text-gray-900 tracking-tight">Kustomisasi Tampilan</h1>
                        <p class="text-gray-500 text-sm mt-1">Ubah banner utama dan pesan promosi di halaman depan.</p>
                    </div>
                    <?php if ($message): ?>
                        <div class="px-4 py-2 rounded-xl text-sm font-bold flex items-center gap-2 animate-pulse <?= $msgType === 'success' ? 'bg-green-100 text-green-700' : 'bg-red-100 text-red-700' ?>">
                            <?= $message ?>
                        </div>
                    <?php endif; ?>
                </header>

                <form method="post" enctype="multipart/form-data" class="grid lg:grid-cols-[1fr_1.2fr] gap-8">
                    
                    <div class="space-y-6">
                        <div class="glass-panel p-6 rounded-3xl">
                            <h2 class="text-lg font-bold text-gray-800 mb-4 flex items-center gap-2">
                                <span class="w-8 h-8 rounded-lg bg-ls-100 text-ls-600 flex items-center justify-center">
                                    <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M11 5H6a2 2 0 00-2 2v11a2 2 0 002 2h11a2 2 0 002-2v-5m-1.414-9.414a2 2 0 112.828 2.828L11.828 15H9v-2.828l8.586-8.586z"/></svg>
                                </span>
                                Konten Teks
                            </h2>
                            <div class="space-y-4">
                                <div>
                                    <label class="block text-xs font-bold text-gray-400 uppercase mb-2 ml-1">Judul Utama (Headline)</label>
                                    <input type="text" name="hero_title" value="<?= htmlspecialchars($setting['hero_title']) ?>" oninput="updatePreview()" 
                                           class="w-full px-4 py-3 rounded-xl bg-white border border-gray-200 focus:ring-2 focus:ring-ls-200 outline-none font-bold text-gray-800">
                                </div>
                                <div>
                                    <label class="block text-xs font-bold text-gray-400 uppercase mb-2 ml-1">Deskripsi Singkat</label>
                                    <textarea name="hero_subtitle" rows="3" oninput="updatePreview()"
                                              class="w-full px-4 py-3 rounded-xl bg-white border border-gray-200 focus:ring-2 focus:ring-ls-200 outline-none text-sm text-gray-600 leading-relaxed"><?= htmlspecialchars($setting['hero_subtitle']) ?></textarea>
                                </div>
                            </div>
                        </div>

                        <div class="glass-panel p-6 rounded-3xl">
                            <h2 class="text-lg font-bold text-gray-800 mb-4 flex items-center gap-2">
                                <span class="w-8 h-8 rounded-lg bg-blue-100 text-blue-600 flex items-center justify-center">
                                    <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M13 10V3L4 14h7v7l9-11h-7z"/></svg>
                                </span>
                                Tombol Aksi (CTA)
                            </h2>
                            <div class="grid grid-cols-2 gap-4">
                                <div>
                                    <label class="block text-xs font-bold text-gray-400 uppercase mb-2 ml-1">Label Tombol</label>
                                    <input type="text" name="cta_text" value="<?= htmlspecialchars($setting['cta_text']) ?>" oninput="updatePreview()"
                                           class="w-full px-4 py-3 rounded-xl bg-white border border-gray-200 focus:ring-2 focus:ring-blue-200 outline-none font-medium text-sm">
                                </div>
                                <div>
                                    <label class="block text-xs font-bold text-gray-400 uppercase mb-2 ml-1">Link Tujuan</label>
                                    <input type="text" name="cta_link" value="<?= htmlspecialchars($setting['cta_link']) ?>" 
                                           class="w-full px-4 py-3 rounded-xl bg-white border border-gray-200 focus:ring-2 focus:ring-blue-200 outline-none font-medium text-sm text-blue-600">
                                </div>
                            </div>
                        </div>

                        <div class="glass-panel p-6 rounded-3xl">
                            <h2 class="text-lg font-bold text-gray-800 mb-4 flex items-center gap-2">
                                <span class="w-8 h-8 rounded-lg bg-purple-100 text-purple-600 flex items-center justify-center">
                                    <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 16l4.586-4.586a2 2 0 012.828 0L16 16m-2-2l1.586-1.586a2 2 0 012.828 0L20 14m-6-6h.01M6 20h12a2 2 0 002-2V6a2 2 0 00-2-2H6a2 2 0 00-2 2v12a2 2 0 002 2z"/></svg>
                                </span>
                                Gambar Banner
                            </h2>
                            
                            <div class="relative w-full h-32 rounded-xl border-2 border-dashed border-gray-300 bg-gray-50 hover:bg-white hover:border-ls-400 transition-all cursor-pointer flex flex-col items-center justify-center group overflow-hidden">
                                <input type="file" name="hero_image" onchange="previewImage(event)" class="absolute inset-0 w-full h-full opacity-0 cursor-pointer z-10">
                                <div class="text-center group-hover:scale-105 transition-transform duration-300">
                                    <svg class="w-8 h-8 mx-auto text-gray-400 group-hover:text-ls-500 mb-2" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M7 16a4 4 0 01-.88-7.903A5 5 0 1115.9 6L16 6a5 5 0 011 9.9M15 13l-3-3m0 0l-3 3m3-3v12"/></svg>
                                    <p class="text-xs font-bold text-gray-500 group-hover:text-ls-600">Klik untuk upload gambar baru</p>
                                    <p class="text-[10px] text-gray-400 mt-1">JPG, PNG, WEBP (Max 2MB)</p>
                                </div>
                            </div>
                            
                            <div class="mt-4">
                                <label class="block text-xs font-bold text-gray-400 uppercase mb-2 ml-1">Atau URL Gambar Eksternal</label>
                                <input type="text" name="hero_image_url" placeholder="https://..." class="w-full px-4 py-2 rounded-xl bg-white border border-gray-200 text-xs focus:ring-2 focus:ring-ls-200 outline-none">
                            </div>
                            <input type="hidden" name="current_hero_image" value="<?= htmlspecialchars($setting['hero_image']) ?>">
                        </div>
                        
                        <button type="submit" class="w-full py-4 rounded-xl bg-gray-900 text-white font-bold text-lg shadow-xl hover:bg-ls-600 hover:shadow-ls-500/30 hover:-translate-y-1 transition-all duration-300">
                            Simpan Perubahan
                        </button>
                    </div>

                    <div class="hidden lg:block">
                        <div class="sticky top-8">
                            <div class="flex items-center justify-between mb-4">
                                <h3 class="font-bold text-gray-500 text-sm uppercase tracking-wider">Live Preview (Desktop Mode)</h3>
                                <span class="px-2 py-1 bg-green-100 text-green-700 text-[10px] font-bold rounded uppercase">Real-time</span>
                            </div>
                            
                            <div class="bg-white rounded-[2rem] shadow-2xl overflow-hidden border border-gray-100 relative">
                                <div class="bg-gray-100 px-4 py-3 flex gap-2 border-b border-gray-200">
                                    <div class="w-3 h-3 rounded-full bg-red-400"></div>
                                    <div class="w-3 h-3 rounded-full bg-yellow-400"></div>
                                    <div class="w-3 h-3 rounded-full bg-green-400"></div>
                                    <div class="ml-4 flex-1 bg-white rounded-md h-4 opacity-50"></div>
                                </div>

                                <div class="p-6 bg-gray-50 relative overflow-hidden h-[500px] flex items-center">
                                    <div class="absolute top-0 right-0 w-64 h-64 bg-purple-200 rounded-full blur-3xl opacity-30"></div>
                                    
                                    <div class="grid grid-cols-1 gap-6 w-full relative z-10">
                                        <div class="space-y-4">
                                            <span class="inline-block px-3 py-1 rounded-full bg-ls-50 text-ls-600 text-[10px] font-bold uppercase border border-ls-100">
                                                New Collection
                                            </span>
                                            <h1 id="prevTitle" class="text-3xl font-extrabold text-gray-900 leading-tight">
                                                <?= htmlspecialchars($setting['hero_title']) ?>
                                            </h1>
                                            <p id="prevSubtitle" class="text-sm text-gray-500 leading-relaxed">
                                                <?= htmlspecialchars($setting['hero_subtitle']) ?>
                                            </p>
                                            <div class="pt-2">
                                                <span id="prevBtn" class="px-6 py-2 rounded-full bg-gray-900 text-white text-xs font-bold shadow-lg">
                                                    <?= htmlspecialchars($setting['cta_text']) ?>
                                                </span>
                                            </div>
                                        </div>

                                        <div class="relative h-48 w-full rounded-3xl overflow-hidden shadow-lg transform rotate-2 mt-4 bg-gray-200">
                                            <img id="prevImgBg" src="<?= htmlspecialchars($setting['hero_image']) ?>" class="w-full h-full object-cover object-top">
                                            <div class="absolute bottom-0 inset-x-0 h-16 bg-gradient-to-t from-black/50 to-transparent"></div>
                                        </div>
                                    </div>
                                </div>
                            </div>
                            <p class="text-center text-xs text-gray-400 mt-4">Preview ini adalah simulasi visual sederhana.</p>
                        </div>
                    </div>
                </form>
            </div>
        </main>
    </div>
</body>
</html>
