<!DOCTYPE html>
<html lang="fr">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Formulaire de Connexion</title>

    <!-- Bootstrap CSS -->
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">

    <style>
        body {
            background: #f0f2f5;
            height: 100vh;
            display: flex;
            justify-content: center;
            align-items: center;
        }

        .login-card {
            width: 380px;
            border-radius: 15px;
            box-shadow: 0 4px 20px rgba(0,0,0,0.1);
            padding: 30px;
            background: #fff;
        }

        .login-card h3 {
            text-align: center;
            margin-bottom: 20px;
            font-weight: 600;
        }
    </style>
</head>
<body>

    <div class="login-card">
        <h3>Connexion</h3>

        <form method="POST" action="userController"> 
            <!-- Email -->
            <div class="mb-3">
                <label for="email" class="form-label">Email</label>
                <input type="email" name="email" class="form-control" id="email" placeholder="Entrez votre email" required>
                <!-- name permet de recuperer les infos dans le controller (sur la page php) -->
            </div>

            <!--Password -->
            <div class="mb-3">
                <label for="password" class="form-label">Mot de passe</label>
                <input type="password" name="password" class="form-control" id="password" placeholder="Entrez votre mot de passe" required>
            </div>

            <button type="submit" name="btnLogin" class="btn btn-primary w-100">Se connecter</button>

        </form>
    </div>

    <!-- Bootstrap JS -->
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>

</body>
</html>
