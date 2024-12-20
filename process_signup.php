<?php
require_once 'User.php';

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $name = htmlspecialchars($_POST['name']);
    $email = htmlspecialchars($_POST['email']);
    $password = htmlspecialchars($_POST['password']);
    $role = htmlspecialchars($_POST['role']);

    $user = new User();

    if ($user->register($name, $email, $password, $role)) {
        header('Location: login.php');
    } else {
        echo "Erreur lors de l'inscription. Veuillez réessayer.";
    }
}
?>
