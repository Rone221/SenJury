<?php
// Connexion à la base de données
$conn = new mysqli('localhost', 'root', '', 'gestion_jury');

// Vérifier la connexion
if ($conn->connect_error) {
    die("Connection failed: " . $conn->connect_error);
}

// Récupérer la liste des sujets disponibles
$sujets_sql = "SELECT id, titre FROM Sujet";
$sujets_result = $conn->query($sujets_sql);

// Ajouter un étudiant
if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $nom = $_POST['nom'];
    $prenom = $_POST['prenom'];
    $sujet_id = $_POST['sujet_id'];
    $sql = "INSERT INTO Etudiant (nom, prenom) VALUES ('$nom', '$prenom')";
    if ($conn->query($sql) === TRUE) {
        $etudiant_id = $conn->insert_id;
        $sql = "INSERT INTO Sujet_Etudiant (sujet_id, etudiant_id) VALUES ('$sujet_id', '$etudiant_id')";
        $conn->query($sql);
        echo "Nouvel étudiant ajouté avec succès";
    } else {
        echo "Erreur: " . $sql . "<br>" . $conn->error;
    }
}

// Supprimer un étudiant
if (isset($_GET['delete'])) {
    $id = $_GET['delete'];
    $sql = "DELETE FROM Etudiant WHERE id=$id";
    if ($conn->query($sql) === TRUE) {
        echo "Étudiant supprimé avec succès";
    } else {
        echo "Erreur: " . $sql . "<br>" . $conn->error;
    }
}

// Récupérer les étudiants avec leurs sujets et jurys
$sql = "SELECT Etudiant.id, Etudiant.nom, Etudiant.prenom, Sujet.titre AS sujet, Jury.salle AS jury
        FROM Etudiant
        LEFT JOIN Sujet_Etudiant ON Etudiant.id = Sujet_Etudiant.etudiant_id
        LEFT JOIN Sujet ON Sujet_Etudiant.sujet_id = Sujet.id
        LEFT JOIN Sujet_Jury ON Sujet.id = Sujet_Jury.sujet_id
        LEFT JOIN Jury ON Sujet_Jury.jury_id = Jury.id";
$result = $conn->query($sql);

if (!$result) {
    die("Erreur SQL: " . $conn->error);
}
?>

<!DOCTYPE html>
<html lang="fr">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Étudiants</title>
    <link rel="stylesheet" href="./src/output.css">
</head>

<body class="bg-gray-100 text-gray-900">

    <!-- Navigation -->
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

    <h1 class="text-3xl font-bold mb-4">Étudiant</h1>

    <!-- Main Content -->
    <div class="container mx-auto p-4">
        <div class="bg-white shadow-md rounded-lg overflow-hidden p-6">
            <h1 class="text-3xl font-bold mb-4">Ajouter un Étudiant</h1>
            <form method="post" action="etudiants.php" class="flex flex-col space-y-4">
                <input class="w-full px-8 py-4 rounded-lg font-medium bg-gray-100 border border-gray-200 placeholder-gray-500 text-sm focus:outline-none focus:border-gray-400 focus:bg-white" type="text" name="nom" placeholder="Nom" required />
                <input class="w-full px-8 py-4 rounded-lg font-medium bg-gray-100 border border-gray-200 placeholder-gray-500 text-sm focus:outline-none focus:border-gray-400 focus:bg-white" type="text" name="prenom" placeholder="Prénom" required />
                <select name="sujet_id" class="w-full px-8 py-4 rounded-lg font-medium bg-gray-100 border border-gray-200 placeholder-gray-500 text-sm focus:outline-none focus:border-gray-400 focus:bg-white" required>
                    <option value="" disabled selected>Choisir un sujet</option>
                    <?php while ($row = $sujets_result->fetch_assoc()) : ?>
                        <option value="<?php echo $row['id']; ?>"><?php echo $row['titre']; ?></option>
                    <?php endwhile; ?>
                </select>
                <button class="w-full py-4 bg-blue-500 text-white font-bold rounded-lg hover:bg-blue-700 transition duration-300">Ajouter</button>
            </form>
        </div>

        <div class="mt-8 bg-white shadow-md rounded-lg overflow-hidden p-6">
            <h1 class="text-3xl font-bold mb-4">Liste des Étudiants</h1>
            <table class="min-w-full bg-white shadow-md rounded-lg overflow-hidden">
                <thead>
                    <tr>
                        <th class="px-4 py-2 bg-gray-200 text-left text-sm font-semibold text-gray-700">ID</th>
                        <th class="px-4 py-2 bg-gray-200 text-left text-sm font-semibold text-gray-700">Nom</th>
                        <th class="px-4 py-2 bg-gray-200 text-left text-sm font-semibold text-gray-700">Prénom</th>
                        <th class="px-4 py-2 bg-gray-200 text-left text-sm font-semibold text-gray-700">Sujet</th>
                        <th class="px-4 py-2 bg-gray-200 text-left text-sm font-semibold text-gray-700">Jury</th>
                        <th class="px-4 py-2 bg-gray-200 text-left text-sm font-semibold text-gray-700">Actions</th>
                    </tr>
                </thead>
                <tbody>
                    <?php while ($row = $result->fetch_assoc()) : ?>
                        <tr>
                            <td class="border-t px-4 py-2"><?php echo $row['id']; ?></td>
                            <td class="border-t px-4 py-2"><?php echo $row['nom']; ?></td>
                            <td class="border-t px-4 py-2"><?php echo $row['prenom']; ?></td>
                            <td class="border-t px-4 py-2"><?php echo $row['sujet']; ?></td>
                            <td class="border-t px-4 py-2"><?php echo $row['jury']; ?></td>
                            <td class="border-t px-4 py-2">
                                <a href="modifier_etudiant.php?id=<?php echo $row['id']; ?>" class="text-blue-500 hover:underline">Modifier</a>
                                <a href="etudiants.php?delete=<?php echo $row['id']; ?>" class="text-red-500 hover:underline">Supprimer</a>
                            </td>
                        </tr>
                    <?php endwhile; ?>
                </tbody>
            </table>
        </div>
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