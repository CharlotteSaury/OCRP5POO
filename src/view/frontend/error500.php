<?php $this->_title = 'Erreur 404'; ?>



<!-- $content definition -->

<?php ob_start(); ?>

<div class="masthead connexionSection" id="errorSection">
    <div class="container d-flex h-100 align-items-center">
        <div class="mx-auto text-center">
            <h1 class="mx-auto my-0 text-uppercase">Erreur : </h1>
            <h2 class="text-white-50">Erreur interne au serveur.</h2>
        </div>
    </div>
</div>


<?php $this->_content = ob_get_clean(); ?>

