<!-- $title definition -->

<?php $this->_title = 'Admin - Ajouter un article'; ?>

<!-- Content title definition -->

<?php $this->_contentTitle = 'Ajouter un nouvel article'; ?>

<!-- $content definition -->

<?php ob_start(); ?>

<!-- New post form Row -->
<div class="row">
    <div class="col-12">
        <form action="" method="post">
            <div class="form-group">
                <label for="new-post-title" hidden>Titre</label>
                <input type="text" class="form-control" id="" placeholder="Titre de l'article *" required/>
            </div>
            <div class="form-group">
                <label for="new-post-chapo" hidden>Chapô</label>
                <input type="text" class="form-control" id="" placeholder="Chapo *" required/>
            </div>
            <div class="form-group mt-4">
                <label for="main-post-img">Image principale</label>
                <button type="button" class="d-none d-sm-inline-block btn btn-sm btn-primary-custom shadow-sm ml-1"><i class="fas fa-upload mr-1"></i> Ajouter</button>
            </div>
            <div class="form-group">
                <label for="new-post-content">Contenu *</label>
                <textarea class="form-control" id="new-post-content" rows="3"  required></textarea>
            </div>
            <div class="my-2">
                <button type="submit" name="addParagraph" class="d-none d-sm-inline-block btn btn-sm btn-light shadow-sm"><i class="fas fa-plus fa-sm mr-1"></i> Ajouter un paragraphe</button>
                <button type="submit" name="addPicture" class="d-none d-sm-inline-block btn btn-sm btn-light shadow-sm"><i class="fas fa-plus fa-sm mr-1"></i> Ajouter une image</button>
            </div>
            <div class="form-group form-inline">
                <label for="new-post-category">Sélectionner / Ajouter une catégorie</label>
                <input type="text" class="form-control mx-2" id=""/> 
                <button type="button" class="d-none d-sm-inline-block btn btn-sm btn-light shadow-sm"><i class="fas fa-plus"></i></button>
            </div>                
            <div class="mt-4">
                <button type="submit" name="newPost" class="btn btn-primary-custom">Enregistrer en tant que brouillon</button>
                <button type="submit" name="newPublishedPost" class="btn btn-primary-custom">Publier</button>
            </div>
                                
        </form>

    </div>
</div>



<?php $this->_content = ob_get_clean(); ?>

