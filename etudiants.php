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
<html>

<head>
    <title>Étudiants</title>
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
                            <form method="post" action="etudiants.php" class="flex flex-col items-center">
                                <input class="w-full px-8 py-4 mt-4 rounded-lg font-medium bg-gray-100 border border-gray-200 placeholder-gray-500 text-sm focus:outline-none focus:border-gray-400 focus:bg-white" type="text" name="nom" placeholder="Nom" required />
                                <input class="w-full px-8 py-4 mt-4 rounded-lg font-medium bg-gray-100 border border-gray-200 placeholder-gray-500 text-sm focus:outline-none focus:border-gray-400 focus:bg-white" type="text" name="prenom" placeholder="Prénom" required />
                                <select name="sujet_id" class="w-full px-8 py-4 mt-4 rounded-lg font-medium bg-gray-100 border border-gray-200 placeholder-gray-500 text-sm focus:outline-none focus:border-gray-400 focus:bg-white" required>
                                    <option value="" disabled selected>Choisir un sujet</option>
                                    <?php while ($row = $sujets_result->fetch_assoc()) : ?>
                                        <option value="<?php echo $row['id']; ?>"><?php echo $row['titre']; ?></option>
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
</body>

</html>

<?php $conn->close(); ?>