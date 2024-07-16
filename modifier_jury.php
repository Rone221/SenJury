<?php
// Connexion à la base de données
$conn = new mysqli('localhost', 'root', '', 'gestion_jury');

// Vérifier la connexion
if ($conn->connect_error) {
    die("Connection failed: " . $conn->connect_error);
}

if (isset($_GET['id'])) {
    $id = $_GET['id'];

    // Récupérer les détails du jury
    $sql = "SELECT * FROM Jury WHERE id='$id'";
    $result = $conn->query($sql);
    $jury = $result->fetch_assoc();

    // Récupérer les membres disponibles (qui ne sont pas déjà dans un jury)
    $membres_sql = "SELECT id, nom, specialite FROM Membre WHERE jury_id IS NULL OR jury_id='$id'";
    $membres_result = $conn->query($membres_sql);

    if ($_SERVER["REQUEST_METHOD"] == "POST") {
        $salle = $_POST['salle'];
        $president_id = $_POST['president_id'];
        $sql = "UPDATE Jury SET salle='$salle', president_id='$president_id' WHERE id='$id'";
        if ($conn->query($sql) === TRUE) {
            $sql = "UPDATE Membre SET jury_id=NULL WHERE jury_id='$id'";
            $conn->query($sql);
            $sql = "UPDATE Membre SET jury_id='$id' WHERE id='$president_id'";
            $conn->query($sql);
            header('Location: jurys.php');
        } else {
            echo "Erreur: " . $sql . "<br>" . $conn->error;
        }
    }
} else {
    header('Location: jurys.php');
}
?>

<!DOCTYPE html>
<html>

<head>
    <title>Modifier Jury</title>
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <link rel="stylesheet" type="text/css" href="output.css">
    <link rel="stylesheet" href="./src/output.css">
</head>

<body class="bg-gray-100 text-gray-900 flex justify-center items-center min-h-screen">
    <div class="max-w-4xl m-0 sm:m-10 bg-white shadow sm:rounded-lg flex justify-center flex-1">
        <div class="lg:w-1/2 xl:w-5/12 p-6 sm:p-12">
            <div class="w-full flex flex-col items-center">
                <div class="mt-8 flex flex-col items-center">
                    <div class="w-full flex-1">
                        <div class="mx-auto max-w-xs">
                            <form method="post" action="" class="flex flex-col items-center">
                                <input class="w-full px-8 py-4 mt-4 rounded-lg font-medium bg-gray-100 border border-gray-200 placeholder-gray-500 text-sm focus:outline-none focus:border-gray-400 focus:bg-white" type="text" name="salle" placeholder="Salle" value="<?php echo $jury['salle']; ?>" required />
                                <select name="president_id" class="w-full px-8 py-4 mt-4 rounded-lg font-medium bg-gray-100 border border-gray-200 placeholder-gray-500 text-sm focus:outline-none focus:border-gray-400 focus:bg-white" required>
                                    <option value="" disabled>Choisir un président</option>
                                    <?php while ($row = $membres_result->fetch_assoc()) : ?>
                                        <option value="<?php echo $row['id']; ?>" <?php if ($row['id'] == $jury['president_id']) echo 'selected'; ?>><?php echo $row['nom'] . " - " . $row['specialite']; ?></option>
                                    <?php endwhile; ?>
                                </select>
                                <button class="mt-5 tracking-wide font-semibold bg-blue-500 text-white w-full py-4 rounded-lg hover:bg-blue-700 transition-all duration-300 ease-in-out flex items-center justify-center focus:shadow-outline focus:outline-none">
                                    <svg class="w-6 h-6 -ml-2" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round">
                                        <path d="M16 21v-2a4 4 0 00-4-4H5a4 4 0 00-4 4v2" />
                                        <circle cx="8.5" cy="7" r="4" />
                                        <path d="M20 8v6M23 11h-6" />
                                    </svg>
                                    <span class="ml-3">Mettre à jour</span>
                                </button>
                            </form>
                            <a href="jurys.php" class="mt-5 inline-block text-blue-500 hover:underline">Retour</a>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</body>

</html>

<?php $conn->close(); ?>