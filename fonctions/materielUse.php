<?php
//fonction qui génère la liste déroulante de toutes les marques dispo en BD
function generateOptionMarqueHTML($pdoP)
{
    $stmt = $pdoP->prepare("SELECT ID_MARQUE, LIBELLE_MARQUE FROM marques");
    $stmt->execute();
    $options = [];//tableau qui va contenir toutes les options HTML
    $results = $stmt->fetchAll(PDO::FETCH_ASSOC);
    $i = 0;//indice du tableau options
    foreach ($results as $result) {
        $options[$i] = '<option value="' . $result['ID_MARQUE'] . '">' . $result['LIBELLE_MARQUE'] . '</option>';
        $i++;
    }
    return $options;
}
//fonction qui génère la liste déroulante correspondant aux catégories matériels
//disponibles en BD
function generateOptionCatMatHTML($pdoP)
{
    $stmt = $pdoP->prepare("SELECT ID_CAT_MAT, LIBELLE_MAT FROM categories_materiel");
    $stmt->execute();
    $optionsHTML = "";//tableau qui va contenir toutes les options HTML
    $results = $stmt->fetchAll(PDO::FETCH_ASSOC);
    $i = 0;//indice du tableau options
    foreach ($results as $result) {
        $optionsHTML .= '<option value="' . $result['ID_CAT_MAT'] . '">' . $result['LIBELLE_MAT'] . '</option>';
    }
    return $optionsHTML;
}

//fonction qui génère la liste déroulante des types de matériel correspondant 
//à la catégorie passée en paramètre
function generateOptionTypeMatHTML($pdoP,$idCatMat)
{
    $stmt = $pdoP->prepare("SELECT ID_TYPE_MAT, LIBELLE_TYPE_MAT FROM types_materiel WHERE ID_CAT_MAT=?");
    $stmt->execute([$idCatMat]);
    $optionsHTML = "";//tableau qui va contenir toutes les options HTML
    $results = $stmt->fetchAll(PDO::FETCH_ASSOC);
    $i = 0;//indice du tableau options
    foreach ($results as $result) {
        $optionsHTML .=  '<option value="' . $result['ID_TYPE_MAT'] . '">' . $result['LIBELLE_TYPE_MAT'] . '</option>';
    }
    return $optionsHTML;
}
//fonction qui génère la liste déroulante des types de matériel correspondant 
//à la catégorie passée en paramètre
function getListTypeMatJSON($pdoP,$idCatMat)
{
    $stmt = $pdoP->prepare("SELECT ID_TYPE_MAT, LIBELLE_TYPE_MAT FROM types_materiel WHERE ID_CAT_MAT=?");
    $stmt->execute([$idCatMat]);
    return $stmt->fetchAll(PDO::FETCH_ASSOC);
}

//retourne la liste des matériels 
function getListMateriel($pdoP, $values){
    $paraSQL = [];
    $requeteSQL = "SELECT materiels.ID_MAT, materiels.LIBELLE_MAT, concat(materiels.ID_MARQUE, ' : ',marques.LIBELLE_MARQUE) AS LIB_MARQ, 
    concat(materiels.ID_TYPE_MAT,' : ', types_materiel.LIBELLE_TYPE_MAT) AS LIB_TYPEMAT FROM materiels
    INNER JOIN types_materiel ON types_materiel.ID_TYPE_MAT=materiels.ID_TYPE_MAT
    INNER JOIN marques ON marques.ID_MARQUE=materiels.ID_MARQUE";
    $typeMat = $values['type_materiel'];
    $libMat = $values['libelle_mat'];
    if ($typeMat!="" || $libMat!="") $requeteSQL .= " WHERE ";
    if ($typeMat != "") { //si l'utilisateur a filtré sur le type de matériel
        $requeteSQL .= " materiels.ID_TYPE_MAT=?";
        array_push($paraSQL, $typeMat);
    }
    if ( $libMat != "") { //si l'utilisateur a filtré sur le libellé
        $requeteSQL .= ($typeMat != "") ? "AND" : "";
        $requeteSQL .= " materiels.LIBELLE_MAT LIKE ?";
        array_push($paraSQL, '%' . $libMat . '%');
    }
    $stmt = $pdoP->prepare($requeteSQL);
    $stmt->execute($paraSQL);
    return $stmt->fetchAll(PDO::FETCH_ASSOC);
}


?>