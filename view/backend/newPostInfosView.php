<!-- $title definition -->

<?php $title = 'Admin - Ajouter un article'; ?>

<!-- Content title definition -->

<?php $contentTitle = 'Ajouter un nouvel article'; ?>

<!-- $content definition -->

<?php ob_start(); ?>

<!-- New post form Row -->
<div class="row">
    <div class="col-12">
        <form action="index.php?action=newPostInfos" method="post">
            <div class="form-group">
                <label for="new-post-title" hidden>Titre</label>
                <input type="text" class="form-control" name="title" placeholder="Titre de l'article *" required/>
            </div>
            <div class="form-group">
                <label for="new-post-chapo" hidden>Chapô</label>
                <textarea class="form-control" name="chapo" required>Chapo *</textarea>
            </div>
            <div class="form-group">
                <label for="new-post-author" hidden>Auteur</label>
                <input type="text" class="form-control" name="user_id" placeholder="UserId *" required/>
            </div>
            <div class="form-group mt-4">
                <label for="main-post-img">Image principale</label>
                <input type="text" name="main_image" class="form-control" placeholder="url de l'image principale"/>
            </div>         
                           
            <div class="mt-4">
                <button type="submit" class="btn btn-primary-custom">Continuer</button>
            </div>
                                
        </form>

    </div>
</div>



<?php $content = ob_get_clean(); ?>

<?php require('backend_template.php'); ?>
