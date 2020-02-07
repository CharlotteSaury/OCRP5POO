<?php $title = 'Blog connexion'; ?>



<!-- $content definition -->

<?php ob_start(); ?>

<div class="masthead" id="connexionSection">
    <div class="container d-flex h-100 align-items-center">
        <div class="mx-auto text-center">
            <h1 class="mx-auto my-0 text-uppercase">Bienvenue !</h1>
            <h2 class="text-white-50 mx-auto mt-2 mb-5">Se connecter | <a href="index.php?action=inscriptionView">S'inscrire</a></h2>
            <form class="form-inline d-flex flex-column">
                <input type="text" class="form-control flex-fill mr-0 mr-sm-2 mb-3 mb-sm-0" id="inscriptionPseudo" placeholder="Votre pseudo..." required>
                <input type="password" class="form-control flex-fill mr-0 mr-sm-2 mb-3 mb-sm-0" id="loginPass" placeholder="Votre mot de passe..." required>
                <a href="#">J'ai oublié mon mot de passe...</a>
                <div class="form-check">
                    <input type="checkbox" class="form-check-input" name="rememberme" />
                    <label class="form-check-label text-white-50" for="rememberme" >Se souvenir de moi</label>
                </div>
                <p><?= $message; ?></p>
                <button type="submit" class="btn btn-primary-custom mx-auto">Se connecter</button>
                <p class="text-white-50">Pas encore membre ? <a href="index.php?action=inscriptionView">Je m'inscris !</a></p>


            </form>
        </div>
    </div>
</div>


<?php $content = ob_get_clean(); ?>



<?php require('frontend_login_template.php'); ?>