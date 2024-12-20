<?php
session_start();
require_once 'User.php';

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $email = htmlspecialchars($_POST['email']);
    $password = htmlspecialchars($_POST['password']);

    $user = new User();
    $loggedInUser = $user->login($email, $password);

    if ($loggedInUser) {
        $_SESSION['user_id'] = $loggedInUser['id'];
        $_SESSION['role'] = $loggedInUser['role'];

        // Redirection selon le rôle
        switch ($loggedInUser['role']) {
            case 'utilisateur':
                header('Location: user_dashboard.php');
                break;
            case 'organisateur':
                header('Location: organisateur_dashboard.php');
                break;
            case 'guide':
                header('Location: guide_dashboard.php');
                break;
            default:
                echo "Rôle inconnu.";
        }
    } else {
        echo "<script>alert('Email ou mot de passe incorrect.');</script>";
        echo "<script>window.location.href = 'login.php';</script>";
    }
}
?>
