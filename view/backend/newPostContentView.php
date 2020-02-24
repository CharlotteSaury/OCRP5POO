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

            <?php
            while ($donnees = $postInfos->fetch())
            {
            ?>
            
            <div class="form-group">
                <label for="new-post-title">Titre</label>
                <input type="text" class="form-control" name="title" placeholder="<?= htmlspecialchars($donnees['title']); ?>"/>
            </div>
            <div class="form-group">
                <label for="new-post-chapo">Chapô</label>
                <textarea class="form-control" name="chapo"><?= htmlspecialchars($donnees['chapo']); ?></textarea>
            </div>
            <div>
                <p>Auteur : <?= htmlspecialchars($donnees['first_name']); ?> <?= htmlspecialchars($donnees['last_name']); ?></p>
                <p>Date de création : <?= htmlspecialchars($donnees['date_creation']); ?></p>
            </div>

            <?php
                if ($donnees['main_image'] != null)
                {
                ?>

                    <div class="my-4 text-center">
                        <p class=""><strong>Image principale : </strong></p>  
                        <img class="admin-post-img mb-4" src="<?= htmlspecialchars($donnees['main_image']); ?>" />
                        <div>

                            <a data-toggle="modal" data-target="#updateMainPictureModal<?= htmlspecialchars($donnees['postId']); ?>" class="d-none d-sm-inline-block btn btn-sm btn-primary-custom shadow-sm ml-1"><i class="fas fa-upload mr-1"></i> Modifier l'image principale</a> 
                            <a data-toggle="modal" data-target="#deleteMainPictureModal<?= htmlspecialchars($donnees['postId']); ?>" class="btn btn-outline-dark btn-sm mr-2" title="Supprimer">
                                <i class="fas fa-trash-alt"></i>
                            </a>
                        </div>  
                    </div>

                <?php
                }
                else
                {
                ?>
                    <div class="my-4 text-center">
                        <p class=""><strong>Image principale : </strong></p>
                        <p>Aucune image sélectionnée</p>
                        <a data-toggle="modal" data-target="#updateMainPictureModal<?= htmlspecialchars($donnees['postId']); ?>" class="d-none d-sm-inline-block btn btn-sm btn-primary-custom shadow-sm ml-1"><i class="fas fa-upload mr-1"></i> Ajouter une image</a>
                    </div>
                <?php
                }
            }
            $postInfos->closeCursor();
            ?>

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

