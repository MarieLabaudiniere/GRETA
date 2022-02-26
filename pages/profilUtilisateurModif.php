<?php
    //modification en BD d'une information utilisateur
    //chargement des paramètres de la BD
    include('./utils/db.php');
    //récupération des valeurs des champs de formulaire
    $username = htmlspecialchars($_POST['username']);
    $prenom = htmlspecialchars($_POST['firstname']);
    $nom = htmlspecialchars($_POST['lastname']);
    //id de l'utilisateur (clé primaire en BD)
    $id = $_SESSION['id_util'];

    try {
        $stmt = $pdo->prepare("UPDATE utilisateurs SET ident_util=?, nom_util=?, prenom_util=? WHERE id_util=?");
        $stmt->execute([$username, $nom, $prenom, $id]);
        $stmt->fetch();
        //MAJ des donner dans la session
        $_SESSION["prenom"] = $prenom;
        $_SESSION["nom"] = $nom;
        $_SESSION["ident"] = $username;
        echo "Modification de votre profil réussi";
        //header('location: index.php?page=home');
    } catch(PDOException $e){
        echo "Erreur  : " . $e->getMessage();
    }
?>