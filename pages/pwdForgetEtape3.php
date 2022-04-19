<p class="lead text-center font-weight-bold">Mot de passe oublié</p>
<?php
//chargement des paramètres de la BD
include('./utils/db.php');
//chargement des fonctions liées à la manipulation des données utilisateur
include('./fonctions/utilisateurUse.php');
if (isset($_POST['recovery-submit'])) { //CAS où l'utilisateur valid son changement de mot de passe
    reinitPwd($pdo, $_POST);
    header('Location: index.php?page=authentif');
    die();
} else if (isset($_GET['token'])) { //CAS où l'utilisateur à cliquer sur le lien du message de l'email
    $infosToken = getInfosToken($pdo, $_GET['token']);
    if (empty($infosToken)) { //pas de jeton trouvé en BD
        echo "votre jeton n'existe pas, veuillez demander de nouveau une réinitialisation du mot de passe.";
    } else { //le jeton existe
        //contrôle de la validité du jeton
        $timeToken = strtotime(date($infosToken['pwd_change_date']));
        $timeCourant = time();
        $delais = 10800; //3 heures
        if ($timeCourant - $timeToken > $delais) { //le délais est dépassé
            echo "le délais pour changer votre mot de passe est dépassé. Veuillez refaire la demande.";
        } else { //l'utilisateur peut saisir un nouveau mot de passe car jeton valide et délas non dépassé
            echo '<p class="ml-5">Réinitialisation de votre mot de passe :</p>';
            echo '<div class="container mt-3">
<div class="row justify-content-center">
<div class="col-12">
<form id="recovery-form" action="index.php?page=pwdForget" method="POST">
<div class="form-group col-6">
<input type="text" onchange="verifUser(this, \'' . $infosToken['ident_util'] . '\')" name="username" id="username" class="form-control" placeholder="identifiant" value="" required>
</div>
<div class="form-group col-6">
<input type="password" name="pwd" id="pwd" class="form-control" placeholder="pwd" value="" required>
</div>
<div class="form-group col-6">
<input type="password" name="pwdConf" id="pwdConf" class="form-control" placeholder="pwd" value="" required>
</div>
<div class="form-group col-2">
<input type="submit" name="recovery-submit" id="recovery-submit" class="form-control btn-secondary" value="Changement pwd">
</div>
</form></div></div>';
        }
    }
} else { //CAS où l'utilisateur débute sa demande de réinitialisation de mot de passe
    $user = htmlspecialchars(@$_GET['username']);
    if (strlen($user) == 0) { //l'utilisateur n'a pas saisi son identifiant
        echo '<p class="ml-5">Vous devez saisir votre identifiant de connexion :</p>';
        echo '<div class="container mt-3">
<div class="row justify-content-center">
<div class="col-12">
<form action="index.php" method="GET">
<input type="hidden" name="page" value="pwdForget">
<div class="form-group col-6">
<input type="text" name="username" id="username" class="form-control" placeholder="identifiant" value="" required>
</div>
<div class="form-group col-2">
<input type="submit" name="login-submit" id="login-submit" class="form-control btn-secondary" value="Envoi mail">
</div>
</form></div></div>';
    } else {
        $dest = getMail($pdo, $user);
        $sujet = "Modification de mot de passe";
        $headers[] = 'MIME-Version: 1.0';
        $headers[] = 'Content-type: text/html; charset=UTF-8';
        $headers[] = 'From: marielabaudiniere@gmail.com';
        //génération d'une chaine de façon aléatoire.
        $token = openssl_random_pseudo_bytes(16);
        //convertion de la chaine en representation hexadecimal.
        $token = bin2hex($token);
        $message = '<h1>Réinitialisation de votre mot de passe</h1>
    <p>pour réinitialiser votre mot de passe, veuillez suivre ce lien : 
    <a href="localhost/greta/index.php?page=pwdForget&token=' . $token . '">lien</a></p>fin message';
        if (mail($dest, $sujet, utf8_decode($message), implode("\r\n", $headers))) {
            echo "Un email vous a été envoyé sur votre boite mail, veuillez le consulter.";
            //enregistrement en BD du token et de la date
            updateToken($pdo, $token, $user);
        } else {
            echo "Échec de l'envoi de l'email. Veuillez vous adresser à l'administrateur.";
        }
    }
} ?>
<script src="public/js/pwdForget.js"></script>