<?php
//page d'accueil
//chargement des paramètres de la BD et connexion
include('./utils/db.php');

try {
    $stmt = $pdo->prepare("SELECT materiels.id_mat, materiels.libelle_mat, 
    CONCAT(types_materiel.id_type_mat, ' : ', types_materiel.LIBELLE_TYPE_MAT) AS libTypeMat 
    FROM materiels 
    INNER JOIN types_materiel ON materiels.id_type_mat = types_materiel.id_type_mat LIMIT 10");
    $stmt->execute();
    $results = $stmt->fetchAll(PDO::FETCH_ASSOC);
    echo "<pre>".print_r($results)."</pre>";

    //code qui permet d'afficher les résultats sous forme d'un tableau

    
} catch(PDOException $e) {
    echo "Connection failed: " . $e->getMessage();
}
?>