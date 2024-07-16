<?php
// Connexion à la base de données
$conn = new mysqli('localhost', 'root', '', 'gestion_jury');

// Vérifier la connexion
if ($conn->connect_error) {
    die("Connection failed: " . $conn->connect_error);
}

// Ajouter un membre
if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $nom = $_POST['nom'];
    $specialite = $_POST['specialite'];
    $sql = "INSERT INTO Membre (nom, specialite) VALUES ('$nom', '$specialite')";
    if ($conn->query($sql) === TRUE) {
        echo "Nouveau membre ajouté avec succès";
    } else {
        echo "Erreur: " . $sql . "<br>" . $conn->error;
    }
}

// Supprimer un membre
if (isset($_GET['delete'])) {
    $id = $_GET['delete'];
    $sql = "DELETE FROM Membre WHERE id=$id";
    if ($conn->query($sql) === TRUE) {
        echo "Membre supprimé avec succès";
    } else {
        echo "Erreur: " . $sql . "<br>" . $conn->error;
    }
}

// Récupérer les membres
$sql = "SELECT * FROM Membre";
$result = $conn->query($sql);

if (!$result) {
    die("Erreur SQL: " . $conn->error);
}
?>

<!DOCTYPE html>
<html>

<head>
    <title>Membres</title>
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <link rel="stylesheet" type="text/css" href="output.css">
    <link rel="stylesheet" href="./src/output.css">
</head>

<body class="bg-gray-100 text-gray-900 flex flex-col min-h-screen">
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

    <h1 class="text-3xl font-bold mb-4">Membres</h1>


    <div class="flex-grow flex justify-center items-center">
        <div class="max-w-4xl m-0 sm:m-10 bg-white shadow sm:rounded-lg flex justify-center flex-1">
            <div class="lg:w-1/2 xl:w-5/12 p-6 sm:p-12">
                <div class="w-full flex flex-col items-center">
                    <div class="mt-8 flex flex-col items-center">
                        <div class="w-full flex-1">
                            <div class="mx-auto max-w-xs">
                                <form method="post" action="membres.php" class="flex flex-col items-center">
                                    <input class="w-full px-8 py-4 mt-4 rounded-lg font-medium bg-gray-100 border border-gray-200 placeholder-gray-500 text-sm focus:outline-none focus:border-gray-400 focus:bg-white" type="text" name="nom" placeholder="Nom" required />
                                    <input class="w-full px-8 py-4 mt-4 rounded-lg font-medium bg-gray-100 border border-gray-200 placeholder-gray-500 text-sm focus:outline-none focus:border-gray-400 focus:bg-white" type="text" name="specialite" placeholder="Spécialité" required />
                                    <button class="mt-5 tracking-wide font-semibold bg-blue-500 text-white w-full py-4 rounded-lg hover:bg-blue-700 transition-all duration-300 ease-in-out flex items-center justify-center focus:shadow-outline focus:outline-none">
                                        <svg class="w-6 h-6 -ml-2" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round">
                                            <path d="M16 21v-2a4 4 0 00-4-4H5a4 4 0 00-4 4v2" />
                                            <circle cx="8.5" cy="7" r="4" />
                                            <path d="M20 8v6M23 11h-6" />
                                        </svg>
                                        <span class="ml-3">Ajouter</span>
                                    </button>
                                </form>
                                <a href="index.php" class="mt-5 inline-block text-blue-500 hover:underline">Retour à l'accueil</a>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <div class="container mx-auto p-4">
        <h1 class="text-3xl font-bold mb-4">Liste des Membres</h1>
        <table class="min-w-full bg-white shadow-md rounded-lg overflow-hidden">
            <thead>
                <tr>
                    <th class="px-4 py-2 bg-gray-200 text-left text-sm font-semibold text-gray-700">ID</th>
                    <th class="px-4 py-2 bg-gray-200 text-left text-sm font-semibold text-gray-700">Nom</th>
                    <th class="px-4 py-2 bg-gray-200 text-left text-sm font-semibold text-gray-700">Spécialité</th>
                    <th class="px-4 py-2 bg-gray-200 text-left text-sm font-semibold text-gray-700">Actions</th>
                </tr>
            </thead>
            <tbody>
                <?php while ($row = $result->fetch_assoc()) : ?>
                    <tr>
                        <td class="border-t px-4 py-2"><?php echo $row['id']; ?></td>
                        <td class="border-t px-4 py-2"><?php echo $row['nom']; ?></td>
                        <td class="border-t px-4 py-2"><?php echo $row['specialite']; ?></td>
                        <td class="border-t px-4 py-2">
                            <a href="modifier_membre.php?id=<?php echo $row['id']; ?>" class="text-blue-500 hover:underline">Modifier</a>
                            <a href="membres.php?delete=<?php echo $row['id']; ?>" class="text-red-500 hover:underline ml-2">Supprimer</a>
                        </td>
                    </tr>
                <?php endwhile; ?>
            </tbody>
        </table>
    </div>
    <script>
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

<?php $conn->close(); ?>