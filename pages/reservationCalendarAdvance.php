<?php
include('./utils/db.php');
include('./fonctions/reservationUse.php');
$idMat = $_GET['id_mat'];
$libelleMat = $_GET['libelle_mat'];
$userId = $_SESSION['id_util'];
?>
<script>
    initialisationCalendar(<?php echo $userId ?>, <?php echo $idMat ?>);
</script>
<p class="lead text-center font-weight-bold">Calendrier de réservation pour <?php echo $libelleMat ?></p>
<!-- div qui permet d'afficher le calendrier -->
<div id='calendrier'"></div>
<!--div qui apparait après l'enregistrement de la résa en BD ou de sa suppression-->
<div id='msg'></div>

<!--MODAL qui s'affiche lorsque l'on clique sur un évènement-->
<div id="calendarModal" class="modal fade">
<div class="modal-dialog">
    <div class="modal-content">
        <div class="modal-header">
        <h4 id="modalTitle" class="modal-title"></h4>
            <button type="button" class="close" data-dismiss="modal"><span aria-hidden="true">×</span> <span class="sr-only">close</span></button>
            <h4 id="modalTitle" class="modal-title"></h4>
        </div>
        <div id="modalBody" class="modal-body"> </div>
        <div class="modal-footer">
            <a href="#" id="modif"><img src="./public/medias/pen.svg"></a>
            <a href="#" id="suppr"><img src="./public/medias/trash.svg"></a>
            <button type="button" class="btn btn-default" data-dismiss="modal">Close</button>
        </div>
    </div>
</div>
</div>
