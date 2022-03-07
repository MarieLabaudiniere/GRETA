<?php
include('./utils/db.php');
include('./fonctions/reservationUse.php');
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
                if(title) {
                    calendar.addEvent({
                        title: title,
                        start: info.start,
                        end: info.end,
                        backgroundColor: 'green',
                        borderColor: 'green'
                    });
                };
            },
            events: <?php echo getEvenementsResa($pdo, $_GET['id_mat']); ?>
        });
        calendar.render();
    });
</script>

<div id='calendar' style="width: 50%; margin: 20px auto;"></div>