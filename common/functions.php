<?php
    function formatDateBD($dateBDP) {
        $date = new DateTime($dateBDP);
        return $date->format("d/m/Y");
    }
    
?>