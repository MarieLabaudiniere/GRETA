<?php 
 $whitelist = array('connexion', 'authentif', 'inscription', 'pwdForget');
if(@$_SESSION["etatConnexion"] == 1) {
    //la connexion a été établie
    array_push($whitelist, 'home', 'deconnexion', 'reservation', 'reservationsUtilisateur',
    'profilUtilisateur', 'profilUtilisateurModif', 'reservationCalendar');
}
//déclarer des accès specifique admin
if(@$_SESSION["is_admin"] == 1) {
    array_push($whitelist, 'tableauDeBord');    
}
?>