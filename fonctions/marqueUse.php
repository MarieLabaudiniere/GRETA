<?php
//fonction qui va renvoyer un tableau contenant toutes les options 
//pour construire la liste déroulante de toutes les marques
function generateOptionHTML($pdoP)
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
