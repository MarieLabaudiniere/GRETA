<?php
//création de l'utilisateur
function createUser($pdoP, $values) {
    //TODO avant la création en BD, vérifier que le userName est unique
    //unicité en BD + select de vérification pour pouvoir afficher
    //un message à l'utilisateur : "identifiant existant."
    $username = htmlspecialchars($values['username']);
    $mail = htmlspecialchars($values['email']);
    $pwd = htmlspecialchars($values['password']);
    $prenom = htmlspecialchars($values['firstname']);
    $nom = htmlspecialchars($values['lastname']);
    $pwdHash = password_hash($pwd, PASSWORD_DEFAULT);
    $stmt = $pdoP->prepare("INSERT INTO utilisateurs (ident_util, pwd_util, mail_util, prenom_util, nom_util) VALUES (?,?,?,?,UPPER(?))");
    $stmt->execute([$username, $pwdHash, $mail, $prenom, $nom]);
}
//fonction qui met à jour pour un identifiant donné la date du jeton et la valeur du jeton
//pour une réinitialisation du mot de passe
function updateToken($pdoP, $tokenP, $userNameP) {
    //ATTENTION l'identifiant doit être unique
    $stmt = $pdoP->prepare("UPDATE utilisateurs SET pwd_change_date=NOW(), pwd_change_token=? WHERE ident_util=?");
    $stmt->execute([$tokenP, $userNameP]);
}
//fonction qui renvoie les infos spécifiques à un jeton passé en paramètre
function getInfosToken($pdoP, $tokenP){
    //ATTENTION l'identifiant doit être unique
    $stmt = $pdoP->prepare("SELECT pwd_change_date, ident_util FROM utilisateurs WHERE pwd_change_token=?");
    $stmt->execute([$tokenP]);
    return $stmt->fetch(PDO::FETCH_ASSOC);
}
//fonction qui modifie le mot de passe et enlève les infos concernant le token
function reinitPwd($pdoP, $values) {
    //ATTENTION l'identifiant doit être unique
    $username = htmlspecialchars($values['username']);
    $pwd = htmlspecialchars($values['pwd']);
    $pwdHash = password_hash($pwd, PASSWORD_DEFAULT);
    $stmt = $pdoP->prepare("UPDATE utilisateurs SET pwd_change_date=NULL, pwd_change_token=NULL, pwd_util=?   WHERE ident_util=?");
    $stmt->execute([$pwdHash, $username]);
}
//fonction qui renvoie l'id de l'utilisateur et son email
function getMail($pdoP, $userNameP){
    $stmt = $pdoP->prepare("SELECT mail_util from utilisateurs WHERE ident_util=?");
    $stmt->execute([$userNameP]);
    $result = $stmt->fetch(PDO::FETCH_ASSOC);
    return $result['mail_util'];
}
