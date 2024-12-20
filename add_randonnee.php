<?php
session_start();
require_once 'Randonnee.php';
require_once 'User.php'; // Classe User pour gérer les utilisateurs

if (!isset($_SESSION['user_id']) || $_SESSION['role'] !== 'organisateur') {
    header('Location: login.php');
    exit();
}

$randonnee = new Randonnee();
$user = new User();
$error = "";

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $organisateur_id = $_SESSION['user_id'];
    $location = $_POST['location'];
    $distance = $_POST['distance'];
    $difficulte = $_POST['difficulte'];
    $guide_email = $_POST['guide_email'];
    $image = $_POST['image']; // On récupère directement le lien de l'image

    // Validation de l'image
    if (!filter_var($image, FILTER_VALIDATE_URL)) {
        $error = "Veuillez fournir un lien valide pour l'image.";
    }

    // Récupération du guide_id à partir de l'email
    try {
        if (empty($error)) {
            $guide = $user->getUserByEmail($guide_email); // Nouvelle méthode pour récupérer le guide
            if (!$guide || $guide['role'] !== 'guide') {
                throw new Exception("Aucun guide avec cet email ou l'utilisateur n'est pas un guide.");
            }

            $guide_id = $guide['id'];

            // Création de la randonnée
            $success = $randonnee->create($location, $distance, $difficulte, $organisateur_id, $guide_id, $image);
            if ($success) {
                header('Location: organisateur_dashboard.php');
                exit();
            } else {
                $error = "Erreur lors de l'ajout de la randonnée.";
            }
        }
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
    <title>Ajouter une Randonnée</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0-alpha1/dist/css/bootstrap.min.css" rel="stylesheet">
    <style>
        body {
            font-family: 'Arial', sans-serif;
            background-color: #121212; /* Fond sombre */
            color: #eaeaea; /* Texte clair */
            margin: 0;
            height: 100vh;
            display: flex;
            flex-direction: column;
            justify-content: space-between;
        }

        .background-animation {
            position: absolute;
            top: 0;
            left: 0;
            width: 100%;
            height: 100%;
            overflow: hidden;
            z-index: -1;
        }

        .background-animation span {
            position: absolute;
            width: 15px;
            height: 15px;
            background: rgba(255, 255, 255, 0.3);
            border-radius: 50%;
            animation: animate 8s linear infinite;
        }

        @keyframes animate {
            0% {
                transform: translateY(0) scale(1);
            }
            50% {
                transform: translateY(-300px) scale(1.2);
            }
            100% {
                transform: translateY(0) scale(1);
            }
        }

        /* Formulaire centré */
        .form-container {
            background: #1e1e1e;
            padding: 30px;
            border-radius: 15px;
            box-shadow: 0px 4px 8px rgba(0, 0, 0, 0.2);
            z-index: 1;
            width: 400px;
            position: relative;
            margin-top: 50px;
        }

        .form-control:focus {
            border-color: #A4C7E7;
            box-shadow: 0 0 10px #A4C7E7;
        }

        .btn-primary {
            background: #A4C7E7;
            border: none;
            transition: background 0.3s ease-in-out, transform 0.2s ease-in-out;
            border-radius: 30px;
        }

        .btn-primary:hover {
            background: #82b9e0;
            transform: scale(1.05);
        }

        .btn-secondary {
            background: #333;
            border: none;
            color: #eaeaea;
            border-radius: 30px;
        }

        .btn-secondary:hover {
            background: #444;
        }

        .navbar {
            background: #1f1f1f;
            border-radius: 30px 30px 0 0;
            padding: 15px;
            width: 100%;
            position: relative;
        }

        .navbar-brand {
            color: #ffffff;
            font-weight: bold;
        }

        .navbar a {
            color: #ffffff !important;
        }

        .navbar a:hover {
            color: #A4C7E7 !important;
        }

        .footer {
            background: #1f1f1f;
            color: #ffffff;
            text-align: center;
            padding: 10px;
            position: absolute;
            bottom: 0;
            width: 100%;
            border-radius: 0 0 30px 30px;
        }

        .alert {
            margin-top: 20px;
            font-size: 14px;
        }

        /* Flexbox pour centrer le contenu */
        .main-content {
            flex-grow: 1;
            display: flex;
            justify-content: center;
            align-items: center;
            margin-top: 50px;
        }
    </style>
</head>
<body>

    <!-- Animation d'arrière-plan -->
    <div class="background-animation">
        <span></span>
        <span></span>
        <span></span>
        <span></span>
        <span></span>
        <span></span>
    </div>

    <!-- Navbar -->
    <nav class="navbar navbar-expand-lg navbar-dark">
        <div class="container">
            <a class="navbar-brand" href="#">Ajouter une Randonnée</a>
        </div>
    </nav>

    <!-- Contenu principal -->
    <div class="main-content">
        <div class="form-container">
            <h3 class="text-center mb-4">Formulaire</h3>
            <?php if (!empty($error)): ?>
                <div class="alert alert-danger"><?php echo htmlspecialchars($error); ?></div>
            <?php endif; ?>
            <?php if (!empty($success)): ?>
                <div class="alert alert-success"><?php echo htmlspecialchars($success); ?></div>
            <?php endif; ?>
            <form method="POST">
                <div class="mb-3">
                    <label for="location" class="form-label">Lieu</label>
                    <input type="text" name="location" id="location" class="form-control" required>
                </div>
                <div class="mb-3">
                    <label for="distance" class="form-label">Distance (en km)</label>
                    <input type="number" step="0.1" name="distance" id="distance" class="form-control" required>
                </div>
                <div class="mb-3">
                    <label for="difficulte" class="form-label">Difficulté</label>
                    <select name="difficulte" id="difficulte" class="form-select" required>
                        <option value="facile">Facile</option>
                        <option value="moyen">Moyen</option>
                        <option value="difficile">Difficile</option>
                    </select>
                </div>
                <div class="mb-3">
                    <label for="guide_email" class="form-label">Email du Guide</label>
                    <input type="email" name="guide_email" id="guide_email" class="form-control" required>
                </div>
                <div class="mb-3">
                    <label for="image" class="form-label">Lien de l'image</label>
                    <input type="url" name="image" id="image" class="form-control" required>
                </div>
                <button type="submit" class="btn btn-primary w-100">Ajouter</button>
            </form>
        </div>
    </div>

    <!-- Footer -->
    <footer class="footer">
        <p>&copy; 2024 RandoSite. Tous droits réservés.</p>
    </footer>

    <script>
        // Génération dynamique des bulles d'animation
        const backgroundAnimation = document.querySelector('.background-animation');
        for (let i = 0; i < 50; i++) {
            const span = document.createElement('span');
            span.style.left = `${Math.random() * 100}%`;
            span.style.animationDuration = `${Math.random() * 10 + 5}s`;
            span.style.animationDelay = `${Math.random() * 5}s`;
            backgroundAnimation.appendChild(span);
        }
    </script>

</body>
</html>
