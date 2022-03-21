<?php
include('./utils/db.php');
include('./fonctions/reservationUse.php');
$idMat = $_GET['id_mat'];
$libelleMat = $_GET['libelle_mat'];
?>
<script>
    document.addEventListener('DOMContentLoaded', function() {
        var calendarEl = document.getElementById('calendar');
        var calendar = new FullCalendar.Calendar(calendarEl, {
                initialView: 'dayGridMonth',
                headerToolbar: {
                    left: 'prev,next today',
                    center: 'title',
                    right: 'dayGridMonth,timeGridWeek,list'
                },
                locale: 'fr',
                buttonText: {
                    today: 'Aujourd\'hui',
                    month: 'Mois',
                    week: 'Semaine',
                    list: 'Liste'
                },
                selectable: true,
                select: function(info) {
                    const title = prompt('Libellé de votre résa :');
                    if (title) {
                        info.title = title;
                        calendar.addEvent({
                            title: title,
                            start: info.start,
                            end: info.end,
                            allDay: true,
                            backgroundColor: 'green',
                            borderColor: 'green'
                        });
                        //AJAX pour sauvegarder l'évènement en BD
                        requestInsert(info);
                    };
                },
                events: <?php echo getEvenementsResa($pdo, $idMat); ?>
        }); calendar.render();
    });

    function requestInsert(info) {
        const xhr = getXMLHttpRequest();
        xhr.onreadystatechange = function() {
            if (xhr.readyState == 4 && xhr.status == 200) {
                $('#msg').fadeIn().delay(2000).fadeOut();
                //console.log($('#msg'));
                console.log(xhr.responseText);
            }
        };

        xhr.open("POST", "request/insertResa.php", true);
        xhr.setRequestHeader("Content-Type", "application/x-www-form-urlencoded");
        //formatage des dates pour convenir à MySQL
        //ajout de 1 sur les mois car les mois commence à 0 en JS
        const dateDeb = info.start;
        const dateDebStr = dateDeb.getFullYear() + "-" + (dateDeb.getMonth() + 1) + "-" + dateDeb.getDate();
        const dateFin = info.end;
        const dateFinStr = dateFin.getFullYear() + "-" + (dateFin.getMonth() + 1) + "-" + dateFin.getDate();
        xhr.send("id_util=<?php echo $_SESSION['id_util'] ?>&libelle_resa=" + info.title + "&id_mat_resa=<?php echo $idMat ?>&date_debut_resa=" + dateDebStr + "&date_fin_resa=" + dateFinStr);
    }
</script>
<p class="lead text-center font-weight-bold">Calendrier de réservation pour <?php echo $libelleMat ?></p>
<div id='calendar' style="width: 50%; margin: 20px auto;"></div>
<div id='msg'>Evènement enregistré</div>