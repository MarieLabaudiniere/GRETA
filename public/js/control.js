function controlPeriode(dateDebutP, dateFinP) {
    const elemDateDebut = document.getElementById(dateDebutP);
    const elemDateFin = document.getElementById(dateFinP);
    const dateDebut = new Date(elemDateDebut.value);
    const dateFin =  new Date(elemDateFin.value);
    if(dateDebut > dateFin) {
        elemDateDebut.validity.valid = "false";
        elemDateDebut.setCustomValidity("La date de début doit être inférieur à la date de fin");
    } else {
        elemDateDebut.validity.valid = "true";
        elemDateDebut.setCustomValidity("");
    }
}