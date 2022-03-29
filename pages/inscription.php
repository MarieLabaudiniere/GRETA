
<?php
//chargement des paramètres de la BD
include('./utils/db.php');
//chargement des fonctions liées à la manipulation des données utilisateur
include('./fonctions/utilisateurUse.php');
if(isset($_POST['register-submit'])) {//si ce paramètre existe alors c'est que l'utilisateur
    //a soumis le formulaire
    try {
        createUser($pdo, $_POST);
        header('Location: index.php?page=authentif&ok=1');
        die();
    } catch(PDOException $e){
        echo "Erreur  : " . $e->getMessage();
    }
}
?>
<!-- formulaire permettant de créer un nouvel identifiant-->
<div class="container mt-3">
    <div class="row justify-content-center">
        <div class="col-md-6 col-md-offset-3">
            <div class="panel panel-login">
                <div class="panel-body">
                    <div class="row">
                        <div class="col-lg-12">
                            <form id="register-form" action="index.php?page=inscription" method="post" role="form">
                                <div class="form-group">
                                    <input type="text" name="username" id="username" tabindex="1" class="form-control" placeholder="Username" value="">
                                </div>
                                <div class="form-group">
                                    <input type="text" name="firstname" id="firstname" tabindex="1" class="form-control" placeholder="prénom" value="">
                                </div>
                                <div class="form-group">
                                    <input type="text" name="lastname" id="lastname" tabindex="1" class="form-control" placeholder="nom de famille" value="">
                                </div>
                                <div class="form-group">
                                    <input type="email" name="email" id="email" tabindex="1" class="form-control" placeholder="Email Address" value="">
                                </div>
                                <div class="form-group">
                                    <input type="password" name="password" id="password" tabindex="2" class="form-control" placeholder="Password">
                                </div>
                                <div class="form-group">
                                    <input type="password" name="confirm_password" id="confirm_password" tabindex="2" class="form-control" placeholder="Confirm Password">
                                </div>
                                <div class="form-group">
                                    <div class="row justify-content-center">
                                        <div class="col-sm-6 col-sm-offset-3">
                                            <input type="submit" name="register-submit" id="register-submit" tabindex="4" class="form-control btn btn-secondary" value="Inscription">
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
<script>
    
    $('#register-form').validate({
        rules: {
            username: {
                required: true,
                minlength: 2
            },
            firstname: {
                required: true,
                minlength: 2
            },
            lastname: {
                required: true,
                minlength: 2
            },
            email: {
                //email: true,
                maxlength: 255
            },
            password: {
                required: true,
            },
            confirm_password: {
                required: true,
                equalTo: "#password"
            }
        },
        messages: {
            confirm_password: {
                equalTo: "Vous devez saisir le même mot de passe."
            }
        },
        errorClass: "invalid",
        //onsubmit: false,
        submitHandler: function(form) {
            if (form.valid()) {
                form.submit();
            }
            return false;
        }
    });
    $.validator.addMethod('email', function(value) {
        if (value.length > 0) {
            return /^[^@\s]+@[^@\s]+\.[^@\s]+$/.test(value);
        }
        return true;
    }, 'le format de l\'email est invalide.');
    $.validator.addMethod('password', function(value) {
        return /^(?=.*[a-z])(?=.*[A-Z])(?=.*[0-9])(?=.*[^a-zA-Z\d])\S{12,50}$/.test(value);
    }, 'Le mot de passe doit avoir plus de 12 caractères, au moins une majuscule, une minuscule, un chiffre et un caractère spécial');
</script>