
<div class="container mt-3">
    <div class="row justify-content-center">
        <div class="col-md-6 col-md-offset-3">
            <div class="panel panel-login">
                <div class="panel-body">
                    <div class="row">
                        <div class="col-lg-12">
                            <form id="login-form" action="index.php?page=connexion" method="post" role="form" style="display: block;">
                                <div class="form-group">
                                    <input type="text" name="username" id="username" tabindex="1" class="form-control" placeholder="Username" value="">
                                </div>
                                <div class="form-group">
                                    <input type="password" name="password" id="password" tabindex="2" class="form-control" placeholder="Password">
                                </div>
                                <div class="form-group">
                                    <div class="row justify-content-center">
                                        <div class="col-sm-6 col-sm-offset-3">
                                            <?php
                                            if (isset($_SESSION["etatConnexion"]) && $_SESSION["etatConnexion"] == 0) {
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
