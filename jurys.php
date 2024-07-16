<?php
// Connexion à la base de données
$conn = new mysqli('localhost', 'root', '', 'gestion_jury');

// Vérifier la connexion
if ($conn->connect_error) {
    die("Connection failed: " . $conn->connect_error);
}

// Ajouter un jury
if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $salle = $_POST['salle'];
    $president_id = $_POST['president_id'];
    $sql = "INSERT INTO Jury (salle, president_id) VALUES ('$salle', '$president_id')";
    if ($conn->query($sql) === TRUE) {
        $jury_id = $conn->insert_id; // Récupérer l'ID du jury ajouté
        // Mettre à jour le membre pour lui assigner ce jury
        $sql_update_membre = "UPDATE Membre SET jury_id='$jury_id' WHERE id='$president_id'";
        $conn->query($sql_update_membre);
        echo "Nouveau jury ajouté avec succès";
    } else {
        echo "Erreur: " . $sql . "<br>" . $conn->error;
    }
}

// Supprimer un jury
if (isset($_GET['delete'])) {
    $id = $_GET['delete'];
    // Mettre à jour les membres pour supprimer le lien avec ce jury
    $sql_update_membres = "UPDATE Membre SET jury_id=NULL WHERE jury_id=$id";
    $conn->query($sql_update_membres);
    $sql = "DELETE FROM Jury WHERE id=$id";
    if ($conn->query($sql) === TRUE) {
        echo "Jury supprimé avec succès";
    } else {
        echo "Erreur: " . $sql . "<br>" . $conn->error;
    }
}

// Récupérer les jurys
$sql = "SELECT * FROM Jury";
$result = $conn->query($sql);

// Récupérer les membres disponibles (ceux qui n'ont pas encore de jury)
$sql_membres = "SELECT id, nom, specialite FROM Membre WHERE jury_id IS NULL";
$membres_result = $conn->query($sql_membres);

if (!$result || !$membres_result) {
    die("Erreur SQL: " . $conn->error);
}
?>

<!DOCTYPE html>
<html>

<head>
    <title>Jurys</title>
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <link rel="stylesheet" type="text/css" href="output.css">
    <script src="https://cdn.tailwindcss.com"></script>
</head>

<body class="bg-gray-100 text-gray-900 flex justify-center items-center min-h-screen">
    <div class="max-w-4xl m-0 sm:m-10 bg-white shadow sm:rounded-lg flex justify-center flex-1">
        <div class="lg:w-1/2 xl:w-5/12 p-6 sm:p-12">
            <div class="w-full flex flex-col items-center">
                <div class="mt-8 flex flex-col items-center">
                    <div class="w-full flex-1">
                        <div class="mx-auto max-w-xs">
                            <form method="post" action="jurys.php" class="flex flex-col items-center">
                                <input class="w-full px-8 py-4 mt-4 rounded-lg font-medium bg-gray-100 border border-gray-200 placeholder-gray-500 text-sm focus:outline-none focus:border-gray-400 focus:bg-white" type="text" name="salle" placeholder="Salle" required />
                                <select name="president_id" class="w-full px-8 py-4 mt-4 rounded-lg font-medium bg-gray-100 border border-gray-200 placeholder-gray-500 text-sm focus:outline-none focus:border-gray-400 focus:bg-white" required>
                                    <option value="" disabled selected>Choisir un président</option>
                                    <?php while ($row = $membres_result->fetch_assoc()) : ?>
                                        <option value="<?php echo $row['id']; ?>"><?php echo $row['nom'] . " - " . $row['specialite']; ?></option>
                                    <?php endwhile; ?>
                                </select>
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
                <?php while ($row = $result->fetch_assoc()) : ?>
                    <tr>
                        <td class="border-t px-4 py-2"><?php echo $row['id']; ?></td>
                        <td class="border-t px-4 py-2"><?php echo $row['salle']; ?></td>
                        <td class="border-t px-4 py-2"><?php
                                                        // Récupérer le nom du président
                                                        $president_id = $row['president_id'];
                                                        $sql_president = "SELECT nom FROM Membre WHERE id=$president_id";
                                                        $president_result = $conn->query($sql_president);
                                                        $president = $president_result->fetch_assoc();
                                                        echo $president['nom'];
                                                        ?></td>
                        <td class="border-t px-4 py-2">
                            <a href="modifier_jury.php?id=<?php echo $row['id']; ?>" class="text-blue-500 hover:underline">Modifier</a>
                            <a href="jurys.php?delete=<?php echo $row['id']; ?>" class="text-red-500 hover:underline ml-2">Supprimer</a>
                        </td>
                    </tr>
                <?php endwhile; ?>
            </tbody>
        </table>
    </div>
</body>

</html>

<?php $conn->close(); ?>