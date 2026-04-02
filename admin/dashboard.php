<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Fixed Sidebar Tailwind</title>
    <script src="https://cdn.tailwindcss.com"></script>
</head>
<body class="flex h-screen overflow-hidden">

    <!-- SIDEBAR (Fixed) -->
    <!-- h-screen: tinggi penuh layar, overflow-y-auto: scroll jika menu panjang -->
    <aside class="w-64 h-screen bg-gray-800 text-white p-5 overflow-y-auto flex-shrink-0">
        <h1 class="text-2xl font-bold mb-10">Admin Panel</h1>
        <ul>
            <li class="mb-4"><a href="#" class="hover:text-gray-300">Dashboard</a></li>
            <li class="mb-4"><a href="#" class="hover:text-gray-300">Users</a></li>
            <li class="mb-4"><a href="#" class="hover:text-gray-300">Settings</a></li>
            <!-- Tambahkan banyak menu di sini untuk mencoba scroll -->
            <li class="mb-4"><a href="#">Menu Panjang 1</a></li>
            <li class="mb-4"><a href="#">Menu Panjang 2</a></li>
            <li class="mb-4"><a href="#">Menu Panjang 3</a></li>
            <li class="mb-4"><a href="#">Menu Panjang 4</a></li>
            <li class="mb-4"><a href="#">Menu Panjang 5</a></li>
        </ul>
    </aside>

    <!-- KONTEN UTAMA (Scrollable) -->
    <!-- flex-1: sisa lebar, h-screen + overflow-y-auto: konten scrollable -->
    <div class="flex-1 h-screen overflow-y-auto bg-gray-100">
        <!-- Header (Opsional, ikut scroll) -->
        <header class="bg-white shadow p-5 sticky top-0 z-10">
            <h2 class="text-xl font-semibold">Selamat Datang</h2>
        </header>

        <!-- Isi Konten -->
        <main class="p-6">
            <h1 class="text-3xl font-bold mb-5">Konten Utama</h1>
            <p>Scroll halaman ini untuk melihat efek fixed sidebar.</p>
            <div class="h-96 bg-white my-5 p-5 rounded-lg">Konten 1</div>
            <div class="h-96 bg-white my-5 p-5 rounded-lg">Konten 2 (Scroll ke sini)</div>
            <div class="h-96 bg-white my-5 p-5 rounded-lg">Konten 3</div>
            <div class="h-96 bg-white my-5 p-5 rounded-lg">Konten 4</div>
        </main>
    </div>


</body>
</html>
