<?php $this->_title = 'Blog connexion'; ?>



<!-- $content definition -->

<?php ob_start(); ?>

<div class="masthead connexionSection">
    <div class="container d-flex h-100 align-items-center">
        <div class="mx-auto text-center">
            <h1 class="mx-auto mb-3 text-uppercase">Bienvenue !</h1>
            <h2 class="text-white-50 mx-auto mt-2 mb-3"><a href="index.php?action=connexionView">Se connecter</a> | S'inscrire</h2>

            <?php
            if (isset($message)) {
                echo '<div class="adminMessage text-white text-center">' . $message . '</div>';
            }
            ?>
            
            <form class="form-inline d-flex flex-column" method="POST" action="index.php?action=inscription">

                <input type="text" name="pseudo" class="form-control flex-fill mr-0 mr-sm-2 mb-3 mb-sm-0" placeholder="Votre pseudo...*" required>

                <?= isset($errors['pseudo']) ? '<p class="text-white">' . $errors['pseudo'] . '</p>' : ''; ?>

                <input type="password" name="pass1" class="form-control flex-fill mr-0 mr-sm-2 mb-3 mb-sm-0" placeholder="Votre mot de passe...*" required>

                <?= isset($errors['pass1']) ? '<p class="text-white">' . $errors['pass1'] . '</p>' : ''; ?>

                <input type="password" name="pass2" class="form-control flex-fill mr-0 mr-sm-2 mb-3 mb-sm-0" placeholder="Retapez votre mot de passe...*" required>
                <input type="email" name="email" class="form-control flex-fill mr-0 mr-sm-2 mb-3 mb-sm-0" placeholder="Votre adresse email...*" required>

                <?= isset($errors['email']) ? '<p class="text-white">' . $errors['email'] . '</p>' : ''; ?>

                <div class="form-check">
                    <input type="checkbox" class="form-check-input" name="confidentiality" id="confidentiality" required/>
                    <label class="form-check-label text-white-50" for="confidentiality" >* J'ai lu et j'accepte la <a href="index.php?action=confidentiality"> Politique de confidentialité des données personnelles</a></label>
                </div>
                
                <button type="submit" class="btn btn-primary-custom mx-auto">S'inscrire</button>
                <p class="text-white-50">Vous avez déjà un compte ? <a href="index.php?action=connexionView">Connectez-vous !</a></p>

            </form>
        </div>
    </div>
</div>


<?php $this->_content = ob_get_clean(); ?>

