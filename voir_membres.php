<?php
// Connexion à la base de données
$conn = new mysqli('localhost', 'root', '', 'gestion_jury');

// Vérifier la connexion
if ($conn->connect_error) {
    die("Connection failed: " . $conn->connect_error);
}

// Vérifier si l'ID du jury est passé en paramètre GET
if (!isset($_GET['id'])) {
    die("ID du jury non spécifié.");
}

// Récupérer l'ID du jury à afficher les membres
$jury_id = $_GET['id'];

// Préparer la requête pour récupérer les membres associés à ce jury
$sql_membres = "SELECT M.nom
                FROM membre AS M 
                INNER JOIN jury_membre AS JM ON M.id = JM.membre_id 
                WHERE JM.jury_id = ?";

// Préparer la requête SQL
$stmt_membres = $conn->prepare($sql_membres);
$stmt_membres->bind_param("i", $jury_id); // Binder le paramètre de l'ID du jury

// Exécuter la requête
$stmt_membres->execute();
$result_membres = $stmt_membres->get_result();

// Récupérer le président du jury
$sql_president = "SELECT M.nom
                 FROM membre AS M 
                 INNER JOIN jury AS J ON M.id = J.president_id 
                 WHERE J.id = ?";
$stmt_president = $conn->prepare($sql_president);
$stmt_president->bind_param("i", $jury_id);
$stmt_president->execute();
$result_president = $stmt_president->get_result();

// Vérifier s'il y a des résultats pour le président
if ($result_president->num_rows > 0) {
    $president = $result_president->fetch_assoc();
} else {
    $president['nom'] = "Aucun président sélectionné";
}

?>

<!DOCTYPE html>
<html lang="fr">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Membres du Jury <?php echo htmlspecialchars($jury_id); ?></title>
    <link rel="stylesheet" href="./src/output.css">
</head>

<body class="bg-gray-100 text-gray-900">

    <!-- Navigation -->
    <nav class="font-sans flex flex-col text-center content-center sm:flex-row sm:text-left sm:justify-between py-2 px-6 bg-white shadow sm:items-baseline w-full">
        <div class="mb-2 sm:mb-0 flex flex-row">
            <div class="h-10 w-10 self-center mr-2">
                <a href="./index.php"> <img class="h-10 w-10 self-center" src="./img/logo.webp" /></a>
            </div>
            <div>
                <a href="./index.php" class="text-2xl no-underline bg-gradient-to-r from-blue-700 to-sky-400 bg-clip-text text-transparent hover:text-blue-dark font-sans font-bold">SenJury</a><br>
            </div>
        </div>
        <div class="sm:mb-0 self-center">
            <p class="text-xl text-gray-600">Bonjour !</p>
        </div>
    </nav>

    <!-- Main Content -->
    <div class="container mx-auto p-4">
        <h1 class="text-3xl font-bold mb-4">Membres du Jury <?php echo htmlspecialchars($jury_id); ?></h1>
        <div class="bg-white shadow-md rounded-lg overflow-hidden p-4">
            <h2 class="text-xl font-bold mt-4 mb-2">Membres du Jury</h2>
            <?php if ($result_membres->num_rows > 0) : ?>
                <ul class="list-disc pl-4">
                    <?php while ($row = $result_membres->fetch_assoc()) : ?>
                        <li><?php echo htmlspecialchars($row['nom']); ?></li>
                    <?php endwhile; ?>
                </ul>
            <?php else : ?>
                <p>Aucun membre trouvé pour ce jury.</p>
            <?php endif; ?>
        </div>

        <div class="bg-white shadow-md rounded-lg overflow-hidden p-4 mt-4">
            <h2 class="text-xl font-bold">Président du Jury</h2>
            <p><?php echo htmlspecialchars($president['nom']); ?></p>
        </div>

        <div class="mt-4">
            <a href="javascript:history.back()" class="bg-blue-500 hover:bg-blue-700 text-white font-bold py-2 px-4 rounded">
                Retour
            </a>
        </div>
    </div>

</body>

</html>

<?php
// Fermer le statement et la connexion à la base de données
$stmt_membres->close();
$stmt_president->close();
$conn->close();
?>