<?php
session_start();
require_once 'Randonnee.php';

if (!isset($_SESSION['user_id']) || $_SESSION['role'] !== 'organisateur') {
    header('Location: login.php');
    exit();
}

$randonnee = new Randonnee();

if (!isset($_GET['id'])) {
    header('Location: organisateur_dashboard.php');
    exit();
}

$id = $_GET['id'];
$rando = $randonnee->getById($id);

if (!$rando) {
    echo "Randonnée introuvable.";
    exit();
}

$error = "";

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $location = $_POST['location'];
    $distance = $_POST['distance'];
    $difficulte = $_POST['difficulte'];
    $image = $_POST['image']; // On récupère directement le lien de l'image

    try {
        // Mise à jour de la randonnée
        $randonnee->update($id, $location, $distance, $difficulte, $image);
        header('Location: organisateur_dashboard.php');
        exit();
    } catch (Exception $e) {
        $error = $e->getMessage();
    }
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Modifier une Randonnée</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0-alpha1/dist/css/bootstrap.min.css" rel="stylesheet">
    <style>
        body {
            font-family: 'Arial', sans-serif;
            background: #f5f5f5;
            margin: 0;
            display: flex;
            justify-content: center;
            align-items: center;
            height: 100vh;
        }
        .form-container {
            background: #fff;
            padding: 30px;
            border-radius: 15px;
            box-shadow: 0px 4px 8px rgba(0, 0, 0, 0.1);
            width: 400px;
        }
        .form-control:focus {
            border-color: #84fab0;
            box-shadow: 0 0 10px #84fab0;
        }
        .btn-primary {
            background: #84fab0;
            border: none;
            transition: background 0.3s ease-in-out, transform 0.2s ease-in-out;
        }
        .btn-primary:hover {
            background: #8fd3f4;
            transform: scale(1.05);
        }
    </style>
</head>
<body>
    <div class="form-container">
        <h3 class="text-center mb-4">Modifier une Randonnée</h3>
        <?php if (!empty($error)): ?>
            <div class="alert alert-danger"><?php echo htmlspecialchars($error); ?></div>
        <?php endif; ?>
        <form method="POST">
            <div class="mb-3">
                <label for="location" class="form-label">Lieu</label>
                <input type="text" name="location" id="location" class="form-control" value="<?php echo htmlspecialchars($rando['location']); ?>" required>
            </div>
            <div class="mb-3">
                <label for="distance" class="form-label">Distance (en km)</label>
                <input type="number" step="0.1" name="distance" id="distance" class="form-control" value="<?php echo htmlspecialchars($rando['distance']); ?>" required>
            </div>
            <div class="mb-3">
                <label for="difficulte" class="form-label">Difficulté</label>
                <select name="difficulte" id="difficulte" class="form-select" required>
                    <option value="facile" <?php echo $rando['difficulte'] === 'facile' ? 'selected' : ''; ?>>Facile</option>
                    <option value="moyen" <?php echo $rando['difficulte'] === 'moyen' ? 'selected' : ''; ?>>Moyen</option>
                    <option value="difficile" <?php echo $rando['difficulte'] === 'difficile' ? 'selected' : ''; ?>>Difficile</option>
                </select>
            </div>
            <div class="mb-3">
                <label for="image" class="form-label">Lien de l'image</label>
                <input type="url" name="image" id="image" class="form-control" value="<?php echo htmlspecialchars($rando['image']); ?>" required>
            </div>
            <button type="submit" class="btn btn-primary w-100">Modifier</button>
        </form>
    </div>
</body>
</html>
