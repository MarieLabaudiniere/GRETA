<?php include('./fonctions/reservationUse.php');
$idMat = $_GET['id_mat'];
$libelleMat = $_GET['libelle_mat'];
$userId = $_SESSION['id_util'];
?>
<p class="lead text-center font-weight-bold">Calendrier de réservation pour 
    <?php echo $libelleMat ?></p>

<div id='calendrier'></div>