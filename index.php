<!DOCTYPE html>
<html lang="fr">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <link rel="icon" type="image/x-icon" href="./img/SJ.png">
    <link rel="stylesheet" href="./src/output.css">
    <!-- <script src="https://cdn.tailwindcss.com"></script> -->

    <title>Gestion des Jurys de Soutenance</title>
    <!-- Feather Icons -->
    <script src="https://unpkg.com/feather-icons"></script>
</head>

<body>
    <nav class="bg-white shadow">
        <div class="max-w-7xl mx-auto px-2 sm:px-6 lg:px-8">
            <div class="relative flex items-center justify-between h-16">
                <div class="absolute inset-y-0 left-0 flex items-center sm:hidden">
                    <!-- Mobile menu button-->
                    <button id="mobile-menu-button" type="button" class="inline-flex items-center justify-center p-2 rounded-md text-gray-400 hover:text-white hover:bg-gray-700 focus:outline-none focus:ring-2 focus:ring-inset focus:ring-white" aria-controls="mobile-menu" aria-expanded="false">
                        <span class="sr-only">Open main menu</span>
                        <svg id="menu-open-icon" class="block h-6 w-6" xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke="currentColor" aria-hidden="true">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 6h16M4 12h16m-7 6h7" />
                        </svg>
                        <svg id="menu-close-icon" class="hidden h-6 w-6" xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke="currentColor" aria-hidden="true">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12" />
                        </svg>
                    </button>
                </div>
                <div class="flex-1 flex items-center justify-center sm:items-stretch sm:justify-start">
                    <div class="flex-shrink-0">
                        <a href="./index.php">
                            <img class="h-8 w-auto" src="./img/SJ.png" alt="Logo">
                        </a>
                    </div>
                    <div class="hidden sm:block sm:ml-6">
                        <div class="flex space-x-4">
                            <a href="index.php" class="text-gray-600 hover:underline px-3 py-2 rounded-md text-sm font-medium">Accueil</a>
                            <a href="membres.php" class="text-gray-600 hover:underline px-3 py-2 rounded-md text-sm font-medium">Membres</a>
                            <a href="jurys.php" class="text-gray-600 hover:underline px-3 py-2 rounded-md text-sm font-medium">Jurys</a>
                            <a href="sujets.php" class="text-gray-600 hover:underline px-3 py-2 rounded-md text-sm font-medium">Sujets</a>
                            <a href="etudiants.php" class="text-gray-600 hover:underline px-3 py-2 rounded-md text-sm font-medium">Étudiants</a>
                        </div>
                    </div>
                </div>
                <div class="absolute inset-y-0 right-0 flex items-center pr-2 sm:static sm:inset-auto sm:ml-6 sm:pr-0">
                    <span class="text-gray-800 text-lg font-semibold">Bienvenue</span>
                </div>
            </div>
        </div>

        <!-- Mobile menu, show/hide based on menu state. -->
        <div class="sm:hidden hidden" id="mobile-menu">
            <div class="px-2 pt-2 pb-3 space-y-1">
                <a href="index.php" class="text-gray-600 hover:underline block px-3 py-2 rounded-md text-base font-medium">Accueil</a>
                <a href="membres.php" class="text-gray-600 hover:underline block px-3 py-2 rounded-md text-base font-medium">Membres</a>
                <a href="jurys.php" class="text-gray-600 hover:underline block px-3 py-2 rounded-md text-base font-medium">Jurys</a>
                <a href="sujets.php" class="text-gray-600 hover:underline block px-3 py-2 rounded-md text-base font-medium">Sujets</a>
                <a href="etudiants.php" class="text-gray-600 hover:underline block px-3 py-2 rounded-md text-base font-medium">Étudiants</a>
            </div>
        </div>
    </nav>

    <section>
        <div class="bg-gray-100 sm:grid grid-cols-5 grid-rows-2 px-4 py-6 min-h-full lg:min-h-screen space-y-6 sm:space-y-0 sm:gap-4">
            <div class="h-96 col-span-4 bg-gradient-to-tr from-indigo-800 to-indigo-500 rounded-md flex items-center">
                <div class="ml-20 w-80">
                    <h2 class="text-white text-4xl">Bienvenue à l'application de gestion des jurys de soutenance</h2>
                    <p class="text-indigo-100 mt-4 capitalize font-thin tracking-wider leading-7">Vous pouvez gérer les jurys, membres, sujets et étudiants en utilisant les liens ci-dessous.</p>
                </div>
            </div>
            <div class="h-96 col-span-1">
                <div class="bg-white py-3 px-4 rounded-lg flex justify-around items-center">
                    <input type="text" placeholder="Rechercher" class="bg-gray-100 rounded-md outline-none pl-2 ring-indigo-700 w-full mr-2 p-2">
                    <span><svg xmlns="http://www.w3.org/2000/svg" class="h-6 w-6" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M21 21l-6-6m2-5a7 7 0 11-14 0 7 7 0 0114 0z" />
                        </svg></span>
                </div>

                <div class="flex flex-wrap justify-center mt-3">
                    <div class="relative flex flex-col bg-clip-border rounded-xl bg-white text-gray-700 shadow-md ml-1 mt-4 mb-4 mr-1 p-4 w-80">
                        <div class="bg-clip-border mx-4 rounded-xl overflow-hidden bg-gradient-to-tr from-pink-600 to-pink-400 text-white shadow-pink-500/40 shadow-lg absolute -mt-8 grid h-16 w-16 place-items-center">
                            <i data-feather="smile" class="w-6 h-6 text-white"></i>
                        </div>
                        <div class="p-4 pt-10">
                            <h4 class="block antialiased tracking-normal font-sans text-xl font-semibold leading-snug text-blue-gray-900 mb-2">À propos de l'application</h4>
                            <p class="block antialiased font-sans text-sm leading-normal font-normal text-blue-gray-600 mb-4">
                                Cette application vous permet de gérer les jurys de soutenance, les membres, les sujets et les étudiants de manière efficace et intuitive.
                            </p>
                        </div>
                    </div>
                </div>
            </div>
        </div>

        <!-- Liens vers les sections de gestion -->
        <div class="flex flex-wrap justify-center mt-2">
            <a href="jurys.php" class="relative flex flex-col bg-clip-border rounded-xl bg-white text-gray-700 shadow-md m-4 p-4 w-80">
                <div class="bg-clip-border mx-4 rounded-xl overflow-hidden bg-gradient-to-tr from-blue-600 to-blue-400 text-white shadow-blue-500/40 shadow-lg absolute -mt-8 grid h-16 w-16 place-items-center">
                    <i data-feather="users" class="w-6 h-6 text-white"></i>
                </div>
                <div class="p-4 pt-10">
                    <h4 class="block antialiased tracking-normal font-sans text-xl font-semibold leading-snug text-blue-gray-900 mb-2">Jurys</h4>
                    <p class="block antialiased font-sans text-sm leading-normal font-normal text-blue-gray-600 mb-4">
                        Gérer les jurys de soutenance.
                    </p>
                </div>
            </a>

            <a href="membres.php" class="relative flex flex-col bg-clip-border rounded-xl bg-white text-gray-700 shadow-md m-4 p-4 w-80">
                <div class="bg-clip-border mx-4 rounded-xl overflow-hidden bg-gradient-to-tr from-green-600 to-green-400 text-white shadow-green-500/40 shadow-lg absolute -mt-8 grid h-16 w-16 place-items-center">
                    <i data-feather="user" class="w-6 h-6 text-white"></i>
                </div>
                <div class="p-4 pt-10">
                    <h4 class="block antialiased tracking-normal font-sans text-xl font-semibold leading-snug text-blue-gray-900 mb-2">Membres</h4>
                    <p class="block antialiased font-sans text-sm leading-normal font-normal text-blue-gray-600 mb-4">
                        Gérer les membres des jurys.
                    </p>
                </div>
            </a>

            <a href="sujets.php" class="relative flex flex-col bg-clip-border rounded-xl bg-white text-gray-700 shadow-md m-4 p-4 w-80">
                <div class="bg-clip-border mx-4 rounded-xl overflow-hidden bg-gradient-to-tr from-red-600 to-red-400 text-white shadow-red-500/40 shadow-lg absolute -mt-8 grid h-16 w-16 place-items-center">
                    <i data-feather="file-text" class="w-6 h-6 text-white"></i>
                </div>
                <div class="p-4 pt-10">
                    <h4 class="block antialiased tracking-normal font-sans text-xl font-semibold leading-snug text-blue-gray-900 mb-2">Sujets</h4>
                    <p class="block antialiased font-sans text-sm leading-normal font-normal text-blue-gray-600 mb-4">
                        Gérer les sujets de soutenance.
                    </p>
                </div>
            </a>

            <a href="etudiants.php" class="relative flex flex-col bg-clip-border rounded-xl bg-white text-gray-700 shadow-md m-4 p-4 w-80">
                <div class="bg-clip-border mx-4 rounded-xl overflow-hidden bg-gradient-to-tr from-yellow-600 to-yellow-400 text-white shadow-yellow-500/40 shadow-lg absolute -mt-8 grid h-16 w-16 place-items-center">
                    <i data-feather="user-check" class="w-6 h-6 text-white"></i>
                </div>
                <div class="p-4 pt-10">
                    <h4 class="block antialiased tracking-normal font-sans text-xl font-semibold leading-snug text-blue-gray-900 mb-2">Étudiants</h4>
                    <p class="block antialiased font-sans text-sm leading-normal font-normal text-blue-gray-600 mb-4">
                        Gérer les étudiants de soutenance.
                    </p>
                </div>
            </a>
        </div>
    </section>

    <script>
        // Initialiser les icônes Feather
        feather.replace();

        document.addEventListener('DOMContentLoaded', function() {
            const menuButton = document.getElementById('mobile-menu-button');
            const menu = document.getElementById('mobile-menu');
            const menuOpenIcon = document.getElementById('menu-open-icon');
            const menuCloseIcon = document.getElementById('menu-close-icon');

            menuButton.addEventListener('click', function() {
                const isMenuOpen = menu.classList.contains('hidden');
                menu.classList.toggle('hidden', !isMenuOpen);
                menuOpenIcon.classList.toggle('hidden', !isMenuOpen);
                menuCloseIcon.classList.toggle('hidden', isMenuOpen);
            });
        });
    </script>
</body>

</html>