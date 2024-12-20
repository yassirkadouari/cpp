<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Sign Up</title>
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
        }
        .signup-container {
            background: #fff;
            padding: 30px;
            border-radius: 15px;
            box-shadow: 0px 10px 30px rgba(0, 0, 0, 0.1);
            width: 400px;
            animation: fadeIn 1s ease-in-out;
        }
        .form-control:focus {
            border-color: #84fab0;
            box-shadow: 0 0 10px #84fab0;
        }
        .role-options {
            display: flex;
            justify-content: space-between;
            gap: 10px;
        }
        .role-option {
            flex: 1;
            text-align: center;
            padding: 10px 20px;
            border: 2px solid #8fd3f4;
            border-radius: 8px;
            cursor: pointer;
            transition: all 0.3s ease;
        }
        .role-option.active {
            background-color: #84fab0;
            color: #fff;
            border-color: #84fab0;
        }
        .role-option:hover {
            transform: scale(1.05);
            box-shadow: 0px 5px 15px rgba(0, 0, 0, 0.1);
        }
        @keyframes fadeIn {
            from {
                opacity: 0;
                transform: translateY(-20px);
            }
            to {
                opacity: 1;
                transform: translateY(0);
            }
        }
    </style>
</head>
<body>
    <div class="signup-container">
        <h2 class="text-center mb-4">Créer un compte</h2>
        <form id="signupForm" method="POST" action="process_signup.php">
            <div class="mb-3">
                <label for="name" class="form-label">Nom</label>
                <input type="text" class="form-control" id="name" name="name" placeholder="Entrez votre nom" required>
            </div>
            <div class="mb-3">
                <label for="email" class="form-label">Email</label>
                <input type="email" class="form-control" id="email" name="email" placeholder="Entrez votre email" required>
            </div>
            <div class="mb-3">
                <label for="password" class="form-label">Mot de passe</label>
                <input type="password" class="form-control" id="password" name="password" placeholder="Créez un mot de passe" required>
            </div>
            <div class="mb-3">
                <label for="role" class="form-label">Choisissez un rôle</label>
                <div class="role-options">
                    <div class="role-option" data-role="utilisateur">Utilisateur</div>
                    <div class="role-option" data-role="organisateur">Organisateur</div>
                    <div class="role-option" data-role="guide">Guide</div>
                </div>
            </div>
            <input type="hidden" name="role" id="role" value="utilisateur">
            <button type="submit" class="btn btn-primary w-100">S'inscrire</button>
        </form>
    </div>

    <script>
        const roleOptions = document.querySelectorAll('.role-option');
        const roleInput = document.getElementById('role');

        roleOptions.forEach(option => {
            option.addEventListener('click', () => {
                // Réinitialiser toutes les options
                roleOptions.forEach(opt => opt.classList.remove('active'));
                // Activer l'option sélectionnée
                option.classList.add('active');
                // Mettre à jour le rôle dans le champ caché
                roleInput.value = option.getAttribute('data-role');
            });
        });
    </script>
</body>
</html>
