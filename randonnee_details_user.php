<?php
session_start();
require_once 'Randonnee.php';
require_once 'User.php';
require_once 'Inscription.php';
require_once 'Commentaire.php';

// Vérification de la session
if (!isset($_SESSION['user_id'])) {
    header('Location: login.php');  // Redirige vers la page de connexion si non connecté
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

// Vérification si l'utilisateur connecté est le guide de cette randonnée
$isGuide = ($details['guide_id'] == $_SESSION['user_id']);  // Comparer l'ID du guide de la randonnée avec l'ID de l'utilisateur connecté

$inscription = new Inscription();
$commentaire = new Commentaire();
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

// Gestion de l'ajout de commentaires
if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['commentaire'])) {
    try {
        $commentText = trim($_POST['commentaire']);
        if (!empty($commentText)) {
            $commentaire->addComment($_SESSION['user_id'], $details['id'], $commentText);
            header("Location: randonnee_details_user.php?id=" . $details['id']); // Rafraîchir la page
            exit();
        } else {
            $error = "Le commentaire ne peut pas être vide.";
        }
    } catch (Exception $e) {
        $error = $e->getMessage();
    }
}

// Récupérer les commentaires
$comments = $commentaire->getCommentsForRandonnee($details['id']);
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Détails de la Randonnée</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0-alpha1/dist/css/bootstrap.min.css" rel="stylesheet">
    <style>
        body {
            font-family: 'Arial', sans-serif;
            background-color: #121212;
            color: #eaeaea;
            margin: 0;
            display: flex;
            justify-content: center;
            align-items: center;
            min-height: 100vh;
        }
        .container {
            background: #1e1e1e;
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
        .details p {
            margin: 10px 0;
        }
        .btn-primary, .btn-secondary, .btn-edit {
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
            background-color: #ffc107;
            color: white;
        }
        .btn-edit:hover {
            background-color: #e0a800;
        }
        .comments-section ul {
            padding: 0;
        }
        .comments-section ul li {
            list-style: none;
            margin-bottom: 15px;
            background:rgb(172, 165, 165);
            padding: 10px;
            border-radius: 5px;
        }
        .comments-section form textarea {
            width: 100%;
            background:rgb(163, 156, 156);
            border: 1px solid #444;
            color: #eaeaea;
            padding: 10px;
            border-radius: 5px;
        }
    </style>
</head>
<body>
    <div class="container">
        <div class="header">
            <h1>Détails de la Randonnée</h1>
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
        <?php if ($success): ?>
            <div class="alert alert-success"><?php echo htmlspecialchars($success); ?></div>
        <?php endif; ?>
        <?php if ($error): ?>
            <div class="alert alert-danger"><?php echo htmlspecialchars($error); ?></div>
        <?php endif; ?>
        <?php if ($isGuide): ?>
            <a href="update_randonnee.php?id=<?php echo $details['id']; ?>" class="btn btn-edit">Modifier</a>
        <?php endif; ?>
        <a href="randonnee_details_user.php?id=<?php echo $details['id']; ?>&action=inscrire" class="btn btn-primary">S'inscrire</a>
        <a href="user_dashboard.php" class="btn btn-secondary">Retour</a>
        <div class="comments-section mt-4">
            <h2>Commentaires</h2>
            <?php if ($comments): ?>
                <ul class="list-group mb-3">
                    <?php foreach ($comments as $comment): ?>
                        <li class="list-group-item">
                            <strong><?php echo htmlspecialchars($comment['auteur']); ?> (<?php echo htmlspecialchars($comment['email']); ?>)</strong>
                            <span class="text-muted" style="font-size: 0.9em;"><?php echo htmlspecialchars($comment['date_creation']); ?></span>
                            <p><?php echo htmlspecialchars($comment['commentaire']); ?></p>
                        </li>
                    <?php endforeach; ?>
                </ul>
            <?php else: ?>
                <p>Aucun commentaire pour le moment. Soyez le premier à commenter !</p>
            <?php endif; ?>
            <form method="POST">
                <textarea name="commentaire" rows="3" placeholder="Ajoutez un commentaire..."></textarea>
                <button type="submit" class="btn btn-primary mt-2">Poster</button>
            </form>
        </div>
    </div>
</body>
</html>
