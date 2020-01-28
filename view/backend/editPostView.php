<!-- $title definition -->

<?php $title = 'Admin - Editer l\'article'; ?>

<!-- Content title definition -->

<?php $contentTitle = 'Editer l\'article'; ?>

<!-- $content definition -->

<?php ob_start(); ?>


<div class="row adminPostView">
    <div class="col-11 mx-auto my-3">

        <form class="form" method="" action="">

            <div class="form-group">
                <label for="post-title">Titre : </label>
                <input type="text" class="form-control" name="post-title" placeholder="qsfcgaerg efsd"/>
            </div>

            <hr class="d-none d-lg-block ml-0">

            <div class="form-group">
                <label for="post-author">Auteur : </label>
                <input type="text" class="form-control" name="post-author" placeholder="Charlotte SAURY"/>
            </div>

            <div class="post-content post-content-text text-black-50 text-justify">
                <p class="mb-0">le 23/12/2019</p>
                <p class="mb-0">Dernière modification le 23/12/2019</p>
            </div>

            <hr class="d-none d-lg-block ml-0">


            <div class="form-group">
                <label for="post-chapo">Chapô : </label>
                <input type="text" class="form-control" name="post-chapo" placeholder="Donec pede justo, fringilla vel, aliquet nec, vulputate eget, arcu. Vestibulum facilisis.."/>   
            </div>


            <hr class="d-none d-lg-block ml-0">

            <h4>Contenu</h4>

            <div class="form-group d-flex">
                <label for="post-content" hidden>post-content</label>
                <button type="button" class="btn btn-outline-dark btn-sm mr-2" title="Supprimer">
                    <i class="fas fa-trash-alt"></i>
                </button>
                <textarea class="form-control">Donec pede justo, fringilla vel, aliquet nec, vulputate eget, arcu. Vestibulum facilisis, purus nec pulvinar iaculis, ligula mi congue nunc, vitae euismod ligula urna in dolor. Praesena mi congue nunc, vitt ac sem eget est egestas volutpat. Curabitur a felis in nunc fringilla tristique.</textarea>  
            </div>
            <div class="form-group d-flex">
                <label for="post-content" hidden>post-content</label>
                <button type="button" class="btn btn-outline-dark btn-sm mr-2" title="Supprimer">
                    <i class="fas fa-trash-alt"></i>
                </button>
                <textarea class="form-control">Donec pede justo, fringilla vel, aliquet nec, vulputate eget, arcu. Vestibulum facilisis, purus nec pulvinar iaculis, ligula mi congue nunc, vitae euismod ligula urna in dolor. Praesent ac sem egetigula mi congue nunc, vitae euismod ligula urna in dolor. Praesent ac sem eget est egestas volutpat. Curabitur a felis in nunc fringilla tristique est egestas volutpat. Curabitur a felis in nunc fringilla tristique.</textarea>  
            </div>

            <div class="my-4">  
                <img class="admin-post-img" src="../../public/images/post-3.jpg" /> 
                <button type="button" class="d-none d-sm-inline-block btn btn-sm btn-primary-custom shadow-sm ml-1"><i class="fas fa-upload mr-1"></i> Modifier l'image</button> 
                <button type="button" class="btn btn-outline-dark btn-sm mr-2" title="Supprimer">
                    <i class="fas fa-trash-alt"></i>
                </button> 
            </div>

            <div class="form-group d-flex">
                <label for="post-content" hidden>post-content</label>
                <button type="button" class="btn btn-outline-dark btn-sm mr-2" title="Supprimer">
                    <i class="fas fa-trash-alt"></i>
                </button>
                <textarea class="form-control">Donec pede justo, fringilla vel, aliquet nec, vulputate eget, arcu. Vestibulum facilisis, purus nec pulvinar iaculis, ligula mi congue nunc, vitae euismod ligula urna in dolor. Praesent ac sem eget est egestas volutpat. Curabitur a felis in nunc fringilla tristique.</textarea>  
            </div>
            <div class="form-group d-flex">
                <label for="post-content" hidden>post-content</label>
                <button type="button" class="btn btn-outline-dark btn-sm mr-2" title="Supprimer">
                    <i class="fas fa-trash-alt"></i>
                </button>
                <textarea class="form-control">Donec pede justo, fringilla vel, aliquet nec, vulputate eget, arcu. Vestibulum facilisis, purus nec pulvinar iaculis, ligula mi congue nunc, vitae euismod ligula urna in dolor. Praesent aigula mi congue nunc, vitae euismod ligula urna in dolor. Praesent ac sem eget est egestas volutpat. Curabitur a felis in nunc fringilla tristiquec sem eget est egestas volutpat. Curabitur a felis in nunc fringilla tristique.</textarea>  
            </div>
            <div class="form-group d-flex">
                <label for="post-content" hidden>post-content</label>
                <button type="button" class="btn btn-outline-dark btn-sm mr-2" title="Supprimer">
                    <i class="fas fa-trash-alt"></i>
                </button>
                <textarea class="form-control">Donec pede justo, fringilla vel, aliquet nec, vulputate eget, arcu. Vestibulum facilisis, purus nec pulvinar iaculis, ligula mi congue nunc, vitae euismod ligula urna in dolor. Praesent ac sem ega mi congue nunc, vitet est egestas volutpat. Curabitur a felis in nunc fringilla tristique.</textarea>  
            </div>
            <div class="my-2">
                <button type="button" id="add-post-content" class="d-none d-sm-inline-block btn btn-sm btn-light shadow-sm"><i class="fas fa-plus fa-sm mr-1"></i> Ajouter un contenu</a>
                </div>

                <hr class="d-none d-lg-block ml-0">

                <h4 class="mb-4">Catégories</h4>
                <div class="">
                    <a class="btn btn-outline-secondary" href="#">Développement<i class="fas fa-times ml-2"></i></a>
                    <a class="btn btn-outline-secondary" href="#">PHP<i class="fas fa-times ml-2"></i></a>
                    <a class="btn btn-outline-secondary" href="#">WordPress<i class="fas fa-times ml-2"></i></a>            
                </div>
                <div class="form-group form-inline mt-3">
                    <label for="new-post-category">Sélectionner / Ajouter une catégorie</label>
                    <input type="text" class="form-control mx-2" id=""/> 
                    <button type="button" class="d-none d-sm-inline-block btn btn-sm btn-light shadow-sm"><i class="fas fa-plus"></i></button>
                </div>

                <hr class="d-none d-lg-block ml-0">

                <input type="submit" class="d-none d-sm-inline-block btn btn-sm btn-primary-custom shadow-sm ml-1" value="Enregistrer les modifications"/> 
        </form>
    </div>
</div>






<?php $content = ob_get_clean(); ?>

<?php require('backend_template.php'); ?>
