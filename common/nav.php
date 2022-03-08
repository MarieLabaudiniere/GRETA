<?php
    //fonction qui renvoie le nom de la page courante
    function getPageCourante(){
        //page par défaut
        $page="authentif";
        if (isset($_GET['page'])) {
            //si le nom de la page est contenu dans l'URL
            $page =  $_GET['page'];
        }
        return $page;
    }
    //fonction qui renvoie la chaine active si le lien correspond à la page
    //sur laquelle l'utilisateur se trouve.
    function getActive($pageLien) {
        $pageC = getPageCourante();
        if($pageLien == $pageC) {
            //si le nom du lien de la page de la barre de navigation est le même que la page
            //sur laquelle l'utilisateur est, le lien doit être actif
            return "active";
        } else{
            return "";
        }
    }
?>
<nav class="navbar navbar-expand-lg navbar-dark bg-secondary">

    <a class="navbar-brand" href="index.php"> <img class="logo" src="./public/medias/logo_gretaTrans.png" height="60"></a>
    <button class="navbar-toggler" type="button" data-toggle="collapse" data-target="#navbar1" aria-controls="navbarNav" aria-expanded="false" aria-label="Toggle navigation">
        <span class="navbar-toggler-icon"></span>
    </button>
    <div class="collapse navbar-collapse mr-5" id="navbar1">
        <ul class="navbar-nav ml-auto">
            <?php 
            
            if(isset($_SESSION["etatConnexion"]) && $_SESSION["etatConnexion"]==1 && getPageCourante()!="deconnexion") {
                //l'utilisateur a réussi à se connecter?>
                <li class="nav-item <?php echo getActive('deconnexion');?>">
                    <a class="nav-link" href="index.php?page=deconnexion">Déconnexion <span class="sr-only">(current)</span></a>
                </li>
                <li class="nav-item <?php echo getActive('home');?>">
                    <a class="nav-link" href="index.php?page=home">Accueil </a>
                </li>
                <?php if(isset($_SESSION["is_admin"]) && $_SESSION["is_admin"]==1) {
                    //si l'utilisateur est administrateur alors on affiche le tableau de bord?>
                <li class="nav-item <?php echo getActive('tableauDeBord');?>">
                    <a class="nav-link" href="index.php?page=tableauDeBord">Tableau de bord </a>
                </li>
                <?php } ?>
                <li class="nav-item dropdown">
                    <a class="nav-link  dropdown-toggle" href="#" data-toggle="dropdown">Réservation </a>
                    <ul class="dropdown-menu">
                        <li><a class="dropdown-item" href="index.php?page=home
                        ">Mes réservations</a></li>
                        <li><a class="dropdown-item" href="index.php?page=reservation">Nouvelles réservations</a></li>
                    </ul>
                </li>
            <?php } else {
            ?>
            <li class="nav-item <?php echo getActive('authentif');?>">
                <a class="nav-link" href="index.php?page=authentif">Connexion <span class="sr-only">(current)</span></a>
            </li>
            <li class="nav-item <?php echo getActive('inscription');?>">
                <a class="nav-link" href="index.php?page=inscription">Inscription </a>
            </li>
            <?php } 
            ?>  
        </ul>
    </div>
</nav>