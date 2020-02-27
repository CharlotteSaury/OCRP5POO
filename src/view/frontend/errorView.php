<?php $this->_title = 'Erreur'; ?>



<!-- $content definition -->

<?php ob_start(); ?>

<div class="masthead" id="connexionSection">
    <div class="container d-flex h-100 align-items-center">
        <div class="mx-auto text-center">
            <h1 class="mx-auto my-0 text-uppercase">Erreur : <?= $errorMessage;?></h1>
            
        </div>
    </div>
</div>


<?php $this->_content = ob_get_clean(); ?>

