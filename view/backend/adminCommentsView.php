<!-- $title definition -->

<?php $this->_title = 'Admin - Commentaires'; ?>

<!-- $content definition -->

<?php ob_start(); ?>


<!-- Sorting Row -->

<div class="row mb-5">
    <div class="col-12 mb-3">
        <a href="index.php?action=adminComments">Tous (<?= $totalCommentsNb ?>)</a> | <a href="index.php?action=adminComments&sort=unapproved">Non approuvés (<?= $unapprovedCommentsNb ?>)</a>
             | trier par date 

        <?php
        if (!isset($_GET['sort']))
        {
            if (!isset($_GET['date']))
            {
                echo '<a href="index.php?action=adminComments&date=asc" title="Trier du plus ancien au plus récent"><i class="fas fa-sort-down fa-2x ml-2"></i></a>';
            }
            else
            {
                if ($_GET['date'] == 'asc')
                {
                    echo '<a href="index.php?action=adminComments" title="Trier du plus récent au plus ancien"><i class="fas fa-sort-up fa-2x ml-2 mb-0"></i></a>';
                }
            }
        }
        else
        {
            if ($_GET['sort'] == 'unapproved')
            {
                if (!isset($_GET['date']))
                {
                    echo '<a href="index.php?action=adminComments&sort=unapproved&date=asc" title="Trier du plus ancien au plus récent"><i class="fas fa-sort-down fa-2x ml-2"></i></a>';
                }
                else
                {
                    if ($_GET['date'] == 'asc')
                    {
                        echo '<a href="index.php?action=adminComments&sort=unapproved" title="Trier du plus récent au plus ancien"><i class="fas fa-sort-up fa-2x ml-2 mb-0"></i></a>';
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


<?php $this->_content = ob_get_clean(); ?>

