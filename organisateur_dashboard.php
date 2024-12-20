<?php
session_start();
require_once 'Randonnee.php';

// Vérification de la session
if (!isset($_SESSION['user_id'])) {
    header('Location: login.php');
    exit();
}

// Récupération de toutes les randonnées
$randonnee = new Randonnee();
$randos = $randonnee->getAll();
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Tableau de bord - Toutes les Randonnées</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0-alpha1/dist/css/bootstrap.min.css" rel="stylesheet">
    <style>
        /* Body et arrière-plan */
        body {
            font-family: 'Arial', sans-serif;
            background-color: #121212; /* Couleur de fond sombre */
            color: #ffffff; /* Texte en blanc */
            margin: 0;
            display: flex;
            flex-direction: column;
            min-height: 100vh;
        }

        /* Navbar */
        .navbar {
            background-color: #1e1e1e; /* Fond sombre */
        }
        .navbar-brand, .navbar a {
            color: #ffffff !important;
            font-weight: bold;
        }
        .navbar a:hover {
            color: #aaaaaa !important;
        }

        /* Conteneur principal */
        .container {
            flex: 1;
            padding: 20px;
        }

        /* Style des cartes */
        .card {
            border: none;
            border-radius: 10px;
            overflow: hidden;
            background: #1e1e1e; /* Fond sombre */
            color: #ffffff;
            box-shadow: 0px 4px 8px rgba(0, 0, 0, 0.5);
            transition: transform 0.3s ease, box-shadow 0.3s ease;
        }
        .card:hover {
            transform: translateY(-10px);
            box-shadow: 0px 8px 16px rgba(0, 0, 0, 0.7);
        }
        .card img {
            height: 200px;
            object-fit: cover;
        }
        .card-title {
            margin: 10px 0;
            font-weight: bold;
            text-align: center;
        }
        .text-muted {
            color: #aaaaaa !important;
        }

        /* Boutons */
        .btn-outline-primary {
            border-color: #3897f0;
            color: #3897f0;
            font-weight: bold;
        }
        .btn-outline-primary:hover {
            background-color: #3897f0;
            color: #ffffff;
        }

        /* Footer */
        .footer {
            background: #1e1e1e;
            color: #aaaaaa;
            text-align: center;
            padding: 10px 0;
            position: relative;
            bottom: 0;
            width: 100%;
        }

        /* Bouton Ajouter Randonnée */
        .add-button {
            position: fixed;
            bottom: 20px;
            right: 20px;
            background: #3897f0;
            color: #ffffff;
            border: none;
            border-radius: 50px;
            padding: 15px 20px;
            font-size: 18px;
            font-weight: bold;
            transition: all 0.3s ease-in-out;
            z-index: 1000;
        }
        .add-button:hover {
            background: #317bd1;
            transform: scale(1.1);
        }
    </style>
</head>
<body>
    <!-- Navbar -->
    <nav class="navbar navbar-expand-lg">
        <div class="container">
            <a class="navbar-brand" href="#">Toutes les Randonnées</a>
            <div class="collapse navbar-collapse">
                <ul class="navbar-nav ms-auto">
                    <li class="nav-item"><a class="nav-link" href="#">Accueil</a></li>
                    <li class="nav-item"><a class="nav-link" href="profil.php">Mon Profil</a></li>
                    <li class="nav-item"><a class="nav-link" href="logout.php">Déconnexion</a></li>
                </ul>
            </div>
        </div>
    </nav>

    <!-- Contenu principal -->
    <div class="container">
        <h1 class="my-4 text-center">Toutes les Randonnées</h1>
        <div class="row g-4">
            <?php foreach ($randos as $rando): ?>
                <div class="col-md-4">
                    <div class="card">
                        <img 
                            src="<?php echo htmlspecialchars($rando['image']); ?>" 
                            alt="Image de la randonnée" 
                            class="card-img-top">
                        <div class="card-body">
                            <h5 class="card-title"><?php echo htmlspecialchars($rando['location']); ?></h5>
                            <p class="text-muted">Organisé par : <?php echo htmlspecialchars($rando['organisateur_name']); ?></p>
                            <a href="randonnee_details.php?id=<?php echo $rando['id']; ?>" class="btn btn-outline-primary w-100">Voir les détails</a>
                        </div>
                    </div>
                </div>
            <?php endforeach; ?>
        </div>
    </div>

    <!-- Bouton Ajouter Randonnée -->
    <a href="add_randonnee.php" class="add-button">+ Ajouter</a>

    <!-- Footer -->
    <footer class="footer">
        <p>&copy; 2024 RandoSite. Tous droits réservés.</p>
    </footer>
</body>
</html>
