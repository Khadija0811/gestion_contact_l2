<?php
    // Connecter un utilisateur

    if(isset($_POST['btnLogin'])) // verifier si le formulaire existe
    {
        $email = $_POST['email']; // recuperer l'email
        $password = $_POST['password']; // recuperer le mot de passe

        // Verifier si les informations sont correctes
            // var_dump($email); // afficher l'email
            // var_dump($password); // afficher le mot de passe
            // die; // arreter l'execution du script

        // Validation des données 
            if(!(filter_var($email, FILTER_VALIDATE_EMAIL)) || strlen($password) < 8) // filter_var permet de valider les données 
            // FILTER_VALIDATE_EMAIL permet de valider le format de l'email et strlen permet de verifier la longueur du mot de passe
            {
                $error = "Email ou Mot de passe incorrecte. "; // message d'erreur
                header("Location:login?error=$error"); // header permet de rediriger vers une autre page
            }
            else 
            {
                // TODO: Authentifier user dans la BD
                header("Location:listeContact");
            }

           
    }
?>