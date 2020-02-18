<!-- $title definition -->

<?php $title = 'Admin - Commentaires'; ?>

<!-- Content title definition -->

<?php $contentTitle = 'Commentaires'; ?>

<!-- $content definition -->

<?php ob_start(); ?>


<!-- Sorting Row -->

<div class="row mb-5">
    <div class="col-12">
        <form class="form-inline sorting-form">
            <label for="admin-comments-date">Tri par date</label>
            <select class="form-control block" id="admin-comments-date">
                <option value="desc" selected>Du plus récent au plus ancien</option>
                <option value="asc">Du plus ancien au plus récent</option>
            </select>
            <label for="admin-comments-articledate">Tri par article</label>
            <select class="form-control block" id="admin-comments-articledate">
                <option value="desc" selected>Du plus récent au plus ancien</option>
                <option value="asc">Du plus ancien au plus récent</option>
            </select>
            <input class="btn btn-primary-custom" type="submit" name="admin-comments-sorting" value="Filtrer">
        </form>                           
    </div>
</div>

<!-- Posts List Row -->

<div class="row">

    <div class="col-12">
        <table class="table table-hover">
            <thead>
                <tr>
                    <th scope="col">Id</th>
                    <th scope="col">Article</th>
                    <th scope="col">Auteur</th>
                    <th scope="col">Contenu</th>
                    <th scope="col">Date</th>
                    <th scope="col">Action</th>
                </tr>
            </thead>
            <tbody>
                <?php

                while ($donnees = $allComments->fetch())
                {
                    if ($donnees['status'] == 1)
                    {
                        echo '<tr>';
                    }
                    else
                    {
                        echo '<tr class="table-success-custom">';
                    }
                ?>
                    <th scope="row"><?= htmlspecialchars($donnees['commentId']); ?></th>
                    <td><a href="index.php?action=adminPostView&amp;id=<?= htmlspecialchars($donnees['postId']); ?>"><?= htmlspecialchars($donnees['postTitle']); ?></a></td>
                    <td><?= htmlspecialchars($donnees['pseudo']); ?></td>
                    <td><?= htmlspecialchars($donnees['content']); ?></td>
                    <td><?= htmlspecialchars($donnees['commentDate']); ?></td>
                    <td>
                        <a href="index.php?action=adminPostView&amp;id=<?= htmlspecialchars($donnees['postId']); ?>" class="btn btn-outline-dark btn-sm" title="Voir l'article">
                            <i class="fas fa-eye"></i>
                        </a>

                        <?php
                        if ($donnees['status'] == 0)
                        {
                        ?>

                        <a href="index.php?action=approveComment&amp;id=<?= htmlspecialchars($donnees['commentId']); ?>" class="btn btn-outline-dark btn-sm" title="Approuver">
                            <i class="fas fa-check"></i>
                        </a>

                        <?php
                        }
                        ?>

                        <a data-toggle="modal" data-target="#deleteCommentModal<?= htmlspecialchars($donnees['commentId']); ?>" class="btn btn-outline-dark btn-sm" title="Supprimer">
                            <i class="fas fa-times"></i>
                        </a>
                    </td>
                </tr>
                <!-- deleteComment Modal-->
                <div class="modal fade" id="deleteCommentModal<?= htmlspecialchars($donnees['commentId']); ?>" tabindex="-1" role="dialog" aria-labelledby="deleteCommentLabel" aria-hidden="true">
                    <div class="modal-dialog" role="document">
                        <div class="modal-content">
                            <div class="modal-header">
                                <h5 class="modal-title" id="deleteCommentLabel">Voulez-vous vraiment supprimer ce commentaire ?</h5>
                                <button class="close" type="button" data-dismiss="modal" aria-label="Close">
                                    <span aria-hidden="true">×</span>
                                </button>
                            </div>
                            <div class="modal-body">Cliquez sur "Valider" pour supprimer définitivement ce commentaire</div>
                            <div class="modal-footer">
                                <button class="btn btn-secondary" type="button" data-dismiss="modal">Annuler</button>
                                <a class="btn btn-primary-custom" href="index.php?action=deleteComment&amp;id=<?= htmlspecialchars($donnees['commentId']); ?>">Valider</a>
                            </div>
                        </div>
                    </div>
                </div>
                <?php
                }
                $allComments->closeCursor();
                ?>

            </tbody>
        </table>
    </div>

    

</div>


<?php $content = ob_get_clean(); ?>

<?php require('backend_template.php'); ?>
