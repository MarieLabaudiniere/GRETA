<?php
//vérification de l'existance de l'identifiant et du mot de passe.
//chargement des paramètres de la BD et connexion
include('./utils/db.php');
$redirectionAuthentif = 'Location: index.php?page=authentif';
$username = htmlspecialchars($_POST['username']);
$pwd = $_POST['password'];
//réinitialisation du nb de tentatives
unset($_SESSION["timeNextTentative"]);

$stmt = $pdo->prepare("SELECT date_connexion, nbconnexion_util, id_util, pwd_util, prenom_util, nom_util, ident_util, is_admin_util FROM utilisateurs WHERE ident_util=?");
$stmt->execute([$username]);
$result = $stmt->fetch(PDO::FETCH_ASSOC);

if ($result) {
    //il y a un résultat donc l'utilisateur existe, maintenant vérification du mot de passe
    $pwdHashBD = $result['pwd_util'];

    //récupération des données permettant de lutter contre les attaques
    // de force brute
    $nbConnexionFailed = $result['nbconnexion_util'];
    $dateConnexionFailed = $result['date_connexion'];
    $delaisAttente = 350;
    $timeCourant = time();
    $timeBD;

    is_null($dateConnexionFailed) ? $timeBD = time() : $timeBD = strtotime(date($dateConnexionFailed));
    
    
    if ($nbConnexionFailed > 3 && $timeCourant - $timeBD < $delaisAttente) {
        //si nb tentative >=4 alors bloquer QUOI qu'il arrive
        //la connexion
        $_SESSION["timeNextTentative"] = $timeBD + $delaisAttente;
        header($redirectionAuthentif);
        die();
    } else {
        if (password_verify($pwd, $pwdHashBD)) {
            //CAS où le mot de passe est correcte et que le temps d'attente ne bloque pas
            //l'authentification
            //le mot de passe en BD(qui a été crypté en PHP avant insertion) correspond au mot de passe saisi par l'utilisateur

            $_SESSION["etatConnexion"] = "1";
            //toutes les informations concernant l'utilisateur pourront être accessible durant la session
            $_SESSION["prenom"] = $result['prenom_util'];
            $_SESSION["nom"] = $result['nom_util'];
            $_SESSION["ident"] = $result['ident_util'];
            $_SESSION["id_util"] = $result['id_util'];
            $_SESSION["is_admin"] = $result['is_admin_util'];
            if (!is_null($nbConnexionFailed)) {
                //mise à jour en BD des données gerant les attaques de force brute
                majSessionNews($pdo, null, null, $username);
            }
            //redirection vers la page d'accueil
            header('Location: index.php?page=home');
            die();
        } else { //CAS où le mot de passe est incorrecte OU le délais bloque l'accès
            //ce paramètre stocké en session permettra de savoir que la connexion a échoué
            //et donc d'afficher un message d'echec sur la page d'authentification
            $_SESSION["etatConnexion"] = "0";

            //nb max de tentatives de connexion = 3

            if ($nbConnexionFailed < 3) {
                majSessionNews($pdo, $nbConnexionFailed + 1, time(), $username);
            } else {
                //15 minutes avant renouvellement des tentatives

                if ($timeCourant - $timeBD < $delaisAttente) {
                    //si nb tentative >=4 alors bloquer
                    //la connexion
                    $_SESSION["timeNextTentative"] = $timeBD + $delaisAttente;
                } else {
                    //le délais est passé
                    //renouvellement des tentatives
                    majSessionNews($pdo, 1, time(), $username);
                }
            }

            header($redirectionAuthentif);
            die();
        }
    }
} else {
    // l'identifiant n'existe pas
    $_SESSION["etatConnexion"] = "0";
    header($redirectionAuthentif);
    die();
}

function majSessionNews($pdoP, $nbConnexionFailedP, $timeLastConnexionFailedP, $userP)
{
    try {
        $valDate = $timeLastConnexionFailedP;
        if (!is_null($timeLastConnexionFailedP)) {
            //si la date n'est pas null alors on gère correctement son format
            $dateTime = new DateTime();
            $dateTime->setTimestamp($timeLastConnexionFailedP);
            $valDate = $dateTime->format('Y-m-d H:i');
        }

        $stmtMAJ = $pdoP->prepare("UPDATE utilisateurs SET nbconnexion_util = ?, date_connexion=? WHERE ident_util=?");
        $stmtMAJ->execute([$nbConnexionFailedP, $valDate, $userP]);
        $stmtMAJ->fetch(PDO::FETCH_ASSOC);
    } catch (PDOException $e) {
        echo $e->getMessage();
    }
}
