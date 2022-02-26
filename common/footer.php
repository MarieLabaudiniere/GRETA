<footer>
    
    <p class="text-center">
         <a href="#">Contact Admin</a>
        | <a href="index.php?page=profilUtilisateur">Profil</a>
        | <?php
        if(isset($_SESSION["etatConnexion"]) && $_SESSION["etatConnexion"] == "1"){
            echo "<b>" . $_SESSION["prenom"] . " " . $_SESSION["nom"] . "</b> ";
        } 
        echo date("d/m/Y") ?>
    </p>
   
</footer>
</body>

</html>