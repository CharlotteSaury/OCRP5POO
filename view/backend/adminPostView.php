<!-- $title definition -->

<?php $title = 'Admin - Article'; ?>

<!-- Content title definition -->

<?php $contentTitle = ''; ?>

<!-- $content definition -->

<?php ob_start(); ?>

<?php
while ($donnees = $postInfos->fetch())
{
?>
<div class="row adminPostView">
    <div class="col-11 mx-auto my-3">
        <div class="d-flex flex-row justify-content-between">
            <h2 class="mb-4"><?= htmlspecialchars($donnees['title']); ?></h2>
            <div>
                <?php 
                if ($donnees['status'] == 1)
                {
                ?>

                    <a href="index.php?action=publishPost&amp;id=<?= htmlspecialchars($donnees['postId']); ?>&amp;status=<?= htmlspecialchars($donnees['status']); ?>" title="Ne plus publier" class="mr-2"><i class="fas fa-toggle-on"></i></a>
                    
                <?php
                }
                else
                {
                ?>

                    <a href="index.php?action=publishPost&amp;id=<?= htmlspecialchars($donnees['postId']); ?>&amp;status=<?= htmlspecialchars($donnees['status']); ?>" title="Publier"  class="mr-2"><i class="fas fa-toggle-off"></i></a>
                    
                <?php
                }
                ?>
                <a href="index.php?action=editPostView&amp;id=<?= htmlspecialchars($donnees['postId']); ?>" class="btn btn-outline-dark btn-sm" title="Modifier">
                    <i class="fas fa-pencil-alt"></i>
                </a>
                <a href="index.php?action=deletePost&amp;id=<?= htmlspecialchars($donnees['postId']); ?>" class="btn btn-outline-dark btn-sm" title="Supprimer">
                    <i class="fas fa-trash-alt"></i>
                </a>
            </div>
        </div>

        <hr class="d-none d-lg-block ml-0">

        <div class="post-content post-content-text text-black-50 text-justify">
            <p class="">Posté par <strong><?= htmlspecialchars($donnees['first_name']); ?> <?= htmlspecialchars($donnees['last_name']); ?></strong></p>
            <p class="mb-0">le <?= htmlspecialchars($donnees['date_creation']); ?></p>
            <p class="mb-0">Dernière modification le <?= htmlspecialchars($donnees['date_update']); ?></p>
        </div>

        <hr class="d-none d-lg-block ml-0">

        <div class="post-content post-content-text text-black-50 text-justify">
            <p class=""><strong>Chapô : </strong><?= htmlspecialchars($donnees['chapo']); ?></p>
        </div>

        <hr class="d-none d-lg-block ml-0">

        <?php

            if ($donnees['main_image'] != null)
            {
            ?>

                <div class="my-4 text-center">
                    <p class=""><strong>Image principale : </strong></p>  
                    <img class="admin-post-img" src="<?= htmlspecialchars($donnees['main_image']); ?>" />  
                </div>

            <?php
            }
        }
        $postInfos->closeCursor();

        while ($donnees = $postContents->fetch())
        {
            if ($donnees['content_type'] == 1) 
            {
            ?>
                <div class="my-4 text-center">   
                    <img class="admin-post-img" src="<?= htmlspecialchars($donnees['content']); ?>" />  
                </div>

            <?php
            }
            else
            {
            ?> 
                <div class="post-content post-content-text text-black-50 text-justify">
                    <p><?= htmlspecialchars($donnees['content']); ?></p>
                </div>                        
            <?php
            }
        }
        $postContents->closeCursor();
        ?>

        <hr class="d-none d-lg-block ml-0"> 
    </div>
</div>

<div class="row">
    <div class="col-11 mx-auto">
        <h4 class="mb-4">Catégories</h4>
        <div class="">

            <?php
            while ($donnees = $postCategories->fetch())
            {
                echo '<a class="btn btn-outline-secondary mr-2" href="#">' . htmlspecialchars($donnees["categoryName"]) . '</a>';     
            }

            $postCategories->closeCursor();
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
            while ($donnees = $postComments->fetch())
            {
                if ($donnees['status'] == 0)
                {

                    echo '<div class="comment-content text-black-50 text-justify mt-4 mt-4 unapproved-comment">';
                }
                else
                {
                    echo '<div class="comment-content text-black-50 text-justify mt-4">';
                }
                ?>

                    <div class="comment-infos">
                        <p class=""><strong><?= htmlspecialchars($donnees["first_name"]); ?> <?= htmlspecialchars($donnees["last_name"]); ?></strong> - le <?= htmlspecialchars($donnees["commentDate"]); ?></p>
                    </div>
                    <div class="comment-text">
                        <p class="mb-0"><?= htmlspecialchars($donnees["commentContent"]); ?></p>
                    </div>
                
                <?php
                if ($donnees['status'] == 0)
                {
                ?>
                    <div class="mt-2">
                        <a href='index.php?action=approveComment&id=<?= htmlspecialchars($donnees["commentId"]); ?>' class="btn btn-outline-dark btn-sm" title="Approuver">
                            <i class="fas fa-check"></i>
                        </a>
                        <a href='index.php?action=deleteComment&id=<?= htmlspecialchars($donnees["commentId"]); ?>' class="btn btn-outline-dark btn-sm" title="Supprimer">
                            <i class="fas fa-times"></i>
                        </a>
                    </div>
                </div>   
                <?php
                }

            }
            $postComments->closeCursor();
            ?>
            
            
        </div>


    </div>
</div>  






<?php $content = ob_get_clean(); ?>

<?php require('backend_template.php'); ?>
