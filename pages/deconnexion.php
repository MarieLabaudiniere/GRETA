<?php
    //déconnexion de l'utilisateur
    //suppression de toutes les valeurs mis en session
    session_destroy();
    header('Location: index.php?page=authentif');
    die();
?>