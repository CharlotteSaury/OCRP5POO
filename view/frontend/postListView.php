<!-- title deinition -->

<?php $this->_title = 'Blog : liste des posts'; ?>


<!-- $header definition -->

<?php ob_start(); ?>

<header class="masthead" id="home">
    <div class="container d-flex h-100 align-items-center">
      <div class="mx-auto text-center">
        <h1 class="mx-auto my-0 text-uppercase">Bienvenue sur mon blog !</h1>
        <h2 class="text-white-50 mx-auto mt-2 mb-5">Actus, développement, et autres choses qui me passent par la tête !</h2>
        <a href="#posts-list" class="btn btn-primary-custom js-scroll-trigger">En savoir plus</a>
      </div>
    </div>
  </header>


<?php $this->_header = ob_get_clean(); ?>


<!-- $content definition -->

<?php ob_start(); ?>


<!-- Postlist Section -->

<section class="profile-section bg-light">
    <div class="row container-fluid mx-auto">

        <!-- Posts list -->
        <div id="posts-list" class="posts-list col-lg-9 col-sm-10 mx-auto">

            <!-- Blog post  -->

            <?php

            foreach ($posts as $post)
            {

            ?>
            
            <div class="col-lg-11 bg-black justify-content-center no-gutters mb-4 py-auto mx-auto">
                <div class="post-text d-flex h-100 flex-column justify-content-between  px-2 px-md-4 py-3 py-md-5">
                    <div class="d-flex flex-md-row flex-column">

                        <?php
                        if ($post->mainImage() != null)
                        {
                        ?>
                            <div class="post-list-picture col-md-4 col-12" style="background-image: url('<?= htmlspecialchars($post->mainImage()); ?>')">     
                            </div>
                        <?php
                        }
                        ?>
                        
                        <div class="post-chapo col-md-8 col-12 text-center text-lg-left ml-md-3 pt-4">
                            <h4 class="text-white"><?= htmlspecialchars($post->title()); ?></h4>
                            <p class="mb-0 text-white-50"><?= substr(htmlspecialchars($post->chapo()), 0, 200); ?>... - <a href="index.php?action=postView&amp;id=<?= $post->id(); ?>">En savoir plus</a></p>
                            <hr class="d-none d-lg-block mb-0 ml-0">
                        </div>
                    </div>
                        
                    <div class="post-author d-flex mt-4 align-items-center">
                        <div class="avatar mr-3" style="background-image: url('<?= htmlspecialchars($post->avatar()); ?>')">
                        </div>
                        <div class="text-white-50 posts-informations">
                            <p class="mb-0">Posté par <strong><?= htmlspecialchars($post->pseudo()); ?></strong></p>
                            <p class="mb-0">le <?= $post->dateCreation(); ?></p>
                            <p class="mb-0">Dernière modification le <?= $post->dateUpdate(); ?></p>
                        </div>
                    </div>
                </div>
            </div>

            <?php

            }
            ?>

            <!-- Post list navigation -->

            <div class="row container justify-content-center mt-5">
                <nav aria-label="post-navigation">
                    <ul class="pagination post-pagination">
                        <li class="page-item">
                            <?php 
                            if (isset($_GET['page']) && ($_GET['page'] > 1))
                            {
                                echo '<a class="page-link" href="index.php?action=listPosts&page=' . ((int)htmlspecialchars($_GET['page'])-1) . '&amp;postsPerPage=' . $postsPerPage . '#posts-list" aria-label="Previous">';
                            ?>
                                    <span aria-hidden="true">&laquo;</span>
                                    <span class="sr-only">Previous</span>
                                </a>

                            <?php
                            }
                            else 
                            {
                            ?>
                                <button class="page-link" disabled aria-label="Previous">
                                    <span aria-hidden="true">&laquo;</span>
                                    <span class="sr-only">Previous</span>
                                </button>
                            <?php
                            }
                            ?>
                        </li>

                        <?php
                        for ($page=1 ; $page<=$page_number ; $page++)
                        {
                            echo '<li class="page-item"><a class="page-link" href="index.php?action=listPosts&amp;page='. $page . '&amp;postsPerPage=' . $postsPerPage . '#posts-list">' . $page . '</a></li>';
                        }

                        ?>

                        <li class="page-item">
                            <?php 
                            if (isset($_GET['page']) && ($_GET['page'] < $page_number))
                            {
                                echo '<a class="page-link" href="index.php?action=listPosts&page=' . ((int)htmlspecialchars($_GET['page'])+1) . '&amp;postsPerPage=' . $postsPerPage . '#posts-list" aria-label="Next">';
                            ?>
                                    <span aria-hidden="true">&raquo;</span>
                                    <span class="sr-only">Next</span>
                                </a>
                            <?php
                            }
                            elseif (!isset($_GET['page']) && $page_number > 1) 
                            {
                                echo '<a class="page-link" href="index.php?action=listPosts&page=2&amp;postsPerPage=' . $postsPerPage . '#posts-list" aria-label="Next">';
                            ?>
                                    <span aria-hidden="true">&raquo;</span>
                                    <span class="sr-only">Next</span>
                                </a>
                            <?php
                            } 
                            else
                            {
                            ?>
                                <button type="button" class="page-link" disabled aria-label="Next">
                                    <span aria-hidden="true">&raquo;</span>
                                    <span class="sr-only">Next</span>
                                </button>
                            <?php
                            }
                            ?>
                        </li>
                        
                    </ul>
                </nav>
            </div>
            

        </div>

        <!-- Right column -->

        <div class="blog-right-col col-lg-3 col-sm-10 pl-3 mx-auto">
            <div class="blog-right-col-div nb-posts mb-5">
                <h4 class="mb-4">Nombre de posts par page</h4>
                <form action="index.php#posts-list" method="GET">
                    <input type="hidden" name="action" value="listPosts"/>
                    <label for="postsPerPage" hidden>Nombre de posts par page</label>
                    <select class="form-control block" id="postsPerPage" name="postsPerPage">
                        <?php
                        if (isset($_GET['postsPerPage']))
                        {
                            echo '<option selected disabled>' . htmlspecialchars($_GET['postsPerPage']) . '</option>';
                            switch ($_GET['postsPerPage'])
                            {
                                case 3:
                                    echo '<option value="5">5</option>';
                                    echo '<option value="10">10</option>';
                                    break;
                                case 5:
                                    echo '<option value="3">3</option>';
                                    echo '<option value="10">10</option>';
                                    break;
                                case 10:
                                    echo '<option value="3">3</option>';
                                    echo '<option value="5">5</option>';
                                    break;
                            }
                        }
                        else 
                        {
                        ?>
                            <option value="3" selected disabled>3</option>
                            <option value="5">5</option>
                            <option value="10">10</option>
                        <?php
                        }
                        ?>
                    </select>
                    <input class="btn btn-primary-custom" type="submit" value="Afficher"/>
                </form>
            </div>
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

            <!--<div class="blog-right-col-div post-categories">
                <h4 class="mb-4">Tri par catégorie</h4>
                <form action="index.php#posts-list" method='GET'>
                    <input type="hidden" name="action" value="listPosts"/>
                    <?php
                    while ($donnees = $categories ->fetch())
                    {
                    ?>
                        <div class="form-check">
                            <input type="checkbox" class="form-check-input" id="category<?= htmlspecialchars($donnees['category_id']); ?>" name="cat" value="<?= strtolower(htmlspecialchars($donnees['category_name'])); ?>"/>
                            <label class="form-check-label" for="category<?= htmlspecialchars($donnees['category_id']); ?>" ><?= htmlspecialchars($donnees['category_name']); ?></label>
                        </div>
                    <?php    
                    }
                    $categories -> closeCursor();
                    ?>
                    <input  class="btn btn-primary-custom" type="submit" value="Trier" />
                </form>
            </div>-->
        </div>
    </div>


</section>


<?php $this->_content = ob_get_clean(); ?>
