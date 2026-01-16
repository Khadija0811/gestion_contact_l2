<?php
    require_once("db.php"); // require_once permet d'appeler le fichier db.php pour la connexion à la BD

    function register($nom, $email, $password, $tel, $dateNaissance) // register permet de créer un compte utilisateur et insérer les données dans la BD
    {
        // DECLARER LA REQUETE SQL D'INSERTION
        $sql = "INSERT INTO users
                VALUES (null, :nom, :email, :password, :tel, :date_naissance, default, default, NOW(), null) "; // tous les elements de values doivent etre dans le meme ordre que dans la BD
                // les 2 points (:) permettent d'éviter les injections SQL en securisant les données
        // une requete preparée ou securisée permet de dissocier la requete avec les données de la requete


        try {   
                // SECURISER ET PREPARER LA REQUETE
                $requeteSecurisee = getConnexion()->prepare($sql); // requeteSecurisee permet de preparer la requete SQL
            
                // EXECUTER LA REQUETE PREPAREE 
                $requeteSecurisee->execute([
                    'nom' => $nom,
                    'email' => $email,
                    'password' => $password,
                    'tel' => $tel,
                    'date_naissance' => $dateNaissance
                ]); // on prend que les valeurs précédées des 2 points (:)

                // RECUPERER ET RETOURNER L'ID DU DERNIER UTILISATEUR CREE
                $lastInsertId = getConnexion()->lastInsertId();  
                return $lastInsertId ?: null; // ?: signifie que si $lastInsertId est vide, on retourne null

            } catch (PDOException $error) {
                die("Erreur lors la création de compte utilisateur " . $error->getMessage());
            }
    }

    /**
     * Permet d'authentifier un utilisateur
     */
    function login($email, $password)
    {
        // Sélectionne un utilisateur actif (etat = 1)
        $sql = "SELECT * FROM users WHERE email = :email AND etat = 1";

        try {
            $statement = $db->prepare($sql);
            $statement->execute(['email' => $email]);
            $user = $statement->fetch(PDO::FETCH_ASSOC);

            // Vérification du mot de passe haché
            if ($user && password_verify($password, $user['password'])) {
                return $user; // Retourne l’utilisateur si ok
            }
            return false; // Sinon, connexion échouée

        } catch (PDOException $error) {
            error_log("Erreur lors de la connexion de l'utilisateur " . $error->getMessage());
            throw $error;
        }
    }

    /**
     * Récupérer la liste des utilisateurs en fonction de l’état
     */
    function getAll(int $etat)
    {
        $sql = "SELECT * FROM users WHERE etat = :etat";

        try {
            $stmt = getConnexion()->prepare($sql);
            $stmt->execute(['etat' => $etat]);
            return $stmt->fetchAll(PDO::FETCH_ASSOC) ?: null;
        } catch (PDOException $e) {
            error_log("Erreur lors de la récupération des utilisateurs (etat=$etat) : " . $e->getMessage());
            return null;
        }
    }

    /**
     * Récupérer un utilisateur via son ID
     */
    function getById(int $id)
    {
        $sql = "SELECT * FROM users WHERE id = :id";

        try {
            $statement = $db->prepare($sql);
            $statement->bindParam(':id', $id, PDO::PARAM_INT);
            $statement->execute();
            return $statement->fetch(PDO::FETCH_ASSOC) ?: null;

        } catch (PDOException $error) {
            error_log("Erreur lors de la recupération de l'utilisateur d'id $id " . $error->getMessage());
            throw $error;
        }
    }

    /**
     * Récupérer un utilisateur via son email
     */
    function getUserByEmail($email)
    {
        $sql = "SELECT * FROM users WHERE email = :email";

        try {
            $statement = $db->prepare($sql);
            $statement->execute(['email' => $email]);
            return $statement->fetch(PDO::FETCH_ASSOC) ?: null;

        } catch (PDOException $error) {
            error_log("Erreur lors de la recupération de l'utilisateur d'email $email " . $error->getMessage());
            throw $error;
        }
    }

    /**
     * Modifier les informations d’un utilisateur
     */
    function edit($id, $nom, $adresse, $telephone, $photo, $email, $role, $updatedBy)
    {
        $sql = "UPDATE users
                SET nom = :nom, adresse = :adresse, telephone = :telephone, photo = :photo,
                email = :email, role = :role, updated_at = NOW(), updated_by=:updated_by WHERE id = :id ";

        try {
            $statement = $db->prepare($sql);
            $statement->execute([
                'nom' => $nom,
                'adresse' => $adresse,
                'telephone' => $telephone,
                'photo' => $photo,
                'email' => $email,
                'role' => $role,
                'updated_by' => $updatedBy,
                'id' => $id
            ]);

            $rowAffected = $statement->rowCount();
            return $rowAffected >= 0; // Retourne vrai même si aucune ligne modifiée
        } catch (PDOException $error) {
            error_log("Erreur lors la modification de l'utilisateur $nom " . $error->getMessage());
            throw $error;
        }
    }

    /**
     * Désactiver un utilisateur (soft delete)
     */
    function desactivate($id, $deletedBy)
    {
        $sql = "UPDATE users SET etat = 0, deleted_at = NOW(), deleted_by = :deleted_by WHERE id = :id";

        try {
            $statement = $db->prepare($sql);
            $statement->execute(['deleted_by' => $deletedBy, 'id' => $id]);
            return $statement->rowCount() > 0;

        } catch (PDOException $error) {
            error_log("Erreur lors de la désactivation d'utilisateur d'id $id " . $error->getMessage());
            throw $error;
        }
    }

    /**
     * Activer un utilisateur
     */
    function activate($id, $updatedBy)
    {
        $sql = "UPDATE users SET etat = 1, updated_at = NOW(), updated_by = :updated_by WHERE id = :id";

        try {
            $statement = $db->prepare($sql);
            $statement->execute(['updated_by' => $updatedBy, 'id' => $id]);
            return $statement->rowCount() > 0;
        } catch (PDOException $error) {
            error_log("Erreur lors de l'activation de l'utilisateur d'id $id " . $error->getMessage());
            throw $error;
        }
    }

    /**
     * Mettre à jour le mot de passe d’un utilisateur
     */
    function updatePassword($userId, $hashedPassword)
    {
        $sql = "UPDATE users SET password = :password, updated_at = NOW(), updated_by = :updated_by WHERE id = :id";

        try {
            $statement = $db->prepare($sql);
            $statement->execute([
                'password' => $hashedPassword,
                'updated_by' => $userId, // Celui qui change est le même que l'utilisateur
                'id' => $userId
            ]);

            return $statement->rowCount() > 0;

        } catch (PDOException $error) {
            error_log("Erreur lors de la modification du mot de passe " . $error->getMessage());
            throw $error;
        }
    }

?>