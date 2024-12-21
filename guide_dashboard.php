<?php
session_start();
require_once 'Randonnee.php';
require_once 'User.php';

// Vérification de la session

if (!isset($_SESSION['user_id'])) {
    header('Location: login.php');  // Redirige vers la page de connexion si non connecté
    exit();
}



$randonnee = new Randonnee();

// Récupération de toutes les randonnées
$randos = $randonnee->getAll();

// Récupération des randonnées où le guide est assigné
$myRandos = $randonnee->getByGuide($_SESSION['user_id']);
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Tableau de bord - Guide</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0-alpha1/dist/css/bootstrap.min.css" rel="stylesheet">
    <style>
        body {
            font-family: 'Arial', sans-serif;
            background: #f5f5f5;
            margin: 0;
            display: flex;
            flex-direction: column;
            min-height: 100vh;
        }

        .navbar {
            background: #343a40;
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
            border-radius: 10px;
            overflow: hidden;
            box-shadow: 0px 4px 8px rgba(0, 0, 0, 0.1);
            transition: transform 0.3s ease, box-shadow 0.3s ease;
            background: #ffffff;
        }

        .card:hover {
            transform: translateY(-10px);
            box-shadow: 0px 8px 16px rgba(0, 0, 0, 0.2);
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

        .footer {
            background: #343a40;
            color: #ffffff;
            text-align: center;
            padding: 10px 0;
            margin-top: auto;
        }

        .btn-edit {
            background-color: #ffc107;
            color: white;
        }

        .btn-edit:hover {
            background-color: #e0a800;
        }
    </style>
</head>
<body>
    <!-- Navbar -->
    <nav class="navbar navbar-expand-lg navbar-dark">
        <div class="container">
            <a class="navbar-brand" href="#">Tableau de Bord - Guide</a>
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
        <h1 class="my-4 text-center">Toutes les Randonnées</h1>
        <div class="row g-4">
            <?php foreach ($randos as $rando): ?>
                <div class="col-md-4">
                    <div class="card">
                        <!-- Lien vers les détails -->
                        <a href="randonnee_details_user.php?id=<?php echo $rando['id']; ?>">
                            <img src="<?php echo htmlspecialchars($rando['image']); ?>" alt="Image de la randonnée" class="card-img-top">
                        </a>
                        <div class="card-body">
                            <h5 class="card-title"><?php echo htmlspecialchars($rando['location']); ?></h5>
                            <p class="text-muted">Organisé par : <?php echo htmlspecialchars($rando['organisateur_name']); ?></p>
                            <?php if (in_array($rando, $myRandos)): ?>
                                <!-- Bouton de modification uniquement pour les randonnées du guide -->
                                <a href="update_randonnee.php?id=<?php echo $rando['id']; ?>" class="btn btn-edit w-100">Modifier</a>
                            <?php endif; ?>
                        </div>
                    </div>
                </div>
            <?php endforeach; ?>
        </div>
    </div>

    <!-- Footer -->
    <footer class="footer">
        <p>&copy; 2024 RandoSite. Tous droits réservés.</p>
    </footer>
</body>
</html>
