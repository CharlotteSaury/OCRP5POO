<?php $title = 'Nouveau mot de passe'; ?>



<!-- $content definition -->

<?php ob_start(); ?>

<div class="masthead" id="connexionSection">
    <div class="container d-flex h-100 align-items-center">
        <div class="mx-auto text-center">
            
            <h1 class="mx-auto my-0 text-uppercase">Nouveau mot de passe</h1>

            <?php
            if (isset($message))
            {
                echo '<div class="adminMessage text-white-50 text-center">' . $message . '</div>';
            }

            if ($status = true)
            {
            ?>

            <form method="POST" action="index.php?action=newPass" class="form-inline d-flex flex-column">

                <input type="hidden" name="email" class="form-control flex-fill mr-0 mr-sm-2 mb-3 mb-sm-0" value="<?= $email ?>">
                <input type="password" name="pass1" class="form-control flex-fill mr-0 mr-sm-2 mb-3 mb-sm-0" placeholder="Nouveau mot de passe">
                <input type="password" name="pass2" class="form-control flex-fill mr-0 mr-sm-2 mb-3 mb-sm-0" placeholder="Retapez vorte mot de passe">
                    
                
                <button type="submit" class="btn btn-primary-custom mx-auto">Réinitialiser mon mot de passe</button>


            </form>

            <?php
            }
            ?>
        </div>
    </div>
</div>


<?php $content = ob_get_clean(); ?>



<?php require 'frontend_login_template.php'; ?>