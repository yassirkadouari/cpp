<?php
session_start();
require_once 'Randonnee.php';
require_once 'Inscription.php';

// Vérification de la session
if (!isset($_SESSION['user_id'])) {
    header('Location: login.php');
    exit();
}

// Vérification de l'ID de la randonnée
if (!isset($_GET['id'])) {
    header('Location: user_dashboard.php');
    exit();
}

$randonnee = new Randonnee();
$details = $randonnee->getById($_GET['id']);

if (!$details) {
    echo "Randonnée introuvable.";
    exit();
}

$inscription = new Inscription();
$success = "";
$error = "";

// Gestion de l'inscription
if (isset($_GET['action']) && $_GET['action'] === 'inscrire') {
    try {
        $inscription->registerUserToRando($_SESSION['user_id'], $details['id']);
        $randonnee->incrementInscrits($details['id']); // Mise à jour du nombre d'inscrits
        $success = "Vous êtes inscrit avec succès à cette randonnée.";
        $details = $randonnee->getById($details['id']); // Actualisation des détails
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
    <title>Détails de la Randonnée</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0-alpha1/dist/css/bootstrap.min.css" rel="stylesheet">
    <style>
        /* Mode sombre */
        body {
            font-family: 'Arial', sans-serif;
            background-color: #121212; /* Fond sombre */
            color: #eaeaea; /* Texte en gris clair */
            margin: 0;
            display: flex;
            justify-content: center;
            align-items: center;
            min-height: 100vh;
        }

        .container {
            background: #1e1e1e; /* Fond sombre des conteneurs */
            max-width: 600px;
            width: 100%;
            border-radius: 10px;
            padding: 20px;
            box-shadow: 0 1px 3px rgba(0, 0, 0, 0.3);
        }

        .header {
            display: flex;
            justify-content: space-between;
            align-items: center;
            border-bottom: 1px solid #333;
            padding-bottom: 10px;
        }

        .header h1 {
            font-size: 24px;
            font-weight: 600;
            color: #fff;
        }

        .img-container {
            text-align: center;
            margin: 20px 0;
        }

        .img-container img {
            max-width: 100%;
            height: auto;
            border-radius: 10px;
            border: 1px solid #333;
        }

        .details {
            line-height: 1.6;
            font-size: 16px;
        }

        .details p {
            margin: 10px 0;
        }

        .btn-primary, .btn-secondary {
            width: 100%;
            font-weight: bold;
            margin-top: 10px;
            padding: 10px;
            border-radius: 5px;
        }

        .btn-primary {
            background-color: #3897f0;
            border-color: #3897f0;
        }

        .btn-primary:hover {
            background-color: #3183d1;
        }

        .btn-secondary {
            background-color: #333;
            border-color: #333;
            color: #eaeaea;
        }

        .btn-secondary:hover {
            background-color: #444;
        }

        .alert {
            margin-top: 20px;
            font-size: 14px;
        }

        /* Hover effect for links */
        .btn-link:hover {
            color: #3897f0 !important;
        }
    </style>
</head>
<body>
    <div class="container">
        <div class="header">
            <h1>Randonnée</h1>
            <a href="user_dashboard.php" class="btn btn-link">Retour</a>
        </div>
        <div class="img-container">
            <img src="<?php echo htmlspecialchars($details['image']); ?>" alt="Image de la randonnée">
        </div>
        <div class="details">
            <p><strong>Lieu :</strong> <?php echo htmlspecialchars($details['location']); ?></p>
            <p><strong>Distance :</strong> <?php echo htmlspecialchars($details['distance']); ?> km</p>
            <p><strong>Difficulté :</strong> <?php echo htmlspecialchars($details['difficulte']); ?></p>
            <p><strong>Nombre d'inscrits :</strong> <?php echo htmlspecialchars($details['nb_inscrits']); ?></p>
        </div>

        <!-- Messages -->
        <?php if ($success): ?>
            <div class="alert alert-success"><?php echo htmlspecialchars($success); ?></div>
        <?php endif; ?>
        <?php if ($error): ?>
            <div class="alert alert-danger"><?php echo htmlspecialchars($error); ?></div>
        <?php endif; ?>

        <!-- Boutons -->
        <a href="randonnee_details_user.php?id=<?php echo $details['id']; ?>&action=inscrire" class="btn btn-primary">S'inscrire</a>
        <a href="user_dashboard.php" class="btn btn-secondary">Retour</a>
    </div>
</body>
</html>
