<?php
session_start();
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Login</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0-alpha1/dist/css/bootstrap.min.css" rel="stylesheet">
    <style>
        body {
            font-family: 'Arial', sans-serif;
            background: linear-gradient(135deg, #84fab0, #8fd3f4);
            display: flex;
            justify-content: center;
            align-items: center;
            height: 100vh;
            margin: 0;
            overflow: hidden;
        }
        .login-container {
            background: #fff;
            padding: 30px;
            border-radius: 15px;
            box-shadow: 0px 10px 30px rgba(0, 0, 0, 0.1);
            width: 400px;
            animation: fadeIn 1.2s ease-in-out, scaleUp 0.6s ease-in-out;
        }
        .form-control:focus {
            border-color: #84fab0;
            box-shadow: 0 0 10px #84fab0;
        }
        button {
            transition: all 0.3s ease-in-out;
        }
        button:hover {
            background: #8fd3f4;
            transform: translateY(-3px);
            box-shadow: 0px 5px 15px rgba(0, 0, 0, 0.1);
        }
        @keyframes fadeIn {
            from {
                opacity: 0;
                transform: translateY(-30px);
            }
            to {
                opacity: 1;
                transform: translateY(0);
            }
        }
        @keyframes scaleUp {
            from {
                transform: scale(0.95);
            }
            to {
                transform: scale(1);
            }
        }
        .floating-label {
            position: relative;
            margin-bottom: 20px;
        }
        .floating-label input {
            border: 1px solid #ccc;
            border-radius: 5px;
            padding: 10px 15px;
            width: 100%;
            transition: border-color 0.3s ease-in-out;
        }
        .floating-label label {
            position: absolute;
            top: 50%;
            left: 15px;
            transform: translateY(-50%);
            color: #aaa;
            font-size: 16px;
            pointer-events: none;
            transition: all 0.3s ease-in-out;
        }
        .floating-label input:focus ~ label,
        .floating-label input:not(:placeholder-shown) ~ label {
            top: -10px;
            font-size: 12px;
            color: #84fab0;
        }
    </style>
</head>
<body>
    <div class="login-container">
        <h2 class="text-center mb-4">Connexion</h2>
        <form method="POST" action="process_login.php">
            <div class="floating-label">
                <input type="email" id="email" name="email" placeholder=" " required>
                <label for="email">Email</label>
            </div>
            <div class="floating-label">
                <input type="password" id="password" name="password" placeholder=" " required>
                <label for="password">Mot de passe</label>
            </div>
            <button type="submit" class="btn btn-primary w-100">Se connecter</button>
        </form>
    </div>
</body>
</html>
