<?php
session_start();
require_once 'Randonnee.php';
require_once 'User.php';

// Vérification de la session
if (!isset($_SESSION['user_id']) || $_SESSION['role'] !== 'utilisateur') {
    header('Location: login.php');
    exit();
}

// Récupération de toutes les randonnées
$randonnee = new Randonnee();
$randos = $randonnee->getAll();

// Gestion de l'inscription à une randonnée
if (isset($_GET['rando_id'])) {
    // Ajouter ici la logique pour inscrire l'utilisateur
    $success = "Vous êtes inscrit à la randonnée avec succès.";
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Tableau de bord - Utilisateur</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0-alpha1/dist/css/bootstrap.min.css" rel="stylesheet">
    <style>
        body {
            margin: 0;
            padding: 0;
            font-family: 'Arial', sans-serif;
            background: #2b3e50; /* Couleur de fond */
            overflow: hidden; /* Empêche le défilement pour les flocons */
            display: flex;
            flex-direction: column;
            min-height: 100vh;
        }

        #snow {
            position: fixed;
            top: 0;
            left: 0;
            width: 100%;
            height: 100%;
            z-index: -1; /* Derrière tout le contenu */
        }

        .snowflake {
            position: absolute;
            top: -10px;
            color: white;
            font-size: 1em;
            animation: fall linear infinite, sway ease-in-out infinite;
        }

        @keyframes fall {
            0% {
                transform: translateY(-10px);
            }
            100% {
                transform: translateY(100vh); /* Descend jusqu'en bas de l'écran */
            }
        }

        @keyframes sway {
            0%, 100% {
                transform: translateX(0px);
            }
            50% {
                transform: translateX(20px); /* Oscillation horizontale */
            }
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

        .btn-inscription {
            background-color: #007bff;
            color: white;
        }

        .btn-inscription:hover {
            background-color: #0056b3;
        }
    </style>
</head>
<body>
    <!-- Animation de neige -->
    <div id="snow"></div>

    <!-- Navbar -->
    <nav class="navbar navbar-expand-lg navbar-dark">
        <div class="container">
            <a class="navbar-brand" href="#">Utilisateur - Tableau de Bord</a>
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
        <?php if (!empty($success)): ?>
            <div class="alert alert-success text-center"><?php echo htmlspecialchars($success); ?></div>
        <?php endif; ?>
        <div class="row g-4">
            <?php foreach ($randos as $rando): ?>
                <div class="col-md-4">
                    <div class="card">
                        <a href="randonnee_details_user.php?id=<?php echo $rando['id']; ?>">
                            <img 
                                src="<?php echo htmlspecialchars($rando['image']); ?>" 
                                alt="Image de la randonnée" 
                                class="card-img-top">
                        </a>
                        <div class="card-body">
                            <h5 class="card-title"><?php echo htmlspecialchars($rando['location']); ?></h5>
                            <p class="text-muted">Organisé par : <?php echo htmlspecialchars($rando['organisateur_name']); ?></p>
                            <a href="?rando_id=<?php echo $rando['id']; ?>" class="btn btn-inscription w-100">S'inscrire</a>
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

    <script>
        // Animation de neige
        const snowContainer = document.getElementById('snow');

        function createSnowflake() {
            const snowflake = document.createElement('div');
            snowflake.classList.add('snowflake');
            snowflake.textContent = '❄'; // Flocon de neige
            snowflake.style.left = `${Math.random() * 100}vw`; // Position horizontale aléatoire
            snowflake.style.animationDuration = `${Math.random() * 3 + 2}s`; // Durée d'animation entre 2 et 5 secondes
            snowflake.style.fontSize = `${Math.random() * 10 + 10}px`; // Taille aléatoire

            snowContainer.appendChild(snowflake);

            // Supprimer le flocon après sa chute
            setTimeout(() => {
                snowflake.remove();
            }, 5000);
        }

        // Générer un flocon toutes les 100ms
        setInterval(createSnowflake, 100);
    </script>
</body>
</html>
