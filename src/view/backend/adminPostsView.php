<!-- title deinition -->

<?php $this->_title = 'Admin - Tous les articles'; ?>

<!-- $content definition -->

<?php ob_start(); ?>


<!-- Sorting Row -->

<div class="row mb-5">
    <div class="col-12">
        <a href="index.php?action=adminPosts">Tous (<?= $totalPostsNb ?>)</a> | <a href="index.php?action=adminPosts&status=1">Non publiés (<?= $unpublishedPostsNb ?>)</a>
    </div>
    <div class="col-12 mb-3">
        Trier par date

        <?php
        if (isset($get)) {

            if (!$get->get('status')) {

                if (!$get->get('date')) {

                    echo '<a href="index.php?action=adminPosts&date=asc" title="Trier du plus ancien au plus récent"><i class="fas fa-sort-down fa-2x ml-2"></i></a>';
                
                } else {

                    if ($get->get('date') == 'asc') {
                       echo '<a href="index.php?action=adminPosts" title="Trier du plus récent au plus ancien"><i class="fas fa-sort-up fa-2x ml-2 mb-0"></i></a>';
                   }
               }
            } else {
                if ($get->get('status') == 1) {

                    if (!$get->get('date')) {
                        echo '<a href="index.php?action=adminPosts&status=1&date=asc" title="Trier du plus ancien au plus récent"><i class="fas fa-sort-down fa-2x ml-2"></i></a>';
                    
                    } else {
                        if ($get->get('date') == 'asc') {
                           echo '<a href="index.php?action=adminPosts&status=1" title="Trier du plus récent au plus ancien"><i class="fas fa-sort-up fa-2x ml-2 mb-0"></i></a>';
                       }
                   }
               }
            }
        }
        

        ?>
    </div>
</div>

<!-- Posts List Row -->

<div class="row">

    <div class="col-12">
        <table class="table table-hover table-responsive">
            <thead>
                <tr>
                    <th scope="col">Id</th>
                    <th scope="col">Status</th>
                    <th scope="col">Titre</th>
                    <th scope="col">Auteur</th>
                    <th scope="col">Chapô</th>
                    <th scope="col"  class="responsive-table-custom">Date de création</th>
                    <th scope="col" title="Nombre de commentaires approuvés/total"><i class="fas fa-comments"></i></th>
                </tr>
            </thead>
            <tbody>

                <?php

                foreach ($posts as $post) {
                    if ($post->getApprovedCommentsNb() != $post->getCommentsNb()) {
                        echo '<tr class="table-success-custom">';
                    } else {
                        echo '<tr>';
                    }
                ?>
                
                    <th scope="row"><?= htmlspecialchars($post->getId(), ENT_QUOTES); ?></th>

                    <?php 
                    if ($post->getStatus() == 2) {
                        ?>

                        <td><a href="index.php?action=publishPost&amp;id=<?= htmlspecialchars($post->getId(), ENT_QUOTES); ?>&amp;currstatus=<?= htmlspecialchars($post->getStatus(), ENT_QUOTES); ?>" title="Ne plus publier"><i class="fas fa-toggle-on"></i></a></td>
                        
                        <?php
                    
                    } else {
                        ?>

                        <td><a href="index.php?action=publishPost&amp;id=<?= htmlspecialchars($post->getId(), ENT_QUOTES); ?>&amp;currstatus=<?= htmlspecialchars($post->getStatus(), ENT_QUOTES); ?>" title="Publier"><i class="fas fa-toggle-off"></i></a></td>
                        
                        <?php
                    }
                    ?>

                    <td>
                        <a href="index.php?action=adminPostView&amp;id=<?= htmlspecialchars($post->getId(), ENT_QUOTES); ?>"><?= htmlspecialchars($post->getTitle(), ENT_QUOTES); ?></a>
                        <hr class="postlist-divider">
                        <a href="index.php?action=adminPostView&amp;id=<?= htmlspecialchars($post->getId(), ENT_QUOTES); ?>" class="btn btn-outline-dark btn-sm" title="Voir">
                            <i class="fas fa-eye"></i>
                        </a>
                        <a href="index.php?action=editPostView&amp;id=<?= htmlspecialchars($post->getId(), ENT_QUOTES); ?>" class="btn btn-outline-dark btn-sm" title="Modifier">
                            <i class="fas fa-pencil-alt"></i>
                        </a>
                        <a data-toggle="modal" data-target="#deletePostModal<?= htmlspecialchars($post->getId(), ENT_QUOTES); ?>" class="btn btn-outline-dark btn-sm" title="Supprimer">
                            <i class="fas fa-trash-alt"></i>
                        </a>
                    </td>
                    <td><?= htmlspecialchars($post->getPseudo(), ENT_QUOTES); ?></td>
                    <td>
                        <?= substr(htmlspecialchars($post->getChapo(), ENT_QUOTES), 0, 40); ?>...
                        
                    <?php
                    if ($post->getCategories() != null) {
                        ?>
                        <hr class="postlist-divider">
                        <p>Catégorie(s) :</p>
                    
                        <?php
                        foreach ($post->getCategories() as $category) {
                            echo '<a class="btn btn-outline-secondary ml-2" href="">' . htmlspecialchars($category['name'], ENT_QUOTES) . '</a>';
                        }
                    }
                    ?>

                        
                    </td>
                    <td  class="responsive-table-custom"><?= htmlspecialchars($post->getDateCreation(), ENT_QUOTES); ?></td>
                    <td><?= htmlspecialchars($post->getApprovedCommentsNb(), ENT_QUOTES); ?>/<?= htmlspecialchars($post->getCommentsNb(), ENT_QUOTES); ?></td>
                    
                </tr>
                <!-- deletePost Modal-->
                <div class="modal fade" id="deletePostModal<?= htmlspecialchars($post->getId(), ENT_QUOTES); ?>" tabindex="-1" role="dialog" aria-labelledby="deletePostLabel" aria-hidden="true">
                    <div class="modal-dialog" role="document">
                        <div class="modal-content">
                            <div class="modal-header">
                                <h5 class="modal-title" id="deletePostLabel">Voulez-vous vraiment supprimer ce post ?</h5>
                                <button class="close" type="button" data-dismiss="modal" aria-label="Close">
                                    <span aria-hidden="true">×</span>
                                </button>
                            </div>
                            <div class="modal-body">Cliquez sur "Valider" pour supprimer définitivement ce post</div>
                            <div class="modal-footer">
                                <button class="btn btn-secondary" type="button" data-dismiss="modal">Annuler</button>
                                <a class="btn btn-primary-custom" href="index.php?action=deletePost&amp;id=<?= htmlspecialchars($post->getId(), ENT_QUOTES); ?>&amp;ct=<?= $session->get('csrf_token'); ?>">Valider</a>
                            </div>
                        </div>
                    </div>
                </div>

                <?php
                }
                ?>
                
            </tbody>
        </table>
    </div>
    
</div>


<?php $this->_content = ob_get_clean(); ?>

