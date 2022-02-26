
    <?php
    //fonctions et classes disponible pour le site
    include('./common/functions.php');
    // accessibilité à la session courante de l'utilisateur
    session_start();
    // Affichage « de la partie haute » de votre site, commun à l'ensemble de votre site
    include('./common/header.php');
    
    // Pages autorisées : whitelist
    include('./whitelist/web.php');
    //nav commune à tout le site
    include('./common/nav.php');
    // Gestion de l'affichage de la page demandée
    //limiter le temps de la session
    $timeSession = isset($_SESSION['timeLastAction']) ? $_SESSION['timeLastAction'] : time();
    $timeCourant = time(); //nb s depuis le 01/01/70
    $page = isset($_GET['page']) ? $_GET['page'] : "authentif";
    if (
        $timeCourant - $timeSession < 3600 //3600 s = 1h
        && in_array($page, $whitelist) //accès à la page autorisée
    ) {
        //rafraichissement du temps de la dernière action
        $_SESSION['timeLastAction'] = $timeCourant;
        if ($page == "authentif") {
            session_regenerate_id();
        }
        include("pages/" . $page . '.php');
    } else {
        //si le champ page n'est pas accessible dans l'URL OU que l'accès à la page n'est pas possible
        //alors on demande une authentification

        //si le temps d'inactivité a été dépassé, détruire la session
        //et forcer une ré authentification
        if ($timeCourant - $timeSession >= 3600) {
            session_destroy();
            session_start();
        }
        session_regenerate_id();
        
        include('pages/authentif.php');
    }

    // Affichage de la partie basse de votre site, commun à l'ensemble de votre site. 
    include('./common/footer.php');
    ?>
    
