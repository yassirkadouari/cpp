<?php
session_start();
require_once 'Randonnee.php';
require_once 'User.php'; // Classe User avec la méthode getUserById
require_once 'Commentaire.php'; // Inclure la classe Commentaire pour gérer les commentaires

// Initialisation des variables
$success = "";
$error = "";

// Vérification de la session
if (!isset($_SESSION['user_id'])) {
    header('Location: login.php');
    exit();
}

// Vérification de l'ID de la randonnée
if (!isset($_GET['id'])) {
    header('Location: organisateur_dashboard.php');
    exit();
}

$randonnee = new Randonnee();
$details = $randonnee->getById($_GET['id']); // Méthode pour récupérer les détails de la randonnée par ID

if (!$details) {
    echo "Randonnée introuvable.";
    exit();
}

// Récupération du nom du guide si disponible et vérification du rôle
$guideName = 'Aucun guide assigné';
if (!empty($details['guide_id'])) {
    $user = new User();
    $guide = $user->getUserById($details['guide_id']); // Utilisation de la méthode correcte
    if ($guide && $guide['role'] === 'guide') {
        $guideName = $guide['nom'];
    }
}

// Gestion de l'inscription
if (isset($_GET['action']) && $_GET['action'] === 'inscrire') {
    try {
        $inscription = new Inscription();
        $inscription->registerUserToRando($_SESSION['user_id'], $details['id']);
        $randonnee->incrementInscrits($details['id']); // Mise à jour du nombre d'inscrits
        $success = "Vous êtes inscrit avec succès à cette randonnée.";
        $details = $randonnee->getById($details['id']); // Actualisation des détails
    } catch (Exception $e) {
        $error = $e->getMessage();
    }
}

// Récupérer les commentaires pour cette randonnée
$commentaire = new Commentaire();
$comments = $commentaire->getCommentsForRandonnee($details['id']);

// Gestion de l'ajout d'un commentaire
if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['commentaire'])) {
    try {
        $commentaire->addComment($_SESSION['user_id'], $details['id'], $_POST['commentaire']);
        $success = "Votre commentaire a été ajouté avec succès.";
        $comments = $commentaire->getCommentsForRandonnee($details['id']); // Rafraîchir les commentaires
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
        /* Mode sombre inspiré d'Instagram */
        body {
            font-family: 'Arial', sans-serif;
            background-color: #121212; /* Fond sombre */
            color: #eaeaea; /* Texte clair */
            margin: 0;
            display: flex;
            justify-content: center;
            align-items: center;
            min-height: 100vh;
        }

        .container {
            background: #1e1e1e; /* Fond sombre des conteneurs */
            max-width: 800px;
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

        .btn-edit {
            background: #ffc107;
            color: #fff;
            border: none;
        }

        .btn-edit:hover {
            background: #e0a800;
        }

        .btn-delete {
            background: #dc3545;
            color: #fff;
            border: none;
        }

        .btn-delete:hover {
            background: #c82333;
        }

        .alert {
            margin-top: 20px;
            font-size: 14px;
        }

        /* Hover effect for links */
        .btn-link:hover {
            color: #3897f0 !important;
        }

        /* Commentaire styles */
        .comment-container {
            margin-top: 20px;
        }

        .comment {
            background: #2c2c2c;
            padding: 10px;
            margin-bottom: 15px;
            border-radius: 8px;
        }

        .comment .author {
            font-weight: bold;
            color: #3897f0;
        }

        .comment .email {
            color: #aaa;
            font-size: 0.9em;
        }

        .comment .content {
            margin-top: 5px;
        }

    </style>
</head>
<body>
    <div class="container">
        <div class="header">
            <h1>Détails de la Randonnée</h1>
            <a href="organisateur_dashboard.php" class="btn btn-link">Retour</a>
        </div>
        <div class="img-container">
            <img src="<?php echo htmlspecialchars($details['image']); ?>" alt="Image de la randonnée">
        </div>
        <div class="details">
            <p><strong>Lieu :</strong> <?php echo htmlspecialchars($details['location']); ?></p>
            <p><strong>Distance :</strong> <?php echo htmlspecialchars($details['distance']); ?> km</p>
            <p><strong>Difficulté :</strong> <?php echo htmlspecialchars($details['difficulte']); ?></p>
            <p><strong>Nombre d'inscrits :</strong> <?php echo htmlspecialchars($details['nb_inscrits']); ?></p>
            <p><strong>Guide :</strong> <?php echo htmlspecialchars($guideName); ?></p>
        </div>

        <!-- Messages -->
        <?php if ($success): ?>
            <div class="alert alert-success"><?php echo htmlspecialchars($success); ?></div>
        <?php endif; ?>
        <?php if ($error): ?>
            <div class="alert alert-danger"><?php echo htmlspecialchars($error); ?></div>
        <?php endif; ?>

        <!-- Actions -->
        <a href="update_randonnee.php?id=<?php echo $details['id']; ?>" class="btn btn-edit">Modifier</a>
        <a href="organisateur_dashboard.php?delete_id=<?php echo $details['id']; ?>" class="btn btn-delete" onclick="return confirm('Êtes-vous sûr de vouloir supprimer cette randonnée ?');">Supprimer</a>

        <!-- Formulaire pour ajouter un commentaire -->
        <div class="comment-container">
            <h4>Ajouter un commentaire</h4>
            <form method="POST">
                <textarea name="commentaire" class="form-control" rows="4" required></textarea>
                <button type="submit" class="btn btn-primary mt-2">Publier le commentaire</button>
            </form>
        </div>

        <!-- Affichage des commentaires -->
        <div class="comments">
            <h4>Commentaires :</h4>
            <?php foreach ($comments as $comment): ?>
                <div class="comment">
                    <div class="author"><?php echo htmlspecialchars($comment['auteur']); ?></div>
                    <div class="email"><?php echo htmlspecialchars($comment['email']); ?></div>
                    <div class="content"><?php echo nl2br(htmlspecialchars($comment['commentaire'])); ?></div>
                    <div class="date"><?php echo htmlspecialchars($comment['date_creation']); ?></div>
                </div>
            <?php endforeach; ?>
        </div>
    </div>
</body>
</html>
