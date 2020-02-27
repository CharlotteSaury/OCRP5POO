<?php $this->_title = 'Mot de passe oublié'; ?>



<!-- $content definition -->

<?php ob_start(); ?>

<div class="masthead" id="connexionSection">
    <div class="container d-flex h-100 align-items-center">
        <div class="mx-auto text-center">
            <h1 class="mx-auto my-0 text-uppercase">Mot de passe oublié</h1>
            
            <?php
            if (isset($message))
            {
                echo '<div class="adminMessage text-white-50 text-center">' . $message . '</div>';
            }
            else
            {
            ?>

            <form method="POST" action="index.php?action=forgotPassMail" class="form-inline d-flex flex-column">

                <?php
                if (isset($_COOKIE['email']))
                {
                    ?>
                    <input type="email" name="email" class="form-control flex-fill mr-0 mr-sm-2 mb-3 mb-sm-0" value="<?= htmlspecialchars($_COOKIE['email']); ?>" required>
                    <?php
                }
                else
                {
                    ?>
                    <input type="email" name="email" class="form-control flex-fill mr-0 mr-sm-2 mb-3 mb-sm-0" placeholder="Votre email..." required>
                    
                    <?php
                }
                ?>
                
                <button type="submit" class="btn btn-primary-custom mx-auto">Réinitialiser mon mot de passe</button>
                <p class="text-white-50">Pas encore membre ? <a href="index.php?action=inscriptionView">Je m'inscris !</a></p>


            </form>
            <?php
            }
            ?>
        </div>
    </div>
</div>


<?php $this->_content = ob_get_clean(); ?>