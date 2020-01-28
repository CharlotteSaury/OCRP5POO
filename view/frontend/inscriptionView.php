<?php $title = 'Blog connexion'; ?>



<!-- $content definition -->

<?php ob_start(); ?>

<div class="masthead" id="connexionSection">
    <div class="container d-flex h-100 align-items-center">
        <div class="mx-auto text-center">
            <h1 class="mx-auto my-0 text-uppercase">Bienvenue !</h1>
            <h2 class="text-white-50 mx-auto mt-2 mb-5"><a href="connexionView.php">Se connecter</a> | S'inscrire</h2>
            <form class="form-inline d-flex flex-column">
                <input type="text" class="form-control flex-fill mr-0 mr-sm-2 mb-3 mb-sm-0" id="inscriptionPseudo" placeholder="Votre pseudo...*" required>
                <input type="password" class="form-control flex-fill mr-0 mr-sm-2 mb-3 mb-sm-0" id="inscriptionPass" placeholder="Votre mot de passe...*" required>
                <input type="password" class="form-control flex-fill mr-0 mr-sm-2 mb-3 mb-sm-0" id="inscriptionPass2" placeholder="Retapez votre mot de passe...*" required>
                <input type="email" class="form-control flex-fill mr-0 mr-sm-2 mb-3 mb-sm-0" id="inscriptionEmail" placeholder="Votre adresse email...*" required>
                <div class="form-check">
                    <input type="checkbox" class="form-check-input" name="rememberme" />
                    <label class="form-check-label text-white-50" for="rememberme" >* J'ai lu et j'accepte la <a href="#"> Politique de Protection des données personnelles</a></label>
                </div>
                <button type="submit" class="btn btn-primary-custom mx-auto">S'inscrire</button>
                <p class="text-white-50">Vous avez déjà un compte ? <a href="connexionView.php">Connectez-vous !</a></p>

            </form>
        </div>
    </div>
</div>


<?php $content = ob_get_clean(); ?>


<?php require('frontend_login_template.php'); ?>