<?php
// Connexion à la base de données
$conn = new mysqli('localhost', 'root', '', 'gestion_jury');

// Vérifier la connexion
if ($conn->connect_error) {
    die("Connection failed: " . $conn->connect_error);
}

// Récupérer les membres disponibles
$sql_membres_disponibles = "SELECT id, nom FROM Membre WHERE jury_id IS NULL OR president_id IS NULL";
$result_membres_disponibles = $conn->query($sql_membres_disponibles);

if (!$result_membres_disponibles) {
    die("Erreur SQL lors de la récupération des membres disponibles: " . $conn->error);
}

// Ajouter un jury avec ses membres
if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $salle = $_POST['salle'];
    $president_id = $_POST['president_id'];
    $membres = $_POST['membres']; // Tableau des membres sélectionnés

    // Vérifier si le président est déjà président d'un autre jury
    $sql_check_president = "SELECT id FROM Jury WHERE president_id = '$president_id'";
    $result_check_president = $conn->query($sql_check_president);
    if ($result_check_president->num_rows > 0) {
        die("Erreur: Ce membre est déjà président d'un autre jury.");
    }

    // Vérifier si les membres sélectionnés sont déjà dans un autre jury
    foreach ($membres as $membre_id) {
        $sql_check_membre = "SELECT id FROM Jury WHERE id IN (SELECT jury_id FROM jury_membre WHERE membre_id = '$membre_id')";
        $result_check_membre = $conn->query($sql_check_membre);
        if ($result_check_membre->num_rows > 0) {
            die("Erreur: Le membre avec l'ID $membre_id est déjà dans un autre jury.");
        }
    }

    // Insérer le nouveau jury
    $sql_insert_jury = "INSERT INTO Jury (salle, president_id) VALUES ('$salle', '$president_id')";
    if ($conn->query($sql_insert_jury) === TRUE) {
        $jury_id = $conn->insert_id; // Récupérer l'ID du jury ajouté

        // Mettre à jour le membre pour lui assigner ce jury et le rôle de président
        $sql_update_president = "UPDATE Membre SET jury_id='$jury_id', president_id='$president_id' WHERE id='$president_id'";
        $conn->query($sql_update_president);

        // Insérer les membres sélectionnés dans la table jury_membre
        foreach ($membres as $membre_id) {
            $sql_insert_jury_membre = "INSERT INTO jury_membre (jury_id, membre_id) VALUES ('$jury_id', '$membre_id')";
            $conn->query($sql_insert_jury_membre);
        }

        echo "Nouveau jury ajouté avec succès";
    } else {
        echo "Erreur: " . $sql_insert_jury . "<br>" . $conn->error;
    }
}

// Supprimer un jury
if (isset($_GET['delete'])) {
    $id = $_GET['delete'];

    // Supprimer les liens dans la table sujet_jury
    $sql_delete_sujet_jury = "DELETE FROM sujet_jury WHERE jury_id=$id";
    if ($conn->query($sql_delete_sujet_jury) === TRUE) {
        // Supprimer les liens dans la table jury_membre (si ce n'est pas déjà fait)
        $sql_delete_jury_membre = "DELETE FROM jury_membre WHERE jury_id=$id";
        $conn->query($sql_delete_jury_membre);

        // Supprimer le jury dans la table Jury
        $sql_delete_jury = "DELETE FROM Jury WHERE id=$id";
        if ($conn->query($sql_delete_jury) === TRUE) {
            echo "Jury supprimé avec succès";
        } else {
            echo "Erreur lors de la suppression du jury: " . $conn->error;
        }
    } else {
        echo "Erreur lors de la suppression des sujets liés au jury: " . $conn->error;
    }
}

// Récupérer les jurys
$sql_jurys = "SELECT * FROM Jury";
$result_jurys = $conn->query($sql_jurys);

if (!$result_jurys) {
    die("Erreur SQL lors de la récupération des jurys: " . $conn->error);
}
?>

