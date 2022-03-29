function initialisationCalendar(idUtilP, idMatP) {
    document.addEventListener('DOMContentLoaded', function () {
        const calendarEl = document.getElementById('calendrier');
        const calendar = new FullCalendar.Calendar(calendarEl, {
            initialView: 'dayGridMonth',
            locale: 'fr',
            headerToolbar: {
                left: 'prev,next today',
                center: 'title',
                right: 'dayGridMonth,timeGridWeek,list'
            },
            buttonText: {
                today: 'Aujourd\'hui',
                month: 'Mois',
                week: 'Semaine',
                list: 'Liste'
            },
            weekends: false,
            weekNumbers: true,
            selectable: true,
            events: {
                url: 'request/listEventsCalendar.php',
                method: 'GET',
                extraParams: {
                    idUtil: idUtilP,
                    idMat: idMatP
                },
                failure: function () {
                    alert('there was an error while fetching events!');
                }
            },
            select: function (info) {
                //vérifier qu'il n'existe pas déjà un évènement.
                if (!isAllreadyExist(calendar, info)) {
                    const titleSaisi = prompt('Libellé de votre résa :');
                    if (titleSaisi) {
                        info.title = titleSaisi;
                        const event = calendar.addEvent({ title: titleSaisi, start: info.start, end: info.end, allDay: true, backgroundColor: 'green', borderColor: 'green' });
                        //ajout de propriétés non génériques
                        event.setExtendedProp('idUtil', idUtilP);
                        event.setExtendedProp('idMat', idMatP);
                        //requête avec l'AJAX pour sauvegarder l'évènement en BD
                        requestInsert(event, calendar);
                    }
                } else {
                    alert('il existe déjà une réservation sur la période demandée');
                }
            },
            eventClick: function (info) {
                const eventObj = info.event;
                if (eventObj.extendedProps.userId == idUtilP) {
                    //ouverture du modal uniquement si l'évènement a été crée par l'utilisateur
                    $('#modalTitle').html(eventObj.title);
                    let contenu = "Date début : " + getFormatFr(eventObj.start) + "<br>"
                        + "Date fin : " + getFormatFr(eventObj.end) + "<br><input type=\"text\" id=\"labelModif\" value=\"" + eventObj.title + "\">";
                    $('#modalBody').html(contenu);
                    $('#calendarModal').modal();
                    //suppression des évènements click sur les liens
                    $("#suppr").off("click");
                    $("#modif").off("click");
                    //création des évènements click sur les liens
                    $("#suppr").on("click", function () {
                        requestDelete(info);
                        $('#calendarModal').modal('hide');
                    });
                    $("#modif").on("click", function () {
                        //avec l'ajax faire la modification du libellé de la résa en BD
                        requestUpdate(info, $("#labelModif").val(), calendar);
                        $('#calendarModal').modal('hide');
                    });
                } else {
                    alert("vous ne pouvez pas modifier cette réservation car eventObj.userId=" + eventObj.userId + " et idUtil=" + idUtilP);
                }
            }
            // events: [
            //     {
            //         title: 'formation GRETA',
            //         start: '2022-04-11',
            //         end: '2022-04-22'
            //     },
            //     {
            //         title: 'Valentin',
            //         start: '2022-04-19',
            //         end: '2022-04-20',
            //         backgroundColor: 'yellow',
            //         borderColor: 'yellow',
            //         textColor: 'black'
            //     },
            //     {
            //         title: 'Repas',
            //         start: '2022-04-12 12:00',
            //         end: '2022-04-12 13:00',
            //         allDay: true,
            //         backgroundColor: 'red',
            //         borderColor: 'red',
            //         textColor: 'black'
            //     },
            //     {
            //         title: 'Projet',
            //         start: '2022-04-12 13:30',
            //         end: '2022-04-12 14:30',
            //         allDay: true,
            //         backgroundColor: 'green',
            //         borderColor: 'green',
            //         textColor: 'black'
            //     }

            // ]
        });
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
            if ((dateStart >= dateStartEvent && dateEnd <= dateEndEvent) || //chevauchement avec une résa antiérieur
                (dateStart >= dateStartEvent && dateStart < dateEndEvent) || //inclusion de la période de la nouvelle résa dans une autre période de résa.
                (dateEnd > dateStartEvent && dateEnd <= dateEndEvent)) { //chevauchement avec une résa postérieur
                isExist = true;
            }
        }
    });
    return isExist;
}
//génération de la requête HTTP qui va permettre d'insérer une réservation en BD
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
//génération de la requête qui va permettre de supprimer une réservation du calendrier
function requestUpdate(info, libelleP, calendarP) {
    const xhr = getXMLHttpRequest();
    xhr.onreadystatechange = function () {
        if (xhr.readyState == 4 && xhr.status == 200) {
            //suppression de l'évènement du calendrier
            info.event.title = libelleP;
            //rafraichissement des évènements du calendrier (récupération en BD avec l'url spécifiée dans la propriété url de events)
            calendarP.refetchEvents();
            //affichage d'une div montrant à l'utilisateur que sa réservation a bien été supprimé pendant 2 secondes.
            $('#msg').text("Enregistrement modifié").fadeIn().delay(2000).fadeOut();
            //affichage de la réponse du serveur pour le débuggage
            console.log(xhr.responseText);
            console.log("update " + info.event.id + " avec le libellé " + libelleP);
        }
    };

    xhr.open("POST", "request/updateResa.php", true);
    xhr.setRequestHeader("Content-Type", "application/x-www-form-urlencoded");
    xhr.send("id_resa=" + info.event.id + "&libelle_resa=" + libelleP);
}
