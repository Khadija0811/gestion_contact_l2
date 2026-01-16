<?php 

    function getConnexion() // getConnexion permet de connecter notre app à la BD
    {
        $host = "localhost"; // host permet de se connecter au serveur de BD (localhost en local)
        $user = "root"; // Nom d'utilisateur par defaut de MySQL ("root" en local)
        $password = ""; // Mot de passe associé à l'utilisateur MySQL (vide par défaut en local)    
        $dbname = "gcontact"; // dbname permet de spécifier le nom de la BD à laquelle on veut se connecter
        $db = null; // db represente une instance PDO(Php Data Object) de connexion à la BD; elle est initialisée à null car il y'a absence de valeur au départ

        try { // try permet d'essayer de se connecter à la BD pour capturer une éventuelle erreur
            $db = new PDO("mysql:host=$host;dbname=$dbname", $user, $password); // db contient des informations (instance PDO) qui permmettent d'effectuer l'operation de CRUD sur la BD
            return $db; 

        } catch (PDOException $error) { // catch permet de capturer une erreur PDO
            die("La connexion à la BD a échoué: " . $error->getMessage()); // Si une erreur survient, die arrête l'exécution du script et affiche un message d'erreur.
        }
    }
?>