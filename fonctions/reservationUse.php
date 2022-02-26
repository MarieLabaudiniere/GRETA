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
function getListResaEnCours($pdoP) {
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
function getListMaterielDispoFast($pdoP, $val) {
    $stmt = $pdoP->prepare("SELECT materiels.ID_MAT, materiels.LIBELLE_MAT FROM materiels 
    WHERE materiels.ID_MAT NOT IN (select reservations.ID_MAT_RESA from reservations 
    where NOW() BETWEEN reservations.DATE_DEBUT_RESA AND reservations.DATE_FIN_RESA) 
    AND materiels.LIBELLE_MAT LIKE ?");
    $stmt->execute(['%'.$val.'%']);
    return $stmt->fetchAll(PDO::FETCH_ASSOC);
}

//liste du matériels disponibles, c.a.d non réservé à la période demandé
//avec les valeurs saisies dans le formulaire de recherche
function getListMaterielDispo($pdoP, $vals) {
    $paraSQL = [$vals['date_debut_resa'], $vals['date_fin_resa'], 
    $vals['date_debut_resa'], $vals['date_fin_resa'], $vals['date_debut_resa'], $vals['date_fin_resa']];
    $requeteSQL = "SELECT materiels.ID_MAT, materiels.LIBELLE_MAT FROM materiels 
    WHERE materiels.ID_MAT NOT IN (select reservations.ID_MAT_RESA from reservations 
    where reservations.DATE_DEBUT_RESA BETWEEN ? AND ? 
    OR reservations.DATE_FIN_RESA BETWEEN ? AND ?
    OR DATE(?) BETWEEN reservations.DATE_DEBUT_RESA AND reservations.DATE_FIN_RESA 
    OR DATE(?) BETWEEN reservations.DATE_DEBUT_RESA AND reservations.DATE_FIN_RESA)";
    if ($vals['marque']!="") {//si l'utilisateur a filtré sa recherche sur la marque
        $requeteSQL = $requeteSQL . " AND materiels.ID_MARQUE=?";
        array_push($paraSQL, $vals['marque']);
    }
    if ($vals['libelle_mat']!="") {//si l'utilisateur a filtré sur le libellé
        $requeteSQL = $requeteSQL .  " AND materiels.LIBELLE_MAT LIKE ?";
        array_push($paraSQL, '%' . $vals['libelle_mat'] . '%');
    }
    $stmt = $pdoP->prepare($requeteSQL);
    $stmt->execute($paraSQL);
    return $stmt->fetchAll(PDO::FETCH_ASSOC);
}
//fonction qui crée un enregistrement en BD
function createResa($pdoP, $vals) {
    //ATTENTION AVANT de créer en BD vérifier que la période choisie est toujours
    //disponible.
    try {
        $stmt = $pdoP->prepare("INSERT INTO reservations(ID_UTIL, ID_MAT_RESA, DATE_RESA, DATE_DEBUT_RESA, DATE_FIN_RESA)
        VALUES (?, ?, NOW(), ?, ?)");
        $stmt->execute([$_SESSION['id_util'], $vals['id_mat_resa'], $vals['date_debut_resa'], $vals['date_fin_resa']]);
        $stmt->fetch();
        return true; 
    } catch (PDOException $e){
        return false;
    }
}
