<?php
//la connexion a déjà été faite
if (isset($_SESSION["etatConnexion"]) && $_SESSION["etatConnexion"] == "1") {
    header('Location: index.php?page=home');
} else {
    //formulaire permettant de saisir son identifiant et son mot de passe
?>
    <div class="container mt-3">
        <div class="row justify-content-center">
            <div class="col-md-6 col-md-offset-3">
                <div class="panel panel-login">
                    <div class="panel-body">
                        <div class="row">
                            <div class="col-lg-12">
                                <form id="login-form" action="index.php?page=connexion" method="post" role="form" style="display: block;">
                                    <div class="form-group">
                                        <input type="text" name="username" id="username" tabindex="1" class="form-control" placeholder="Username" value="" required>
                                    </div>
                                    <div class="form-group">
                                        <div class="input-group-prepend">
                                            <input type="password" name="password" id="password" tabindex="2" class="form-control" placeholder="Password" required>
                                            <span class="input-group-text" id=""><img id="eye" src="./public/medias/eye.svg" onclick="afficher()"></span>
                                        </div>
                                    </div>
                                    <div class="form-group">
                                        <div class="row justify-content-center">
                                            <div class="col-sm-6 col-sm-offset-3">
                                                <?php
                                                if (isset($_SESSION["timeNextTentative"])) {
                                                    $dateTimeNextConnexion = new DateTime();
                                                    $dateTimeNextConnexion->setTimestamp($_SESSION["timeNextTentative"]);
                                                    echo "<p class=\"bg-danger\">Nb de tentatives dépassées, attendez " . $dateTimeNextConnexion->format('d-m-Y H:i') . "</p>";
                                                } else if (isset($_SESSION["etatConnexion"]) && $_SESSION["etatConnexion"] == 0) {
                                                    echo "<p class=\"bg-danger\">Identifiant ou mot de passe incorrect</p>";
                                                }
                                                ?>
                                                <input type="submit" name="login-submit" id="login-submit" tabindex="4" class="form-control btn-secondary" value="Connexion">

                                            </div>
                                        </div>
                                    </div>

                                </form>

                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
<?php } ?>
<script>
    function afficher() {
        const input = document.getElementById("password");
        const img = document.getElementById("eye");
        if (input.type === "password") {
            input.type = "text";
            img.src="./public/medias/eye-slash.svg";
        } else {
            input.type = "password";
            img.src="./public/medias/eye.svg";
        }
    }
</script>