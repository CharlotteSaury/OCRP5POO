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
                    <td><a href="#"><?= htmlspecialchars($donnees['postTitle']); ?></a></td>
                    <td><?= htmlspecialchars($donnees['first_name']); ?> <?= substr(($donnees['last_name']),0,1); ?></td>
                    <td><?= htmlspecialchars($donnees['content']); ?></td>
                    <td><?= htmlspecialchars($donnees['commentDate']); ?></td>
                    <td>
                        <a href="adminPostView.php" class="btn btn-outline-dark btn-sm" title="Voir l'article">
                            <i class="fas fa-eye"></i>
                        </a>
                        <button type="button" class="btn btn-outline-dark btn-sm" title="Approuver">
                            <i class="fas fa-check"></i>
                        </button>
                        <button type="button" class="btn btn-outline-dark btn-sm" title="Supprimer">
                            <i class="fas fa-times"></i>
                        </button>
                    </td>
                </tr>

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
