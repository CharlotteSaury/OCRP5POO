<?php $this->_title = htmlspecialchars($post->title()); ?>


<!-- $header definition -->

<?php ob_start(); ?>

<header class="post-view-head pb-5">
    <div class="container d-flex h-100 align-items-end">
        <div class="mx-auto text-center">

            <h1 class="mx-auto my-0 text-uppercase"><?= htmlspecialchars($post->title()); ?></h1>
            <div class="d-flex mt-4 align-items-center">
                <div class="avatar mr-3" style="background-image: url('<?= htmlspecialchars($post->avatar()); ?>');">
                </div>
                <div class="text-white-50 posts-informations">
                    <p class="mb-0">Posté par <strong><?= htmlspecialchars($post->pseudo()); ?></strong>
                    </p>
                    <p class="mb-0">le <?= $post->dateCreation(); ?></p>
                    <p class="mb-0">Dernière modification le <?= $post->dateUpdate(); ?></p>
                </div>
            </div>
          </div>

    </div>
  </header>


<?php $this->_header = ob_get_clean(); ?>


<!-- $content definition -->

<?php ob_start(); ?>


<!-- Post Section -->

<section class="bg-light">
    <div class="post-section row container-fluid mx-0">

        <!-- Blog post -->
        <div class="blog-post col-lg-9 col-sm-12 mx-auto">
                    
            <!-- Post  content -->
            <div class="post-content col-sm-10 mx-auto mb-5">
               
                <h2 class="mb-4"><?= htmlspecialchars($post->title()); ?></h2>
                <hr class="d-none d-lg-block ml-0">

                <p><?= htmlspecialchars($post->chapo()); ?></p>

                <?php

                    if ($post->mainImage() != null)
                    {
                    ?>
                        <div class="post-picture my-3" style="background-image: url('<?= htmlspecialchars($post->mainImage()); ?>');">     
                        </div>
                    <?php
                    } 

                foreach ($contents as $content)
                {

                    if ($content->contentTypeId() == 1) 
                    {
                    ?>
                        <div class="post-picture my-3" style="background-image: url('<?= htmlspecialchars($content->content()); ?>');">     
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
            </div>

            <!-- Comments section -->

            <div class="post-comments col-sm-10 mx-auto mb-5" id="comments-section">
                <h2>Commentaires</h2>
                <hr class="d-none d-lg-block ml-0">

                <?php
                if (isset($messageComment))
                {
                    echo '<div class="adminMessage text-dark-50 text-center">' . $messageComment . '</div>';
                }

                if (isset($_SESSION['id']))
                {
                    ?>
                    <div class="comment-form">
                        <form method="POST" action="index.php?action=addComment#comments-section">
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
               
                foreach ($postComments as $postComment)
                {

                ?>
                <div class="comments mt-3">
                    <div class="comment d-flex flex-column flex-sm-row align-items-center mb-4">
                        <div class="avatar mr-md-4 ml-md-2 mx-auto mb-4 mb-md-0" style="background-image: url('<?= htmlspecialchars($postComment->userAvatar()); ?>');">
                        </div>
                        <div class="comment-content text-black-50 text-justify">
                            <div class="comment-infos">
                                <p class=""><strong><?= htmlspecialchars($postComment->userPseudo()); ?></strong> - le <?= $postComment->commentDate(); ?></p>
                            </div>
                            <div class="comment-text">
                                <p class="mb-0"><?= htmlspecialchars($postComment->content()); ?></p>
                            </div>
                        </div>
                    </div>

                    <?php
                    }
                    ?>                    
                </div>
            </div>         

        </div>


        <!-- Right column -->

        <div class="blog-right-col col-lg-3 col-sm-10 pl-3 mx-auto">

            <?php
            if ($post->categories() != null)
            {
               ?>
                <div class="blog-right-col-div mb-5">
                    <h4 class="mb-4">Catégories</h4>
                    <div>

                    <?php
                        foreach($post->categories() as $category)
                        {
                            ?>

                            <a class="btn btn-outline-secondary" href="#"><?= htmlspecialchars($category['name']); ?></a>

                            <?php
                        }
                    ?> 

                    </div>
                </div>

               <?php 
            }
            ?>
            

            <div class="blog-right-col-div recent-posts mb-5">
                <h4 class="mb-4">Articles récents</h4>
                <div class="recent-post">
                    <?php
                    foreach ($recentPosts as $post)
                    {
                    ?>
                        <h5><?= htmlspecialchars($post->title()); ?><em>(posté le <?= $post->dateCreation(); ?>)</em></h5>
                        <p><?= substr(htmlspecialchars($post->chapo()), 0, 30);?>... - <a href="index.php?action=postView&amp;id=<?= htmlspecialchars($post->id()); ?>">En savoir plus</a></p>

                    <?php
                    }
                    ?>                      
                </div>
            </div>

        </div>
    </div>


</section>

<?php $this->_content = ob_get_clean(); ?>