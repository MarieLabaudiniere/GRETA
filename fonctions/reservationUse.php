<?php
//fonction qui renvoie la liste des réservations de l'utilisateur connecté
//qui auront lieu après la date du jour sous forme de tableau associatif
//nom de la colonne et valeur en BD
function getListFuturResa($pdoP)
{
    $stmt = $pdoP->prepare("SELECT CONCAT(reservations.ID_MAT_RESA, ' : ', materiels.LIBELLE_MAT) AS libMat,
        CONCAT(materiels.ID_MAT, ' : ', marques.LIBELLE_MARQUE) AS libMarq,
        reservations.DATE_DEBUT_RESA, reservations.DATE_FIN_RESA
        FROM reservations
        INNER JOIN materiels ON reservations.ID_MAT_RESA = materiels.ID_MAT
        INNER JOIN marques ON materiels.ID_MARQUE = marques.ID_MARQUE
        WHERE reservations.id_util=? AND reservations.DATE_DEBUT_RESA > NOW()");
    $stmt->execute([$_SESSION['id_util']]);
    return $stmt->fetchAll(PDO::FETCH_ASSOC);
}

//fonction qui renvoie la liste des réservations de l'utilisateur connecté
//qui auront lieu après la date du jour sous forme de tableau associatif
//nom de la colonne et valeur en BD
function getListResaEnCours($pdoP)
{
    $stmt = $pdoP->prepare("SELECT CONCAT(reservations.ID_MAT_RESA, ' : ', materiels.LIBELLE_MAT) AS libMat,
        CONCAT(materiels.ID_MAT, ' : ', marques.LIBELLE_MARQUE) AS libMarq,
        reservations.DATE_DEBUT_RESA, reservations.DATE_FIN_RESA
        FROM reservations
        INNER JOIN materiels ON reservations.ID_MAT_RESA = materiels.ID_MAT
        INNER JOIN marques ON materiels.ID_MARQUE = marques.ID_MARQUE
        WHERE reservations.id_util=? AND NOW() BETWEEN reservations.DATE_DEBUT_RESA  AND reservations.DATE_FIN_RESA");
    $stmt->execute([$_SESSION['id_util']]);
    return $stmt->fetchAll(PDO::FETCH_ASSOC);
}

//liste du matériels disponibles à la date du jour
//avec recherche rapide sur le libellé
function getListMaterielDispoFast($pdoP, $val)
{
    $stmt = $pdoP->prepare("SELECT materiels.ID_MAT, materiels.LIBELLE_MAT FROM materiels 
    WHERE materiels.ID_MAT NOT IN (select reservations.ID_MAT_RESA from reservations 
    where NOW() BETWEEN reservations.DATE_DEBUT_RESA AND reservations.DATE_FIN_RESA) 
    AND materiels.LIBELLE_MAT LIKE ?");
    $stmt->execute(['%' . $val . '%']);
    return $stmt->fetchAll(PDO::FETCH_ASSOC);
}

//fonction qui permet de vérifier si un matériel est disponible sur une période donnée
function isDispo($pdoP, $dateDebut, $dateFin, $idMat)
{
    $results = getListResa($pdoP, $dateDebut, $dateFin, $idMat);
    return count($results) == 0; //retourne vrai si aucune réservation trouvé
}

//retourne la liste des réservation pour un matériel sur une période donnée
function getListResa($pdoP, $dateDebut, $dateFin, $idMat)
{
    $paraSQL = [
        $idMat,
        $dateDebut, $dateFin,
        $dateDebut, $dateFin,
        $dateDebut, $dateFin
    ];
    $stmt = $pdoP->prepare("SELECT reservations.ID_MAT_RESA from reservations 
    where reservations.ID_MAT_RESA = ? AND (reservations.DATE_DEBUT_RESA BETWEEN ? AND ? 
    OR reservations.DATE_FIN_RESA BETWEEN ? AND ?
    OR DATE(?) BETWEEN reservations.DATE_DEBUT_RESA AND reservations.DATE_FIN_RESA 
    OR DATE(?) BETWEEN reservations.DATE_DEBUT_RESA AND reservations.DATE_FIN_RESA))");
    $stmt->execute($paraSQL);
    return $stmt->fetchAll(PDO::FETCH_ASSOC);
}

//liste du matériels disponibles, c.a.d non réservé à la période demandé
//avec les valeurs saisies dans le formulaire de recherche
function getListMaterielDispo($pdoP, $vals)
{
    $paraSQL = [
        $vals['date_debut_resa'], $vals['date_fin_resa'],
        $vals['date_debut_resa'], $vals['date_fin_resa'],
        $vals['date_debut_resa'], $vals['date_fin_resa']
    ];
    $requeteSQL = "SELECT materiels.ID_MAT, materiels.LIBELLE_MAT FROM materiels 
    WHERE materiels.ID_MAT NOT IN (select reservations.ID_MAT_RESA from reservations 
    where reservations.DATE_DEBUT_RESA BETWEEN ? AND ? 
    OR reservations.DATE_FIN_RESA BETWEEN ? AND ?
    OR DATE(?) BETWEEN reservations.DATE_DEBUT_RESA AND reservations.DATE_FIN_RESA 
    OR DATE(?) BETWEEN reservations.DATE_DEBUT_RESA AND reservations.DATE_FIN_RESA)";
    if ($vals['marque'] != "") { //si l'utilisateur a filtré sa recherche sur la marque
        $requeteSQL = $requeteSQL . " AND materiels.ID_MARQUE=?";
        array_push($paraSQL, $vals['marque']);
    }
    if ($vals['libelle_mat'] != "") { //si l'utilisateur a filtré sur le libellé
        $requeteSQL = $requeteSQL .  " AND materiels.LIBELLE_MAT LIKE ?";
        array_push($paraSQL, '%' . $vals['libelle_mat'] . '%');
    }
    $stmt = $pdoP->prepare($requeteSQL);
    $stmt->execute($paraSQL);
    return $stmt->fetchAll(PDO::FETCH_ASSOC);
}

//fonction qui crée un enregistrement en BD
function createResa($pdoP, $vals)
{
    //ATTENTION AVANT de créer en BD vérifier que la période choisie est toujours
    //disponible.
    //pas de capture d'erreur pour qu'elle puisse remonter
    $idUtil = (isset($vals['id_util'])) ? $vals['id_util'] : $_SESSION['id_util'];
    $stmt = $pdoP->prepare("INSERT INTO reservations(LIBELLE_RESA, ID_UTIL, ID_MAT_RESA, DATE_RESA, DATE_DEBUT_RESA, DATE_FIN_RESA)
        VALUES (?, ?, ?, NOW(), ?, ?)");
    $stmt->execute([$vals['libelle_resa'], $idUtil, $vals['id_mat_resa'], $vals['date_debut_resa'], $vals['date_fin_resa']]);
    return $pdoP->lastInsertId();
}

function deleteResa($pdoP, $idResa)
{
    //pas de capture d'erreur pour qu'elle puisse remonter
    $stmt = $pdoP->prepare("DELETE FROM reservations WHERE ID_RESA=?");
    $stmt->execute([$idResa]);
}

function updateResa($pdoP, $libelleResa, $idResa)
{
    //pas de capture d'erreur pour qu'elle puisse remonter
    $stmt = $pdoP->prepare("UPDATE reservations SET LIBELLE_RESA = ? WHERE ID_RESA=?");
    $stmt->execute([$libelleResa, $idResa]);
}

//fonction permettant de générer les événements du calendrier pour les réservations
//d'un matériel dont l'id est passé en argument
function getEvenementsResa($pdoP, $idMat, $debut, $fin, $idUtil)
{
    try {
        $stmt = $pdoP->prepare("SELECT DISTINCT LIBELLE_RESA, ID_UTIL,ID_RESA, DATE_DEBUT_RESA, DATE_FIN_RESA from reservations 
    where ID_MAT_RESA=? AND DATE_DEBUT_RESA BETWEEN ? AND ?");
        $stmt->execute([$idMat, $debut, $fin]);
        $results = $stmt->fetchAll(PDO::FETCH_ASSOC);
        $reponse = array();
        foreach ($results as $result) {
            $event = array();
            $start = strtotime($result['DATE_DEBUT_RESA']) * 1000;
            $end = strtotime($result['DATE_FIN_RESA']) * 1000;
            $idMat = $result['ID_RESA'];
            $title = "Réservé";
            $color = "red";
            $editable = "false"; //va permettre la modification ou suppression de l'évènement
            if ($result['ID_UTIL'] == $idUtil) {
                //la réservation a été faite par l'utilisateur connecté
                //alors on affiche le libellé qu'il a saisi lors de sa résa.
                $title = $result['LIBELLE_RESA'];
                if (is_null($title)) {
                    $title = "ma résa";
                }
                $color = "green";
                $editable = "true";
            }
            //DEBUT définition des propriétés générales de l'évènement
            $event['id'] = $idMat;
            $event['title'] = $title;
            $event['start'] = $start;
            $event['end'] = $end;
            $event['editable'] = $editable;
            $event['backgroundColor'] = $color;
            $event['borderColor'] = $color;
            $event['allDay'] = 'true';
            //FIN définition des propriétés générales de l'évènement
            //définition des propriétés spécifiques
            $event['extendedProps'] = ['userId' => $idUtil];
            array_push($reponse, $event);
        }
        return $reponse;
    } catch (PDOException $e) {
        return '';
    }
}
