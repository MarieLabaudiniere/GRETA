function initialisationCalendar(idUtil, idMat) {
    document.addEventListener('DOMContentLoaded', function () {
        const calendarEl = document.getElementById('calendrier');
        const calendar = new FullCalendar.Calendar(calendarEl, {
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
            weekends: false,
            weekNumbers: true,
            selectable: true,
            editable: false,
            select: function (info) {
                //vérifier qu'il n'existe pas déjà un évènement.
                if (!isAllreadyExist(calendar, info)) {
                    const titleSaisi = prompt('Libellé de votre résa :');
                    if (titleSaisi) {
                        info.title = titleSaisi;
                        const event = calendar.addEvent({ title: titleSaisi, start: info.start, end: info.end, allDay: true, backgroundColor: 'green', borderColor: 'green' });
                        //ajout de propriétés non génériques
                        event.setExtendedProp('idUtil', idUtil);
                        event.setExtendedProp('idMat', idMat);
                        //requête avec l'AJAX pour sauvegarder l'évènement en BD
                        requestInsert(event, calendar);
                    }
                } else {
                    alert('il existe déjà une réservation sur la période demandée');
                }
            },
            events: {
                url: 'request/listEventsCalendar.php',
                method: 'POST',
                extraParams: {
                    idUtil: idUtil,
                    idMat: idMat
                },
                failure: function () {
                    alert('there was an error while fetching events!');
                }
            },
            eventClick: function (info) {
                const eventObj = info.event;
                if (eventObj.userId == idUtil) {
                    //ouverture du modal uniquement si l'évènement a été crée par l'utilisateur
                    $('#modalTitle').html(eventObj.title);
                    let contenu = "Date début : " + getFormatFr(eventObj.start) + "<br>"
                        + "Date fin : " + getFormatFr(eventObj.end) + "<br><input type=\"text\" id=\"labelModif\" value=\"" + eventObj.title + "\">";
                    $('#modalBody').html(contenu);
                    $('#calendarModal').modal();
                    $("#suppr").click(function () {
                        requestDelete(info);
                        $('#calendarModal').modal('hide');
                    });
                    $("#modif").change(function () {
                        //avec l'ajax faire la modification du libellé de la résa en BD

                    });
                } else {
                    alert("vous ne pouvez pas modifier cette réservation");
                }
            }
        });
        //démarrage du calendrier
        calendar.render();
    });
}
//fonction qui renvoie true s'il existe un autre évènement qui se trouve sur une partie
//de la période de l'évènement que l'on veut créé.
function isAllreadyExist(calendarP, infoP) {
    const events = calendarP.getEvents();
    const dateStart = infoP.start;
    const dateEnd = infoP.end;
    let isExist = false;
    //parcours de chaque élément de la liste de tous les éléments existants.
    events.forEach(function (event) {
        if (!isExist) {
            const dateStartEvent = event.start;//date début de l'évènement parcouru
            const dateEndEvent = event.end;//date fin de l'évènement parcouru
            //console.log(dateStartEvent);
            //console.log(dateEndEvent);
            if ((dateStart >= dateStartEvent && dateEnd <= dateEndEvent) || //chevauchement avec une résa antiérieur
                (dateStart >= dateStartEvent && dateStart < dateEndEvent) || //inclusion de la période de la nouvelle résa dans une autre période de résa.
                (dateEnd > dateStartEvent && dateEnd <= dateEndEvent)) { //chevauchement avec une résa postérieur
                isExist = true;
            }
        }
    });
    return isExist;
}
//génération de la requête qui va permettre d'insérer une réservation en BD
function requestInsert(eventP, calendarP) {
    const dateDebStr = getFormatMySQL(eventP.start);
    const dateFinStr = getFormatMySQL(eventP.end);
    const paras = "id_util=" + eventP.extendedProps.idUtil + "&libelle_resa="
        + eventP.title + "&id_mat_resa=" + eventP.extendedProps.idMat
        + "&date_debut_resa=" + dateDebStr + "&date_fin_resa=" + dateFinStr;
    const xhr = getXMLHttpRequest();
    xhr.onreadystatechange = function () {
        if (xhr.readyState == 4 && xhr.status == 200) {
            //affichage d'une div montrant à l'utilisateur que sa réservation a bien été prise en compte pendant 2 secondes.
            $('#msg').text("Evènement enregistré").fadeIn().delay(2000).fadeOut();
            //affichage de l'id de la résa créé
            console.log("création de la résa : id=" + xhr.responseText);
            eventP.id = xhr.responseText;
            //BUG fullCalndar doublons créés si on ne le supprime pas.
            eventP.remove();
            //rafraichissement des évènements du calendrier (récupération en BD avec l'url spécifiée dans la propriété url de events)
            calendarP.refetchEvents();
        }
    };
    xhr.open("POST", "request/insertResa.php", true);
    xhr.setRequestHeader("Content-Type", "application/x-www-form-urlencoded");
    xhr.send(paras);
}
//génération de la requête qui va permettre de supprimer une réservation du calendrier
function requestDelete(info) {
    const xhr = getXMLHttpRequest();
    xhr.onreadystatechange = function () {
        if (xhr.readyState == 4 && xhr.status == 200) {
            //suppression de l'évènement du calendrier
            info.event.remove();
            //affichage d'une div montrant à l'utilisateur que sa réservation a bien été supprimé pendant 2 secondes.
            $('#msg').text("Enregistrement supprimé").fadeIn().delay(2000).fadeOut();
            //affichage de la réponse du serveur pour le débuggage
            console.log(xhr.responseText);
            console.log("delete id_resa " + info.event.id);
        }
    };

    xhr.open("POST", "request/deleteResa.php", true);
    xhr.setRequestHeader("Content-Type", "application/x-www-form-urlencoded");
    xhr.send("id_resa=" + info.event.id);
}