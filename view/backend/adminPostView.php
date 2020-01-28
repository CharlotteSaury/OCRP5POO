<!-- $title definition -->

<?php $title = 'Admin - Article'; ?>

<!-- Content title definition -->

<?php $contentTitle = ''; ?>

<!-- $content definition -->

<?php ob_start(); ?>


<div class="row adminPostView">
    <div class="col-11 mx-auto my-3">
        <div class="d-flex flex-row justify-content-between">
            <h2 class="mb-4">Titre de l'article</h2>
            <div>
                <a href="editPostView.php" class="btn btn-outline-dark btn-sm" title="Modifier">
                    <i class="fas fa-pencil-alt"></i>
                </a>
                <button type="button" class="btn btn-outline-dark btn-sm" title="Supprimer">
                    <i class="fas fa-trash-alt"></i>
                </button>
            </div>
        </div>
        


        <hr class="d-none d-lg-block ml-0">

        <div class="post-content post-content-text text-black-50 text-justify">
            <p class="">Posté par <strong>Auteur</strong></p>
            <p class="mb-0">le 23/12/2019</p>
            <p class="mb-0">Dernière modification le 23/12/2019</p>
        </div>

        <hr class="d-none d-lg-block ml-0">

        <div class="post-content post-content-text text-black-50 text-justify">
            <p class=""><strong>Chapô : </strong>Donec pede justo, fringilla vel, aliquet nec, vulputate eget, arcu. Vestibulum facilisis..</p>
        </div>

        <hr class="d-none d-lg-block ml-0">

        <div class="post-content post-content-text text-black-50 text-justify">
            <p class="">Donec pede justo, fringilla vel, aliquet nec, vulputate eget, arcu. Vestibulum facilisis, purus nec pulvinar iaculis, ligula mi congue nunc, vitae euismod ligula urna in dolor. Praesena mi congue nunc, vitt ac sem eget est egestas volutpat. Curabitur a felis in nunc fringilla tristique.</p>
            <p class="">Donec pede justo, fringilla vel, aliquet nec, vulputate eget, arcu. Vestibulum facilisis, purus nec pulvinar iaculis, ligula mi congue nunc, vitae euismod ligula urna in dolor. Praesent ac sem egetigula mi congue nunc, vitae euismod ligula urna in dolor. Praesent ac sem eget est egestas volutpat. Curabitur a felis in nunc fringilla tristique est egestas volutpat. Curabitur a felis in nunc fringilla tristique.</p>
        </div>

        <div class="my-4 text-center">   
            <img class="admin-post-img" src="../../public/images/post-3.jpg" />  
        </div>

        <div class="post-content post-content-text text-black-50 text-justify">    
            <p class="">Donec pede justo, fringilla vel, aliquet nec, vulputate eget, arcu. Vestibulum facilisis, purus nec pulvinar iaculis, ligula mi congue nunc, vitae euismod ligula urna in dolor. Praesent ac sem eget est egestas volutpat. Curabitur a felis in nunc fringilla tristique.</p> 
            <p class="">Donec pede justo, fringilla vel, aliquet nec, vulputate eget, arcu. Vestibulum facilisis, purus nec pulvinar iaculis, ligula mi congue nunc, vitae euismod ligula urna in dolor. Praesent aigula mi congue nunc, vitae euismod ligula urna in dolor. Praesent ac sem eget est egestas volutpat. Curabitur a felis in nunc fringilla tristiquec sem eget est egestas volutpat. Curabitur a felis in nunc fringilla tristique.</p> 
            <p class="">Donec pede justo, fringilla vel, aliquet nec, vulputate eget, arcu. Vestibulum facilisis, purus nec pulvinar iaculis, ligula mi congue nunc, vitae euismod ligula urna in dolor. Praesent ac sem ega mi congue nunc, vitet est egestas volutpat. Curabitur a felis in nunc fringilla tristique.</p>
        </div>
        <hr class="d-none d-lg-block ml-0"> 
    </div>
</div>

<div class="row">
    <div class="col-11 mx-auto">
        <h4 class="mb-4">Catégories</h4>
        <div class="">
            <a class="btn btn-outline-secondary" href="#">Développement</a>
            <a class="btn btn-outline-secondary" href="#">PHP</a>
            <a class="btn btn-outline-secondary" href="#">WordPress</a>            
        </div>
    </div>
</div>

<hr class="d-none d-lg-block ml-0">

<!-- Comments section -->
<div class="row mt-4">
    <div class="post-comments col-11 mx-auto mb-5">
        <h2>Commentaires</h2>
        <hr class="d-none d-lg-block ml-0">

        <div class="comments mt-5">
            <div class="comment-content text-black-50 text-justify mb-4">
                <div class="comment-infos">
                    <p class=""><strong>Céline Dion</strong> - le 23/12/2019 à 10h12</p>
                </div>
                <div class="comment-text">
                    <p class="mb-0">Nam at tortor in tellus interdum sagittis. Aenean leo ligula, porttitor eu, consequat vitae, eleifend ac, enim. Praesent adipiscing. Suspendisse eu ligula. Sed aliquam ultrices maurisNam at tortor in tellus interdum sagittis. Aenean leo ligula, porttitor eu, consequat vitae, eleifend ac, enim. Praesent adipiscing. Suspendisse eu ligula. Sed aliquam ultrices mauris.Nam at tortor in tellus interdum sagittis. Aenean leo ligula, porttitor eu, consequat vitae, eleifend ac, enim. Praesent adipiscing. Suspendisse eu ligula. Sed aliquam ultrices mauris..</p>
                </div>
            </div>
            
            <div class="comment-content text-black-50 text-justify mb-4">
                <div class="comment-infos">
                    <p class=""><strong>Charlotte SAURY</strong> - le 20/12/2019 à 10h12</p>
                </div>
                <div class="comment-text">
                    <p class="mb-0">Nam anim. Praesent adipiscing. Suspendisse eu ligula. Sed aliquam ultrices mauris..</p>
                </div>
            </div>
            
            
            <div class="comment-content text-black-50 text-justify mb-4 unapproved-comment">
                <div class="comment-infos">
                    <p class=""><strong>Céline Dion</strong> - le 13/12/2019 à 23h59</p>
                </div>
                <div class="comment-text">
                    <p class="mb-0">Nam at tortor in tellus interdum sagittis. Aenean leo ligula, porttitdipiscing. Suspendisse eu ligula. Sed aliquam ultrices mauris.Nam at tortor in tellus interdum sagittis. Aenean leo ligula, porttitor eu, consequat vitae, eleifend ac, enim. Praesent adipiscing. Suspendisse eu ligula. Sed aliquam ultrices mauris..</p>
                </div>
                <div class="mt-2">
                    <button type="button" class="btn btn-outline-dark btn-sm" title="Approuver">
                        <i class="fas fa-check"></i>
                    </button>
                    <button type="button" class="btn btn-outline-dark btn-sm" title="Supprimer">
                        <i class="fas fa-times"></i>
                    </button>
                </div>
            </div>
            
            
        </div>
    </div>  

</div>
            




<?php $content = ob_get_clean(); ?>

<?php require('backend_template.php'); ?>
