<!-- $title definition -->

<?php $this->_title = 'Admin - Article'; ?>

<!-- Content title definition -->

<?php $this->_contentTitle = ''; ?>

<!-- $content definition -->

<?php ob_start(); ?>

<div class="row adminPostView">
    <div class="col-11 mx-auto my-3">
        <div class="d-flex flex-row justify-content-between">
            <h2 class="mb-4"><?= htmlspecialchars($post->title()); ?></h2>
            <div>
                <?php 
                if ($post->status() == 2)
                {
                ?>

                    <a href="index.php?action=publishPost&amp;id=<?= htmlspecialchars($post->id()); ?>&amp;status=<?= htmlspecialchars($post->status()); ?>" title="Ne plus publier" class="mr-2"><i class="fas fa-toggle-on"></i></a>
                    
                <?php
                }
                else
                {
                ?>

                    <a href="index.php?action=publishPost&amp;id=<?= htmlspecialchars($post->id()); ?>&amp;status=<?= htmlspecialchars($post->status()); ?>" title="Publier"  class="mr-2"><i class="fas fa-toggle-off"></i></a>
                    
                <?php
                }
                ?>
                <a href="index.php?action=editPostView&amp;id=<?= htmlspecialchars($post->id()); ?>" class="btn btn-outline-dark btn-sm" title="Modifier">
                    <i class="fas fa-pencil-alt"></i>
                </a>
                <a href="index.php?action=deletePost&amp;id=<?= htmlspecialchars($post->id()); ?>" class="btn btn-outline-dark btn-sm" title="Supprimer">
                    <i class="fas fa-trash-alt"></i>
                </a>
            </div>
        </div>

        <hr class="d-none d-lg-block ml-0">

        <div class="post-content post-content-text text-black-50 text-justify">
            <p class="">Posté par <strong><?= htmlspecialchars($post->pseudo()); ?></strong></p>
            <p class="mb-0">le <?= htmlspecialchars($post->dateCreation()); ?></p>
            <p class="mb-0">Dernière modification le <?= htmlspecialchars($post->dateUpdate()); ?></p>
        </div>

        <hr class="d-none d-lg-block ml-0">

        <div class="post-content post-content-text text-black-50 text-justify">
            <p class=""><strong>Chapô : </strong><?= htmlspecialchars($post->chapo()); ?></p>
        </div>

        <hr class="d-none d-lg-block ml-0">

        <?php

            if ($post->mainImage() != null)
            {
            ?>

                <div class="my-4 text-center">
                    <p class=""><strong>Image principale : </strong></p>  
                    <img class="admin-post-img" src="<?= htmlspecialchars($post->mainImage()); ?>" />  
                </div>

            <?php
            }

        foreach ($contents as $content)
        {
            if ($content->contentTypeId() == 1) 
            {
            ?>
                <div class="my-4 text-center">   
                    <img class="admin-post-img" src="<?= htmlspecialchars($content->content()); ?>" />  
                </div>

            <?php
            }
            else
            {
            ?> 
                <div class="post-content post-content-text text-black-50 text-justify">
                    <p><?= htmlspecialchars($content->content()); ?></p>
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
            if ($post->categories() != null)
            {
                foreach ($post->categories() as $category)
                {
                    echo '<a class="btn btn-outline-secondary mr-2" href="#">' . htmlspecialchars($category['name']) . '</a>';
                }
            }
            else
            {
                echo '<p>Pas de catégorie associée à ce post.</p>';
            }
            ?> 

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

            <?php
            foreach($postComments as $comment)
            {
                if ($comment->status() == 0)
                {

                    echo '<div class="comment-content text-black-50 text-justify mt-4 mt-4 unapproved-comment">';
                }
                else
                {
                    echo '<div class="comment-content text-black-50 text-justify mt-4">';
                }
                ?>

                    <div class="comment-infos">
                        <p><strong><?= htmlspecialchars($comment->userPseudo()); ?></strong> - le <?= htmlspecialchars($comment->commentDate()); ?></p>
                    </div>
                    <div class="comment-text">
                        <p class="mb-0"><?= htmlspecialchars($comment->content()); ?></p>
                    </div>
                
                <?php
                if ($comment->status() == 0)
                {
                ?>
                    <div class="mt-2">
                        <a href='index.php?action=approveCommentView&id=<?= htmlspecialchars($comment->id()); ?>&post=<?= htmlspecialchars($comment->postId()); ?>' class="btn btn-outline-dark btn-sm" title="Approuver">
                            <i class="fas fa-check"></i>
                        </a>
                        <a href='index.php?action=deleteComment&id=<?= htmlspecialchars($comment->id()); ?>' class="btn btn-outline-dark btn-sm" title="Supprimer">
                            <i class="fas fa-times"></i>
                        </a>
                    </div>
                </div>   
                <?php
                }
            }
            ?>
            
        </div>


    </div>
</div>  

<?php $this->_content = ob_get_clean(); ?>

