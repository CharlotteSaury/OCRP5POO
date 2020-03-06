<?php $this->_title = htmlspecialchars($post->getTitle()); ?>


<!-- $header definition -->

<?php ob_start(); ?>

<header class="post-view-head pb-5">
    <div class="container d-flex h-100 align-items-end">
        <div class="mx-auto text-center">

            <h1 class="mx-auto my-0 text-uppercase"><?= htmlspecialchars($post->getTitle()); ?></h1>
            <div class="d-flex mt-4 align-items-center">
                <div class="avatar mr-3" style="background-image: url('<?= htmlspecialchars($post->getAvatar()); ?>');">
                </div>
                <div class="text-white-50 posts-informations">
                    <p class="mb-0">Posté par <strong><?= htmlspecialchars($post->getPseudo()); ?></strong>
                    </p>
                    <p class="mb-0">le <?= $post->getDateCreation(); ?></p>
                    <p class="mb-0">Dernière modification le <?= $post->getDateUpdate(); ?></p>
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
    <div class="post-section row container-fluid mx-0 px-0">

        <!-- Blog post -->
        <div class="blog-post col-md-9 mx-auto px-0">
                    
            <!-- Post  content -->
            <div class="post-content px-4 px-md-5 mb-5">
               
                <h2 class="mb-4"><?= htmlspecialchars($post->getTitle()); ?></h2>
                <hr class="d-none d-lg-block ml-0">

                <p><?= htmlspecialchars($post->getChapo()); ?></p>

                <?php

                    if ($post->getMainImage() != null) {
                        ?>
                        <div class="post-picture my-3" style="background-image: url('<?= htmlspecialchars($post->getMainImage()); ?>');">     
                        </div>
                        <?php
                    } 

                foreach ($contents as $content) {

                    if ($content->getContentTypeId() == 1) {
                        ?>
                        <div class="post-picture my-3" style="background-image: url('<?= htmlspecialchars($content->getContent()); ?>');">     
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
            </div>

            <!-- Comments section -->

            <div class="post-comments px-4 px-md-5 mb-5" id="comments-section">
                <h2>Commentaires</h2>
                <hr class="d-none d-lg-block ml-0">

                <?php
                if (isset($messageComment)) {
                    echo '<div class="adminMessage text-dark-50 text-center">' . $messageComment . '</div>';
                }

                if (isset($errors['content'])) {
                    echo '<div class="adminMessage text-dark-50 text-center">' . $errors['content'] . '</div>';                    
                }

                if ($session->get('id')) {
                    ?>
                    <div class="comment-form">
                        <form method="POST" action="index.php?action=addComment#comments-section">
                            <div class="form-row">
                                <textarea class="form-control mt-4 mb-4 pb-5" name="content" placeholder="Votre commentaire"></textarea>
                            </div>
                            <div class="form-row">
                                <div class="col">
                                    <input type="hidden" name="postId" value="<?= $postId; ?>" />
                                </div>
                            </div>
                            <input class="btn btn-primary-custom mb-4" type="submit" value="Commenter"/>
                        </form>
                    </div>
                    <?php
                
                } else {
                    echo '<a href="index.php?action=connexionView" class="btn btn-primary-custom my-4">Se connecter pour laisser un commentaire</a>';
                }
                ?>

                

                <?php
               
                foreach ($postComments as $postComment) {
                    ?>
                    <div class="comments mt-3">
                        <div class="comment d-flex flex-column flex-sm-row align-items-center mb-4">
                            <div class="avatar mr-md-4 ml-md-2 mx-auto mb-4 mb-md-0" style="background-image: url('<?= htmlspecialchars($postComment->getUserAvatar()); ?>');">
                            </div>
                            <div class="comment-content text-black-50 text-justify">
                                <div class="comment-infos">
                                    <p class=""><strong><?= htmlspecialchars($postComment->getUserPseudo()); ?></strong> - le <?= $postComment->getCommentDate(); ?></p>
                                </div>
                                <div class="comment-text">
                                    <p class="mb-0"><?= htmlspecialchars($postComment->getContent()); ?></p>
                                </div>
                            </div>
                        </div>
                    </div>
                    <?php
                }
                ?>                    
                
            </div>         

        </div>


        <!-- Right column -->

        <div class="blog-right-col col-md-3 col-12 mx-auto px-4 pl-md-0 pr-md-4">

            <?php
            if ($post->getCategories() != null) {
                ?>
                <div class="blog-right-col-div mb-3">
                    <h4 class="mb-4">Catégories</h4>
                    <div>

                    <?php
                        foreach($post->getCategories() as $category) {
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
                    foreach ($recentPosts as $post) {
                    ?>
                        <h5><?= htmlspecialchars($post->getTitle()); ?><em> (posté le <?= $post->getDateCreation(); ?>)</em></h5>
                        <p><?= substr(htmlspecialchars($post->getChapo()), 0, 30);?>... - <a href="index.php?action=postView&amp;id=<?= htmlspecialchars($post->getId()); ?>">En savoir plus</a></p>

                    <?php
                    }
                    ?>                      
                </div>
            </div>

        </div>
    </div>


</section>

<?php $this->_content = ob_get_clean(); ?>