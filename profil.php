<?php
session_start();
require_once 'User.php';


if (!isset($_SESSION['user_id'])) {
    header('Location: login.php');
    exit();
}

$user = new User();
$userData = $user->getUserById($_SESSION['user_id']);

if (!$userData) {
    echo "Utilisateur introuvable.";
    exit();
}

$error = "";
$success = "";

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $nom = $_POST['nom'];
    $email = $_POST['email'];

    try {
        $user->updateProfile($_SESSION['user_id'], $nom, $email);
        $success = "Profil mis à jour avec succès.";
        $userData = $user->getUserById($_SESSION['user_id']); 
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
    <title>Mon Profil</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0-alpha1/dist/css/bootstrap.min.css" rel="stylesheet">
    <style>
      
        body {
            font-family: 'Arial', sans-serif;
            background-color: #121212; 
            color: #eaeaea; 
            margin: 0;
            display: flex;
            flex-direction: column;
            min-height: 100vh; 
        }

        
        .navbar {
            background-color: #1f1f1f; 
            border-radius: 30px 30px 0 0; 
            margin-bottom: 20px;
        }

        .navbar-brand, .navbar a {
            color: #ffffff !important;
            font-weight: bold;
        }

        .navbar a:hover {
            color: #3897f0 !important; 
        }

        
        .profile-container {
            background: #1e1e1e; 
            padding: 30px;
            border-radius: 20px;
            box-shadow: 0px 4px 8px rgba(0, 0, 0, 0.2);
            width: 400px;
            margin: 0 auto;
            margin-top: 50px;
        }

        .form-control:focus {
            border-color: #3897f0;
            box-shadow: 0 0 10px #3897f0;
        }

        .btn-primary {
            background: #3897f0;
            border: none;
            transition: background 0.3s ease-in-out, transform 0.2s ease-in-out;
            border-radius: 30px;
        }

        .btn-primary:hover {
            background: #3183d1;
            transform: scale(1.05);
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

        /* Boutons et hover */
        .btn-link {
            color: #3897f0;
        }

        .btn-link:hover {
            color: #3183d1;
        }

        .form-control {
            background-color: #333;
            color: #eaeaea;
            border: 1px solid #444;
            border-radius: 10px;
        }
    </style>
</head>
<body>

   
    <nav class="navbar navbar-expand-lg navbar-dark">
        <div class="container">
            <a class="navbar-brand" href="#">Mon Profil</a>
            <div class="collapse navbar-collapse">
                <ul class="navbar-nav ms-auto">
                    <?php if ($_SESSION['role'] === 'organisateur'): ?>
                        <li class="nav-item"><a class="nav-link" href="organisateur_dashboard.php">Tableau de Bord</a></li>
                    <?php elseif ($_SESSION['role'] === 'utilisateur'): ?>
                        <li class="nav-item"><a class="nav-link" href="user_dashboard.php">Tableau de Bord</a></li>
                    <?php endif; ?>
                    <li class="nav-item"><a class="nav-link" href="logout.php">Déconnexion</a></li>
                </ul>
            </div>
        </div>
    </nav>

    
    <div class="profile-container">
        <h3 class="text-center mb-4">Mon Profil</h3>
        <?php if (!empty($success)): ?>
            <div class="alert alert-success"><?php echo htmlspecialchars($success); ?></div>
        <?php endif; ?>
        <?php if (!empty($error)): ?>
            <div class="alert alert-danger"><?php echo htmlspecialchars($error); ?></div>
        <?php endif; ?>
        <form method="POST">
            <div class="mb-3">
                <label for="nom" class="form-label">Nom</label>
                <input type="text" name="nom" id="nom" class="form-control" value="<?php echo htmlspecialchars($userData['nom']); ?>" required>
            </div>
            <div class="mb-3">
                <label for="email" class="form-label">Email</label>
                <input type="email" name="email" id="email" class="form-control" value="<?php echo htmlspecialchars($userData['email']); ?>" required>
            </div>
            <button type="submit" class="btn btn-primary w-100">Mettre à jour</button>
        </form>
    </div>

   
    <footer class="footer">
        <p>&copy; 2024 RandoSite. Tous droits réservés.</p>
    </footer>

</body>
</html>
