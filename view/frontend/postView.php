<?php $title = 'Titre post'; ?>


<!-- $header definition -->

<?php ob_start(); ?>

<header class="post-view-head pb-5">
    <div class="container d-flex h-100 align-items-end">
        <div class="mx-auto text-center">
        
            <?php

            while ($donnees = $postInfos->fetch())
            {

            ?>

            <h1 class="mx-auto my-0 text-uppercase"><?= htmlspecialchars($donnees['title']); ?></h1>
            <div class="d-flex mt-4 align-items-center">
                <div class="avatar mr-3" style="background-image: url('<?= htmlspecialchars($donnees['avatar']); ?>');">
                </div>
                <div class="text-white-50 posts-informations">
                    <p class="mb-0">Posté par 
                        <strong>
                        <?php
                        if (isset($donnees['first_name'], $donnees['last_name']))
                        {
                            echo htmlspecialchars($donnees['first_name']) . ' ' . htmlspecialchars($donnees['last_name']);
                        }
                        else
                        {
                            echo htmlspecialchars($donnees['pseudo']);
                        }
                        ?>
                        </strong>
                    </p>
                    <p class="mb-0">le <?= $donnees['date_creation']; ?></p>
                    <p class="mb-0">Dernière modification le <?= $donnees['date_update']; ?></p>
                </div>
            </div>
          </div>

    </div>
  </header>


<?php $header = ob_get_clean(); ?>


<!-- $content definition -->

<?php ob_start(); ?>


<!-- Post Section -->

<section class="bg-light">
    <div class="post-section row container-fluid mx-0">

        <!-- Blog post -->
        <div class="blog-post col-lg-9 col-sm-12 mx-auto">
                    
            <!-- Post  content -->
            <div class="post-content col-sm-10 mx-auto mb-5">

                <?php
                if (isset($message))
                {
                    echo '<div class="adminMessage text-dark-50 text-center">' . $message . '</div>';
                }
                ?>
                
                <h2 class="mb-4"><?= htmlspecialchars($donnees['title']); ?></h2>
                <hr class="d-none d-lg-block ml-0">

                <p><?= htmlspecialchars($donnees['chapo']); ?></p>

                <?php

                    if ($donnees['main_image'] != null)
                    {
                    ?>
                        <div class="post-picture my-3" style="background-image: url('<?= htmlspecialchars($donnees['main_image']); ?>');">     
                        </div>
                    <?php
                    } 
                }

                $postInfos->closeCursor();

                while ($donnees = $postContents->fetch())
                {

                    if (($donnees['content_type'] == 1)) 
                    {
                    ?>
                        <div class="post-picture my-3" style="background-image: url('<?= htmlspecialchars($donnees['content']); ?>');">     
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
            </div>

            <!-- Comments section -->

            <div class="post-comments col-sm-10 mx-auto mb-5">
                <h2>Commentaires</h2>
                <hr class="d-none d-lg-block ml-0">

                <?php
                if (isset($_SESSION['id']))
                {
                    ?>
                    <div class="comment-form">
                        <form method="POST" action="index.php?action=addComment">
                            <div class="form-row">
                                <textarea class="form-control mt-4 mb-4 pb-5" name="content" placeholder="Votre commentaire"></textarea>
                            </div>
                            <div class="form-row">
                                <div class="col">
                                    <input type="hidden" name="userId" value="<?= $_SESSION['id']; ?>" />
                                    <input type="hidden" name="postId" value="<?= $postId; ?>" />

                                </div>
                            </div>
                            <input class="btn btn-primary-custom my-4" type="submit" value="Commenter"/>
                        </form>
                    </div>
                    <?php
                }
                else
                {
                    echo '<a href="index.php?action=connexionView" class="btn btn-primary-custom my-4">Se connecter pour laisser un commentaire</a>';
                }
                ?>

                

                <?php
               
                while ($donnees = $postComments -> fetch())
                {

                ?>
                <div class="comments mt-3">
                    <div class="comment d-flex flex-column flex-sm-row align-items-center mb-4">
                        <div class="avatar mr-md-4 ml-md-2 mx-auto mb-4 mb-md-0" style="background-image: url('<?= htmlspecialchars($donnees['avatar']); ?>');">
                        </div>
                        <div class="comment-content text-black-50 text-justify">
                            <div class="comment-infos">
                                <p class=""><strong><?= htmlspecialchars($donnees['pseudo']); ?></strong> - le <?= $donnees['commentDate']; ?></p>
                            </div>
                            <div class="comment-text">
                                <p class="mb-0"><?= htmlspecialchars($donnees['commentContent']); ?></p>
                            </div>
                        </div>
                    </div>

                    <?php
                    }

                    $postComments -> closeCursor();

                    ?>                    
                </div>
            </div>         

        </div>


        <!-- Right column -->

        <div class="blog-right-col col-lg-3 col-sm-10 pl-3 mx-auto">

            <div class="blog-right-col-div mb-5">
                <h4 class="mb-4">Catégories</h4>
                <div>

                    <?php
                    while ($donnees = $postCategories->fetch())
                    {
                    ?>

                    <a class="btn btn-outline-secondary" href="#"><?= htmlspecialchars($donnees['categoryName']); ?></a>

                    <?php
                    }
                    $postCategories -> closeCursor();
                    ?> 

                </div>
            </div>

            <div class="blog-right-col-div recent-posts mb-5">
                <h4 class="mb-4">Articles récents</h4>
                <div class="recent-post">
                    <?php
                    while ($donnees = $recentPosts -> fetch())
                    {
                    ?>
                        <h5><?= htmlspecialchars($donnees['title']); ?><em>(posté le <?= $donnees['date_creation']; ?>)</em></h5>
                        <p><?= substr(htmlspecialchars($donnees['chapo']), 0, 30);?>... - <a href="index.php?action=postView&amp;id=<?= htmlspecialchars($donnees['postId']); ?>">En savoir plus</a></p>

                    <?php
                    }
                    $recentPosts -> closeCursor();
                    ?>                      
                </div>
            </div>

        </div>
    </div>


</section>

<?php $content = ob_get_clean(); ?>

<?php require('frontend_template.php'); ?>