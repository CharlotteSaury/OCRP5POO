<!-- $title definition -->

<?php $this->_title = 'Admin - Ajouter un article'; ?>

<!-- Content title definition -->

<?php $this->_contentTitle = 'Ajouter un nouvel article'; ?>

<!-- $content definition -->

<?php ob_start(); ?>

<!-- New post form Row -->
<div class="row">
    <div class="col-12">
        <form enctype="multipart/form-data" action="index.php?action=newPostInfos&amp;ct=<?= $session->get('csrf_token'); ?>" method="post">
            <div class="form-group">
                <label for="new-post-title" hidden>Titre</label>
                <input type="text" class="form-control" name="title" placeholder="Titre de l'article *" required/>
                <?= isset($session->get('errors')['title']) ? '<p>' . $session->get('errors')['title'] . '</p>' : ''; ?>

            </div>
            <div class="form-group">
                <label for="new-post-chapo" hidden>Chapô</label>
                <textarea class="form-control" name="chapo" required>Chapo *</textarea>
                <?= isset($session->get('errors')['chapo']) ? '<p>' . $session->get('errors')['chapo'] . '</p>' : ''; ?>
                
            </div>
             
            <div class="form-group mt-4">
                <label for="main_image">Image principale</label>
                <input name="picture" type="file" />
            </div>         
                           
            <div class="mt-4">
                <button type="submit" class="btn btn-primary-custom">Continuer</button>
            </div>
                                
        </form>

    </div>
</div>



<?php $this->_content = ob_get_clean(); ?>

