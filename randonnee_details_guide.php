<?php
session_start();
require_once 'Randonnee.php';
require_once 'Commentaire.php'; // Ajout de la classe Commentaire

// Vérification si l'utilisateur est connecté. Si ce n'est pas le cas, redirection vers la page de connexion.
if (!isset($_SESSION['user_id'])) {
    header('Location: login.php');
    exit();
}

// Vérification si l'ID de la randonnée est passé dans l'URL. Si ce n'est pas le cas, redirection vers le tableau de bord du guide.
if (!isset($_GET['id'])) {
    header('Location: dashboard_guide.php');
    exit();
}

// Création d'une instance de la classe Randonnee pour accéder aux détails d'une randonnée spécifique.
$randonnee = new Randonnee();
$commentaire = new Commentaire(); // Instance de la classe Commentaire

// Récupération des détails de la randonnée par son ID.
$details = $randonnee->getById($_GET['id']);

// Vérification si la randonnée existe.
if (!$details) {
    echo "Randonnée introuvable.";
    exit();
}

// Vérification si l'utilisateur connecté est bien le guide assigné à cette randonnée.
if ($details['guide_id'] != $_SESSION['user_id']) {
    header('Location: dashboard_guide.php');  // Redirection vers le tableau de bord si ce n'est pas le guide.
    exit();
}

// Traitement du formulaire de modification de la randonnée lorsque l'utilisateur soumet le formulaire.
if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    // Vérification si un commentaire a été soumis
    if (isset($_POST['commentaire'])) {  
        $commentaireText = $_POST['commentaire'];
        $userId = $_SESSION['user_id']; // Utilisateur connecté
        $randonneeId = $details['id'];  // ID de la randonnée

        // Ajouter le commentaire
        $commentaire->addComment($userId, $randonneeId, $commentaireText);
    }

    // Vérification des champs du formulaire avant de les utiliser
    $location = isset($_POST['location']) ? $_POST['location'] : $details['location'];
    $difficulte = isset($_POST['difficulte']) ? $_POST['difficulte'] : $details['difficulte'];
    $distance = isset($_POST['distance']) ? $_POST['distance'] : $details['distance'];
    $image = isset($_POST['image']) ? $_POST['image'] : $details['image'];

    try {
        // Mise à jour des détails de la randonnée avec les nouvelles informations
        $randonnee->update($details['id'], $location, $difficulte, $distance, $image);
        
        // Récupérer les détails mis à jour après la modification
        $details = $randonnee->getById($details['id']);
        $success = "Randonnée mise à jour avec succès."; // Message de succès après la mise à jour
    } catch (Exception $e) {
        $error = $e->getMessage(); // Message d'erreur en cas de problème lors de la mise à jour
    }
}

// Récupérer les commentaires associés à cette randonnée
$comments = $commentaire->getCommentsForRandonnee($details['id']);
?>

<!DOCTYPE html>
<html lang="fr">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Détails de la Randonnée - Guide</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0-alpha1/dist/css/bootstrap.min.css" rel="stylesheet">
    <style>
        /* Style général pour la page */
        body {
            font-family: 'Arial', sans-serif;
            background: #121212;
            color: #ffffff;
            margin: 0;
            display: flex;
            flex-direction: column;
            min-height: 100vh;
        }

        /* Navbar */
        .navbar {
            background: #1f1f1f;
        }

        .navbar-brand, .navbar a {
            color: #ffffff !important;
        }

        .container {
            flex: 1;
            padding: 20px;
        }

        .card {
            border: none;
            border-radius: 15px;
            overflow: hidden;
            box-shadow: 0px 4px 8px rgba(0, 0, 0, 0.3);
            background: #1f1f1f;
            transition: transform 0.3s ease, box-shadow 0.3s ease;
        }

        .card:hover {
            transform: translateY(-10px);
            box-shadow: 0px 8px 16px rgba(0, 0, 0, 0.5);
        }

        .card img {
            height: 200px;
            object-fit: cover;
        }

        .footer {
            background: #1f1f1f;
            color: #ffffff;
            text-align: center;
            padding: 10px 0;
            margin-top: auto;
        }

        /* Commentaires */
        .comments-section {
            background: #1f1f1f;
            border-radius: 15px;
            padding: 20px;
            margin-top: 20px;
            box-shadow: 0px 4px 8px rgba(0, 0, 0, 0.3);
        }

        .comments-section h3 {
            color: #ffffff;
        }

        .comments-section ul {
            list-style: none;
            padding-left: 0;
        }

        .comments-section ul li {
            background: #333333;
            padding: 10px;
            margin-bottom: 15px;
            border-radius: 5px;
        }

        .comments-section form textarea {
            width: 100%;
            background: #333333;
            color: #fff;
            border: 1px solid #555;
            border-radius: 5px;
            padding: 10px;
        }

        .comments-section form button {
            background-color: #4c92b7;
            color: white;
            border-radius: 5px;
            padding: 10px 20px;
            width: 100%;
            margin-top: 10px;
        }

        .comments-section form button:hover {
            background-color: #388aa3;
        }
    </style>
