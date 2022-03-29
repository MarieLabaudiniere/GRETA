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
    $stmt->fetch();
}
?>