<!-- formulaire permettant à l'utilisateur de modifier son profil -->
<div class="container mt-3">
    <div class="row justify-content-center">
        <div class="col-md-6 col-md-offset-3">
            <div class="panel panel-login">
                <div class="panel-body">
                    <div class="row">
                        <div class="col-lg-12">
                            <form id="register-form" action="index.php?page=profilUtilisateurModif" method="post" role="form">
                                <div class="form-group">
                                    <input type="text" name="username" id="username" tabindex="1" class="form-control" placeholder="Username" value="<?php echo $_SESSION['ident']?>" required="true">
                                </div>
                                <div class="form-group">
                                    <input type="text" name="firstname" id="firstname" tabindex="1" class="form-control" placeholder="prénom" value="<?php echo $_SESSION['prenom']?>" required="true">
                                </div>
                                <div class="form-group">
                                    <input type="text" name="lastname" id="lastname" tabindex="1" class="form-control" placeholder="nom de famille" value="<?php echo $_SESSION['nom']?>" required="true">
                                </div>
                                <!-- <div class="form-group">
                                    <input type="email" name="email" id="email" tabindex="1" class="form-control" placeholder="Email Address" value="<?php echo $_SESSION['nom']?>" required="true">
                                </div> -->
                                <!-- <div class="form-group">
                                    <input type="password" name="password" id="password" tabindex="2" class="form-control" placeholder="Password" required="true">
                                </div>
                                <div class="form-group">
                                    <input type="password" name="confirm-password" id="confirm-password" tabindex="2" class="form-control" placeholder="Confirm Password">
                                </div> -->
                                <div class="form-group">
                                    <div class="row justify-content-center">
                                        <div class="col-sm-6 col-sm-offset-3">
                                            <input type="submit" name="register-submit" id="register-submit" tabindex="4" class="form-control btn btn-secondary" value="Mise à jour">
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