</head>
<body>
    <!-- Navbar -->
    <nav class="navbar navbar-expand-lg navbar-dark">
        <div class="container">
            <a class="navbar-brand" href="#">Détails de la Randonnée - Guide</a>
            <div class="collapse navbar-collapse">
                <ul class="navbar-nav ms-auto">
                    <li class="nav-item"><a class="nav-link" href="profil.php">Mon Profil</a></li>
                    <li class="nav-item"><a class="nav-link" href="logout.php">Déconnexion</a></li>
                </ul>
            </div>
        </div>
    </nav>

    <!-- Contenu principal -->
    <div class="container">
        <h1 class="my-4 text-center">Détails de la Randonnée</h1>

        <!-- Affichage des messages de succès ou d'erreur -->
        <?php if (isset($success)): ?>
            <div class="alert alert-success"><?php echo htmlspecialchars($success); ?></div>
        <?php elseif (isset($error)): ?>
            <div class="alert alert-danger"><?php echo htmlspecialchars($error); ?></div>
        <?php endif; ?>

        <!-- Affichage des détails de la randonnée -->
        <div class="row">
            <div class="col-md-6">
                <img src="<?php echo htmlspecialchars($details['image']); ?>" alt="Image de la randonnée" class="img-fluid rounded">
            </div>
            <div class="col-md-6">
                <p><strong>Lieu :</strong> <?php echo htmlspecialchars($details['location']); ?></p>
                <p><strong>Distance :</strong> <?php echo htmlspecialchars($details['distance']); ?> km</p>
                <p><strong>Difficulté :</strong> <?php echo htmlspecialchars($details['difficulte']); ?></p>
            </div>
        </div>

        <!-- Formulaire pour la modification de la randonnée -->
        <h3 class="mt-4 text-center">Modifier la Randonnée</h3>
        <form action="randonnee_details_guide.php?id=<?php echo $details['id']; ?>" method="POST">
            <div class="mb-3">
                <label for="location" class="form-label">Lieu</label>
                <input type="text" class="form-control" id="location" name="location" value="<?php echo htmlspecialchars($details['location']); ?>" required>
            </div>
            <div class="mb-3">
                <label for="difficulte" class="form-label">Difficulté</label>
                <input type="text" class="form-control" id="difficulte" name="difficulte" value="<?php echo htmlspecialchars($details['difficulte']); ?>" required>
            </div>
            <div class="mb-3">
                <label for="distance" class="form-label">Distance (en km)</label>
                <input type="number" class="form-control" id="distance" name="distance" value="<?php echo htmlspecialchars($details['distance']); ?>" required>
            </div>
            <div class="mb-3">
                <label for="image" class="form-label">URL de l'image</label>
                <input type="text" class="form-control" id="image" name="image" value="<?php echo htmlspecialchars($details['image']); ?>" required>
            </div>
            <button type="submit" class="btn btn-edit w-100">Mettre à jour</button>
        </form>

        <!-- Section des commentaires -->
        <div class="comments-section">
            <h3>Commentaires</h3>
            <ul>
                <?php foreach ($comments as $comment): ?>
                    <li>
                        <?php echo htmlspecialchars($comment['commentaire']); ?> - <strong><?php echo htmlspecialchars($comment['auteur']); ?></strong>
                    </li>
                <?php endforeach; ?>
            </ul>

            <!-- Formulaire de commentaire -->
            <form action="randonnee_details_guide.php?id=<?php echo $details['id']; ?>" method="POST">
                <textarea name="commentaire" rows="4" placeholder="Ajoutez votre commentaire" required></textarea>
                <button type="submit">Envoyer</button>
            </form>
        </div>
    </div>

    <!-- Footer -->
    <footer class="footer">
        <p>&copy; 2024 RandoSite. Tous droits réservés.</p>
    </footer>
</body>
</html>
