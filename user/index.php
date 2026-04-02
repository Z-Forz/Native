<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Document</title>
    <script src="https://cdn.jsdelivr.net/npm/@tailwindcss/browser@4"></script>
    <link href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/7.0.1/css/all.min.css" rel="stylesheet">
</head>
<body>
    <nav class="bg-white shadow">
        <div class="max-w-6xl mx-auto px-4 py-4 flex justify-between">
            <h1 class="font-bold text-xl">MyPortfolio</h1>

            <div class="space-x-4">
                <a href=".home" class="hover:text-blue-500">Home</a>
                <a href=".about" class="hover:text-blue-500">About</a>
                <a href=".projects" class="hover:text-blue-500">Projects</a>
                <a href=".contact" class="hover:text-blue-500">Contact</a>
            </div>
        </div>
    </nav>
    <!-- Home Section -->
    <div class="font-serif bg-purple-600 text-white">
        <section class="" id="home">
            <div class="grid h-auto grid-cols-2 place-items-center gap-4 px-40 py-20" >
                <div class="text-start">
                    <div class="">
                        <h1 class="text-2xl font-semibold indent-2">Muhammad Taufiqurrohman</h1>
                        <p class="text-md text-gray-400 font-bold text-6xl w-150">Desain Grafis<br> dan Web Developer</p>
                        <p class="text-xl indent-8 p-4">
                            Desain grafis adalah seni komunikasi visual menggunakan elemen gambar, teks, dan tata letak untuk menyampaikan pesan. 
                            Sementara itu, Web Developer adalah profesi teknis yang membangun, memprogram, dan memelihara fungsionalitas situs web. 
                            Keduanya berkolaborasi: Desainer membuat konsep visual, dan Developer mewujudkannya menjadi situs fungsional.
                        </p>
                    </div>
                    <div class="pl-4">
                        <a href="" class="bg-blue-500 hover:bg-blue-700 text-white font-bold py-2 px-4 rounded">Download CV</a>
                    </div>
                </div>  
                <div class="h-100 w-100 bg-gray-300 rounded-full">
                    <input class="" type="image" src="" alt="">
                </div>
            </div>
        </section>

        <!-- About Section -->
        <section class="" id="about">
            <div class="grid max-w-6xl mx-auto px-4 py-20 place-items-center">
                <h2 class="text-4xl font-bold mb-4">About Me</h2>
                <p class="text-lg text-center">
                    Saya adalah seorang desainer grafis dan web developer dengan pengalaman dalam menciptakan desain visual yang menarik dan membangun situs web yang fungsional. 
                    Saya memiliki keahlian dalam menggunakan berbagai alat desain seperti Adobe Illustrator dan Figma, serta kemampuan pemrograman dengan HTML, CSS, JavaScript, dan php.
                </p>
            </div>
        </section>

        <!-- Projects Section -->
        <section class="" id="projects">
            <div class="grid max-w-6xl mx-auto px-4 py-4 place-items-center">
                <h2 class="text-4xl font-bold mb-4">My Projects</h2>
                <p class="text-lg text-center">
                    Berikut adalah beberapa proyek yang telah saya kerjakan:
                </p>
            </div>
            <div class="flex items-center justify-center">
                <!-- Project Items -->
                <div class="max-w-sm bg-white rounded-2xl shadow-lg overflow-hidden hover:shadow-xl transition">
                <!-- Foto -->
                    <img  class="w-full h-48 object-cover p-4" src="profile.jpeg"  alt="" >
                    <!-- Content -->
                    <div class="p-5">
                        <h2 class="text-xl font-bold text-gray-800 mb-2"> Judul Foto </h2>
                        <p class="text-gray-600 text-sm"> Ini adalah deskripsi singkat dari foto. Kamu bisa menambahkan penjelasan sesuai kebutuhan di sini.</p>
                    </div>
                </div>
            </div>
        </section>

         <!-- Contact Section -->
         <section class="" id="contact">
            <div class="grid max-w-6xl mx-auto px-4 py-20 place-items-center">
                <h2 class="text-4xl font-bold mb-4">Contact Me</h2>
                <p class="text-lg text-center">
                    Jika Anda tertarik untuk bekerja sama atau memiliki pertanyaan, jangan ragu untuk menghubungi saya melalui email di <a href="mailto:example@email.com" class="text-blue-500 hover:underline">example@email.com</a> atau melalui media sosial saya di Instagram, TikTok, dan YouTube.
                </p>
            </div>
        </section>

        <!-- Footer -->
        <footer class="bg-gray-100">
            <div class="max-w-6xl mx-auto px-4 py-6 justify-around grid grid-flow-col justify-items-center place-items-center">

                <div class="">
                    <div class="rounded-xl bg-amber-400 w-10 h-10"></div>
                </div>
                <p class="text-center text-gray-500">Copyright 2026&copy; Z_Forz</p>
                <div class="">
                    <i class="fa-brands fa-instagram fa-2xl" style="color: rgb(0, 0, 0);"></i>
                    <i class="fa-brands fa-tiktok fa-2xl" style="color: rgb(0, 0, 0);"></i>
                    <i class="fa-brands fa-youtube fa-2xl" style="color: rgb(0, 0, 0);"></i>
                </div>
            </div>
        </footer>
    </div>
</body>
</html>