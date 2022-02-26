<?php
    //enregistrement en BD du nouvel utilisateur
    //chargement des paramètres de la BD
    include('./utils/db.php');
    //création de l'utilisateur
    $username = htmlspecialchars($_POST['username']);
    $mail = htmlspecialchars($_POST['email']);
    $pwd = htmlspecialchars($_POST['password']);
    $prenom = htmlspecialchars($_POST['firstname']);
    $nom = htmlspecialchars($_POST['lastname']);
    $pwdHash = password_hash($pwd, PASSWORD_DEFAULT);

    try {
        $stmt = $pdo->prepare("INSERT INTO utilisateurs (ident_util, pwd_util, mail_util, prenom_util, nom_util) VALUES (?,?,?,?,UPPER(?))");
        $stmt->execute([$username, $pwdHash, $mail, $prenom, $nom]);
        $stmt->fetch();
        header('Location: index.php?page=home');
        die();
    } catch(PDOException $e){
        echo "Erreur  : " . $e->getMessage();
    }
?>
