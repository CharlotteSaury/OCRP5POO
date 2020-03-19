<?php $this->_title = 'Blog connexion'; ?>



<!-- $content definition -->

<?php ob_start(); ?>

<div class="masthead connexionSection">
    <div class="container d-flex h-100 align-items-center">
        <div class="mx-auto text-center">
            <h1 class="mx-auto mb-5 text-uppercase">Bienvenue !</h1>
            <h2 class="text-white-50 mx-auto mt-2 mb-3">Se connecter | <a href="index.php?action=inscriptionView">S'inscrire</a></h2>

            <?php
            if ($session->get('message')) {
                echo '<div class="adminMessage text-white-50 text-center">' . $session->get('message') . '</div>';
            }
            ?>

            <form method="POST" action="index.php?action=connexion" class="form-inline d-flex flex-column">

                <?php
                if (isset($_COOKIE['email'])) {
                    ?>
                    <input type="email" name="email" class="form-control flex-fill mr-0 mr-sm-2 mb-3 mb-sm-0" value="<?= htmlspecialchars($_COOKIE['email'], ENT_QUOTES); ?>" required>
                    <?php
                
                } else {
                    ?>
                    <input type="email" name="email" class="form-control flex-fill mr-0 mr-sm-2 mb-3 mb-sm-0" placeholder="Votre email..." required>
                    <?php
                }
                ?>
                <input type="password" class="form-control flex-fill mr-0 mr-sm-2 mb-3 mb-sm-0" name="pass" placeholder="Votre mot de passe..." required>
                <a href="index.php?action=forgotPassView">J'ai oublié mon mot de passe...</a>
                <div class="form-check">
                    <input type="checkbox" class="form-check-input" name="rememberme" id="rememberme" />
                    <label class="form-check-label text-white-50" for="rememberme" >Se souvenir de moi</label>
                </div>
                <button type="submit" class="btn btn-primary-custom mx-auto">Se connecter</button>
                <p class="text-white-50">Pas encore membre ? <a href="index.php?action=inscriptionView">Je m'inscris !</a></p>


            </form>
        </div>
    </div>
</div>


<?php $this->_content = ob_get_clean(); ?>