<!DOCTYPE html>
<html lang="fr">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Jurys</title>
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
    <h1 class="text-3xl font-bold mb-4">Jurys</h1>

    <!-- Main Content -->
    <div class="container mx-auto p-4">
        <h1 class="text-3xl font-bold mb-4">Liste des Jurys</h1>
        <table class="min-w-full bg-white shadow-md rounded-lg overflow-hidden">
            <thead>
                <tr>
                    <th class="px-4 py-2 bg-gray-200 text-left text-sm font-semibold text-gray-700">ID</th>
                    <th class="px-4 py-2 bg-gray-200 text-left text-sm font-semibold text-gray-700">Salle</th>
                    <th class="px-4 py-2 bg-gray-200 text-left text-sm font-semibold text-gray-700">Président</th>
                    <th class="px-4 py-2 bg-gray-200 text-left text-sm font-semibold text-gray-700">Actions</th>
                </tr>
            </thead>
            <tbody>
                <?php while ($row = $result_jurys->fetch_assoc()) : ?>
                    <tr>
                        <td class="border-t px-4 py-2"><?php echo $row['id']; ?></td>
                        <td class="border-t px-4 py-2"><?php echo $row['salle']; ?></td>
                        <td class="border-t px-4 py-2">
                            <?php
                            // Récupérer le nom du président
                            $president_id = $row['president_id'];
                            $sql_president = "SELECT nom FROM Membre WHERE id=?";
                            $stmt = $conn->prepare($sql_president);
                            $stmt->bind_param("i", $president_id); // i pour integer, s'il s'agit d'une chaîne, utiliser "s"
                            $stmt->execute();
                            $president_result = $stmt->get_result();

                            if ($president_result->num_rows > 0) {
                                $president = $president_result->fetch_assoc();
                                echo $president['nom'];
                            } else {
                                echo "Aucun président sélectionné";
                            }

                            $stmt->close();

                            ?>
                        </td>
                        <td class="border-t px-4 py-2">
                        <td class="border-t px-4 py-2">
                            <a href="voir_membres.php?id=<?php echo $row['id']; ?>" class="text-blue-500 hover:underline flex items-center">
                                Voir membres
                            </a>
                            <a href="modifier_jury.php?id=<?php echo $row['id']; ?>" class="text-blue-500 hover:underline flex items-center">
                                <svg class="w-4 h-4 mr-1" xmlns="http://www.w3.org/2000/svg" viewBox="0 0 20 20" fill="currentColor">
                                    <path fill-rule="evenodd" d="M13.293 4.293a1 1 0 011.414 0l2 2a1 1 0 010 1.414l-9 9a1 1 0 01-.32.223l-3 1a1 1 0 01-1.192-1.192l1-3a1 1 0 01.223-.32l9-9z" clip-rule="evenodd" />
                                    <path fill-rule="evenodd" d="M6 13l-2 4 4-2 9-9-2-2-9 9z" clip-rule="evenodd" />
                                </svg>
                                Modifier
                            </a>
                            <a href="?delete=<?php echo $row['id']; ?>" onclick="return confirm('Êtes-vous sûr de vouloir supprimer ce jury?')" class="text-red-500 hover:underline flex items-center ml-2">
                                <svg class="w-4 h-4 mr-1" xmlns="http://www.w3.org/2000/svg" viewBox="0 0 20 20" fill="currentColor">
                                    <path fill-rule="evenodd" d="M5.293 6.293a1 1 0 011.414 0L10 9.586l3.293-3.293a1 1 0 111.414 1.414L11.414 11l3.293 3.293a1 1 0 01-1.414 1.414L10 12.414l-3.293 3.293a1 1 0 01-1.414-1.414L8.586 11 5.293 7.707a1 1 0 010-1.414z" clip-rule="evenodd" />
                                </svg>
                                Supprimer
                            </a>
                        </td>
                    </tr>
                <?php endwhile; ?>
            </tbody>
        </table>

        <!-- Ajouter un nouveau jury -->
        <div class="mt-6">
            <h2 class="text-xl font-bold mb-2">Ajouter un Nouveau Jury</h2>
            <form action="<?php echo $_SERVER["PHP_SELF"]; ?>" method="POST">
                <div class="flex flex-col sm:flex-row gap-4">
                    <div class="flex flex-col mb-4 sm:w-1/2">
                        <label for="salle" class="mb-2 font-bold text-gray-700">Salle</label>
                        <input type="text" name="salle" id="salle" class="px-3 py-2 border rounded-md w-full" required>
                    </div>
                    <div class="flex flex-col mb-4 sm:w-1/2">
                        <label for="president_id" class="mb-2 font-bold text-gray-700">Président</label>
                        <select name="president_id" id="president_id" class="px-3 py-2 border rounded-md w-full" required>
                            <option value="" disabled selected>Sélectionnez un président</option>
                            <?php while ($row = $result_membres_disponibles->fetch_assoc()) : ?>
                                <option value="<?php echo $row['id']; ?>"><?php echo $row['nom']; ?></option>
                            <?php endwhile; ?>
                        </select>
                    </div>
                </div>
                <div class="flex flex-col mb-4">
                    <label for="membres" class="mb-2 font-bold text-gray-700">Membres</label>
                    <select name="membres[]" id="membres" class="px-3 py-2 border rounded-md w-full" multiple required>
                        <?php mysqli_data_seek($result_membres_disponibles, 0); // Réinitialiser le curseur pour réutiliser le résultat 
                        ?>
                        <?php while ($row = $result_membres_disponibles->fetch_assoc()) : ?>
                            <option value="<?php echo $row['id']; ?>"><?php echo $row['nom']; ?></option>
                        <?php endwhile; ?>
                    </select>
                </div>
                <button type="submit" class="px-4 py-2 bg-blue-500 text-white rounded-md hover:bg-blue-600">Ajouter Jury</button>
            </form>
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

<?php
// Fermer la connexion à la base de données
$conn->close();
?>