<?php

include '../config.php';

if (isset($_POST['add-projects'])) {
    $judul_project = $_POST['judul_project'];
    $deskripsi = $_POST['deskripsi'];
    $link = $_POST['link'];
    $nama_file = $_FILES['gambar_project']['name'];

    $stmt = $conn->prepare('INSERT INTO projects (judul_project, gambar_project, deskripsi, link, dibuat, selesai, teknologi) VALUES (?, ?, ?, ?, ?, ?, ?)');
    $stmt->bind_param('sssssss', $judul_project, $nama_file_baru, $deskripsi, $link, $dibuat, $selesai, $teknologi);
    $stmt->execute();
    header('location: ../user/index.php');
    exit;

}

$project = $conn->query("SELECT * FROM projects ORDER BY id DESC");

?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Document</title>
    <script src="https://cdn.jsdelivr.net/npm/@tailwindcss/browser@4"></script>
    <link href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/7.0.1/css/all.min.css" rel="stylesheet">
</head>
<body class="bg-gradient-to-br from-slate-900 via-purple-900 to-slate-900 text-white min-h-screen">
    <!-- Navigation -->
    <nav class="fixed top-0 w-full bg-black/80 backdrop-blur-md z-50 py-4">
        <div class="max-w-6xl mx-auto px-6 flex justify-between items-center">
            <!-- Logo/Nama -->
            <div class="text-2xl font-bold bg-gradient-to-r from-purple-400 to-pink-400 bg-clip-text text-transparent">
                Z_Forz
            </div>
            
            <!-- Menu Desktop -->
            <ul class="hidden md:flex space-x-8">
                <li><a href="#home" class="hover:text-purple-400 transition-colors font-medium">Home</a></li>
                <li><a href="#about" class="hover:text-purple-400 transition-colors font-medium">About</a></li>
                <li><a href="#projects" class="hover:text-purple-400 transition-colors font-medium">Projects</a></li>
                <li><a href="#contact" class="hover:text-purple-400 transition-colors font-medium">Contact</a></li>
            </ul>

            <!-- Mobile Menu Button -->
            <button class="md:hidden">
                <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 6h16M4 12h16M4 18h16"></path>
                </svg>
            </button>
        </div>
    </nav>

    <!-- Home Section -->
    <section id="home" class="min-h-screen flex items-center justify-center pt-20 px-6">
        <div class="max-w-4xl text-center">
            <img src="../images/elnunjuk.jpeg" 
                 alt="Profile" class="w-80 h-80 rounded-full mx-auto mb-8 shadow-2xl border-4 border-purple-500">
            <h1 class="text-5xl md:text-7xl font-bold bg-gradient-to-r from-purple-400 via-pink-400 to-purple-500 bg-clip-text text-transparent mb-6">
                MUhammad Taufiqurrohman
            </h1>
            <p class="text-xl md:text-2xl text-gray-300 mb-8 max-w-2xl mx-auto">
                Webdeveloper and Design Grafis<br>
                I'm a Webdeveloper and Design Grafis Junior
            </p>
            <a href="#projects" class="bg-gradient-to-r from-purple-500 to-pink-500 hover:from-purple-600 hover:to-pink-600 text-white px-8 py-4 rounded-full font-semibold text-lg transition-all duration-300 transform hover:scale-105 shadow-xl">
                Lihat Proyek Saya
            </a>
        </div>
    </section>

    <!-- About Section -->
    <section id="about" class="py-32 px-6 bg-black/30">
        <div class="max-w-6xl mx-auto">
            <h2 class="text-4xl md:text-5xl font-bold text-center mb-20 bg-gradient-to-r from-purple-400 to-pink-400 bg-clip-text text-transparent h-14">
                Tentang Saya
            </h2>
            <div class="grid md:grid-cols-2 gap-12 items-center">
                <div>
                    <p class="text-xl text-gray-300 leading-relaxed mb-8">
                        Hallo! Saya Muhammad Taufiqurrohman, seorang Design Grafis dan Webdeveloper Junior.
                        Saya memiliki ketertarikkan pada dunia desain grafis dan web development.
                        Saya bergabung kedunia desain grafis dan web developer pada tahun 2024.
                    </p>
                    <ul class="space-y-4 text-lg">
                        <li class="flex items-center">
                            <span class="w-3 h-3 bg-purple-500 rounded-full mr-4"></span>
                            Design: Adobe Ilustrator, Figma, Canva
                        </li>
                        <li class="flex items-center">
                            <span class="w-3 h-3 bg-purple-500 rounded-full mr-4"></span>
                            Frontend: tailwindcss
                        </li>
                        <li class="flex items-center">
                            <span class="w-3 h-3 bg-purple-500 rounded-full mr-4"></span>
                            Backend: Node.js, php
                        </li>
                        <li class="flex items-center">
                            <span class="w-3 h-3 bg-purple-500 rounded-full mr-4"></span>
                            Database: MySQL, MongoDB
                        </li>
                    </ul>
                </div>
                <div class="relative">
                    <img src="../images/elek.jpeg" 
                         alt="About" class="rounded-2xl shadow-2xl w-full h-96 object-cover">
                </div>
            </div>
        </div>
    </section>

    <!-- Projects Section -->
    <section id="projects" class="py-32 px-6">
        <div class="max-w-6xl mx-auto">
            <h2 class="text-4xl md:text-5xl font-bold text-center mb-20 bg-gradient-to-r from-purple-400 to-pink-400 bg-clip-text text-transparent h-14">
                Project
            </h2>
            <div class="grid md:grid-cols-2 lg:grid-cols-3 gap-8">
                <!-- Project Card 1 -->
                 <?php while ( $row = $project->fetch_assoc()): ?>
                <div class="group bg-white/10 backdrop-blur-md rounded-2xl p-6 border border-white/20 hover:border-purple-500 transition-all duration-300 hover:scale-105 hover:bg-white/20">
                    <div class="relative overflow-hidden rounded-xl mb-6 h-48">
                        <img src="../uploads/<?= $row['gambar_project']; ?>" width="200" 
                             alt="<?= $row['judul_project'] ?>" class="w-full h-full object-cover group-hover:scale-110 transition-transform duration-500">
                        <div class="absolute inset-0 bg-gradient-to-t from-black/60 to-transparent opacity-0 group-hover:opacity-100 transition-opacity duration-300"></div>
                    </div>
                    <h3 class="text-2xl font-bold mb-3"><?= $row['judul_project'] ?></h3>
                    <p class="text-gray-300 mb-6 leading-relaxed">
                        <?= $row['deskripsi']; ?>
                    </p>
                    <div class="flex space-x-2 mb-6">
                        <div class="px-3 py-1 bg-blue-500/30 text-blue-300 rounded-full text-sm">
                            <?= $row['teknologi']; ?>
                        </div>
                    </div>
                    <div class="flex space-x-4">
                        <a href="<?= $row['link'] ?>" class="bg-gradient-to-br from-purple-600 via-blue-500 to-purple-400 flex-1 border border-white/30 hover:border-purple-500 text-white py-3 px-6 rounded-xl text-center font-semibold transition-all duration-300">Kunjungi Project</a>
                    </div>
                </div>
                <?php endwhile; ?>
            </div>
        </div>
    </section>

    <!-- Contact Section -->
    <section id="contact" class="py-32 px-6 bg-black/30">
        <div class="max-w-4xl mx-auto text-center">
            <h2 class="text-4xl md:text-5xl font-bold mb-8 bg-gradient-to-r from-purple-400 to-pink-400 bg-clip-text text-transparent">
                Mari Berkolaborasi
            </h2>
            <p class="text-xl text-gray-300 mb-12 max-w-2xl mx-auto">
                Punya proyek menarik? Mari kita wujudkan bersama!<br>Tapi jangan hubungi saya.
            </p>
            <div class="grid md:grid-cols-4 gap-6 max-w-2xl mx-auto mb-12">
                <div class="p-6">
                    <a href="" class="w-16 h-16 bg-purple-500/20 rounded-2xl flex items-center justify-center mx-auto mb-4 text-center">
                        <svg class="w-8 h-8 text-purple-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 8l7.89 4.26a2 2 0 002.22 0L21 8M5 19h14a2 2 0 002-2V7a2 2 0 00-2-2H5a2 2 0 00-2 2v10a2 2 0 002 2z"></path>
                        </svg>
                    </a>
                    <h4 class="text-xl font-semibold mb-2">Email</h4>
                </div>
                <div class="p-6">
                    <a href="" class="w-16 h-16 bg-pink-500/20 rounded-2xl flex items-center justify-center mx-auto mb-4 text-center">
                        <i class="fa-brands fa-instagram fa-2xl" style="color: rgba(248, 103, 236, 1);"></i>
                    </a>
                    <h4 class="text-xl font-semibold mb-2">instagram</h4>
                </div>
                <div class="p-6">
                    <a href="" class="w-16 h-16 bg-gray-500/20 rounded-2xl flex items-center justify-center mx-auto mb-4 text-center">
                        <i class="fa-brands fa-tiktok fa-2xl" style="color: rgba(236, 236, 236, 1);"></i>
                    </a>
                    <h4 class="text-xl font-semibold mb-2">Tiktok</h4>
                </div>
                <div class="p-6">
                    <a href="https://wa.me/088232393627" class="w-16 h-16 bg-green-500/20 rounded-2xl flex items-center justify-center mx-auto mb-4 text-center">
                        <i class="fa-brands fa-whatsapp fa-2xl" style="color: rgb(7, 209, 0);"></i>
                    </a>
                    <h4 class="text-xl font-semibold mb-2">WhatsApp</h4>
                </div>
            </div>
        </div>
    </section>

    <!-- Footer -->
    <footer class="bg-black/90 border-t border-white/10 py-12 px-6">
        <div class="max-w-6xl mx-auto text-center">
            <div class="text-2xl font-bold bg-gradient-to-r from-purple-400 to-pink-400 bg-clip-text text-transparent mb-4">
                Muhammad Taufiqurrohman
            </div>
            <p class="text-gray-400 mb-6">
                Z_Froz ©2026
            </p>
            <div class="flex justify-center space-x-6 text-gray-400">
        </div>
    </footer>

    <script>
        // Smooth scrolling untuk navigation links
        document.querySelectorAll('a[href^="#"]').forEach(anchor => {
            anchor.addEventListener('click', function (e) {
                e.preventDefault();
                const target = document.querySelector(this.getAttribute('href'));
                if (target) {
                    target.scrollIntoView({
                        behavior: 'smooth',
                        block: 'start'
                    });
                }
            });
        });
    </script>
</body>
</html>