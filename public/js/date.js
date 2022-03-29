//fonction qui renvoie une chaine de caractères correspondant à la date au format dd/mm/yyyy
function getFormatFr(oDate) {
    let dateStrFr = "";
    //récupération de la date et complétion avec des 0 pour atteindre une longueur de 2
    const dd = oDate.getDate().toString().padStart(2, '0');
    //récupération du mois auquel on ajoute 1 car JS commence au mois O et complétion
    //avec des 0 pour atteindre une longueur de 2
    const mm = String(oDate.getMonth() + 1).padStart(2, '0');
    const yyyy = oDate.getFullYear();
    dateStrFr =  dd + "/" + mm  + "/" + yyyy ;
    return dateStrFr;
}

//fonction qui renvoie une chaine de caractère corrsespondant au format attendant pour MySQL
function getFormatMySQL(oDate) {
    return oDate.getFullYear() + "-" + (oDate.getMonth() + 1) + "-" + oDate.getDate();
}