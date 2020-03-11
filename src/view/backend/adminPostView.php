<!-- $title definition -->

<?php $this->_title = 'Admin - Article'; ?>

<!-- Content title definition -->

<?php $this->_contentTitle = ''; ?>

<!-- $content definition -->

<?php ob_start(); ?>

<div class="row adminPostView">
    <div class="col-11 mx-auto my-3">
        <div class="d-flex flex-column flex-md-row justify-content-between mb-4">
            <h3 class="mb-4"><?= htmlspecialchars($post->getTitle()); ?></h3>
            <div>
                <?php 
                if ($post->getStatus() == 2) {
                    ?>
                    <a href="index.php?action=publishPost&amp;id=<?= htmlspecialchars($post->getId()); ?>&amp;status=<?= htmlspecialchars($post->getStatus()); ?>" title="Ne plus publier" class="mr-2"><i class="fas fa-toggle-on"></i></a>
                    <?php

                } else {
                    ?>
                    <a href="index.php?action=publishPost&amp;id=<?= htmlspecialchars($post->getId()); ?>&amp;status=<?= htmlspecialchars($post->getStatus()); ?>" title="Publier"  class="mr-2"><i class="fas fa-toggle-off"></i></a>
                    <?php
                }
                ?>
                <a href="index.php?action=editPostView&amp;id=<?= htmlspecialchars($post->getId()); ?>" class="btn btn-outline-dark btn-sm" title="Modifier">
                    <i class="fas fa-pencil-alt"></i>
                </a>
                <a href="index.php?action=deletePost&amp;id=<?= htmlspecialchars($post->getId()); ?>" class="btn btn-outline-dark btn-sm" title="Supprimer">
                    <i class="fas fa-trash-alt"></i>
                </a>
            </div>
        </div>

        <hr class="d-none d-lg-block ml-0">

        <div class="post-content post-content-text text-black-50 text-justify">
            <p class="">Posté par <strong><?= htmlspecialchars($post->getPseudo()); ?></strong></p>
            <p class="mb-0">le <?= htmlspecialchars($post->getDateCreation()); ?></p>
            <p class="mb-0">Dernière modification le <?= htmlspecialchars($post->getDateUpdate()); ?></p>
        </div>

        <hr class="d-none d-lg-block ml-0">

        <div class="post-content post-content-text text-black-50 text-justify">
            <p class=""><strong>Chapô : </strong><?= htmlspecialchars($post->getChapo()); ?></p>
        </div>

        <hr class="d-none d-lg-block ml-0">

        <?php

            if ($post->getMainImage() != null) {
                ?>

                <div class="my-4 text-center">
                    <p class=""><strong>Image principale : </strong></p>  
                    <img class="admin-post-img" src="<?= htmlspecialchars($post->getMainImage()); ?>" />  
                </div>

                <?php
            }

        foreach ($contents as $content) {
            if ($content->getContentTypeId() == 1) {
                ?>
                <div class="my-4 text-center">   
                    <img class="admin-post-img" src="<?= htmlspecialchars($content->getContent()); ?>" />  
                </div>

                <?php
            } else {
                ?> 
                <div class="post-content post-content-text text-black-50 text-justify">
                    <p><?= htmlspecialchars($content->getContent()); ?></p>
                </div>                        
                <?php
            }
        }
        ?>

        <hr class="d-none d-lg-block ml-0"> 
    </div>
</div>

<div class="row">
    <div class="col-11 mx-auto">
        <h4 class="mb-4">Catégories</h4>
        <div>

            <?php
            if ($post->getCategories() != null) {
                foreach ($post->getCategories() as $category) {
                    ?>
                    <a class="btn btn-outline-secondary mr-2" href="#"><?= htmlspecialchars($category['name']); ?></a>
                    <?php
                }
            } else {
                ?>
                <p>Pas de catégorie associée à ce post.</p>
                <?php
            }
            ?> 

        </div>
    </div>
</div>

<hr class="d-none d-lg-block ml-0">

<!-- Comments section -->
<div class="row mt-4">
    <div class="post-comments col-11 mx-auto mb-5">
        <h4>Commentaires</h4>
        <hr class="d-none d-lg-block ml-0">

        <div class="comments mt-5">

            <?php
            foreach($postComments as $comment) {
                if ($comment->getStatus() == 1) {
                    ?>
                    <div class="comment-content text-black-50 text-justify mt-4 mt-4 unapproved-comment">
                    <?php
                } else {
                    ?>
                    <div class="comment-content text-black-50 text-justify mt-4">
                    <?php
                }
                    ?>

                        <div class="comment-infos">
                            <p><strong><?= htmlspecialchars($comment->getUserPseudo()); ?></strong> - le <?= htmlspecialchars($comment->getCommentDate()); ?></p>
                        </div>
                        <div class="comment-text">
                            <p class="mb-0"><?= htmlspecialchars($comment->getContent()); ?></p>
                        </div>
                
                <?php
                if ($comment->getStatus() == 1) {
                    ?>
                    <div class="mt-2">
                        <a href='index.php?action=approveCommentView&id=<?= htmlspecialchars($comment->getId()); ?>&post=<?= htmlspecialchars($comment->getPostId()); ?>' class="btn btn-outline-dark btn-sm" title="Approuver">
                            <i class="fas fa-check"></i>
                        </a>
                        <a href='index.php?action=deleteComment&id=<?= htmlspecialchars($comment->getId()); ?>' class="btn btn-outline-dark btn-sm" title="Supprimer">
                            <i class="fas fa-times"></i>
                        </a>
                    </div>   
                    <?php
                }
                ?>
                </div>
                <?php
            }
            ?>
            
        </div>


    </div>
</div>

<?php $this->_content = ob_get_clean(); ?>

