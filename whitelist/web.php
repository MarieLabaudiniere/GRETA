<?php 
 $whitelist = array('connexion', 'authentif', 'inscription', 'register');
if(isset($_SESSION["etatConnexion"]) && $_SESSION["etatConnexion"] == 1) {
    //la connexion a été établie
    array_push($whitelist, 'home', 'deconnexion', 'reservation', 
    'profilUtilisateur', 'profilUtilisateurModif', 'reservationCalendar');
}
//déclarer des accès specifique admin
if(isset($_SESSION["is_admin"]) && $_SESSION["is_admin"] == 1) {
    array_push($whitelist, 'tableauDeBord');    
}
?>