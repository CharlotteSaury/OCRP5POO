<!-- $title definition -->

<?php $title = 'Admin - Utilisateurs'; ?>

<!-- Content title definition -->

<?php $contentTitle = 'Utilisateurs'; ?>

<!-- $content definition -->

<?php ob_start(); ?>

<!-- Sorting Row -->

<div class="row mb-5">
    <div class="col-12">
        <form class="form-inline sorting-form">
            <label for="admin-postslist-date" hidden>Tri par date</label>
            <select class="form-control block" id="admin-postslist-date">
                <option value="desc" selected>Du plus récent au plus ancien</option>
                <option value="asc">Du plus ancien au plus récent</option>
            </select>
            <label for="admin-postslist-category" hidden>Tri par category</label>
            <select class="form-control block" id="admin-postslist-category">
                <option value="categorie" selected disabled>Rôle</option>
                <option value="admin">Administrateur</option>
                <option value="user">Utilisateur</option>
            </select>
            <input class="btn btn-primary-custom" type="submit" name="admin-postslist-sorting" value="Filtrer">
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
                    <th scope="col">Pseudo</th>
                    <th scope="col">Email</th>
                    <th scope="col">Rôle</th>
                    <th scope="col">Date de création</th>
                    <th scope="col"><i class="fas fa-newspaper" title="Nombre d'articles postés"></i></th>
                    <th scope="col"><i class="fas fa-comments" title="Nombre de commentaires"></i></th>
                    <th scope="col">Action</th>
                </tr>
            </thead>                                      
            <tbody>

                <?php
                while ($donnees = $allUsers->fetch())
                {
                ?> 
                <tr>
                    <th scope="row"><?= htmlspecialchars($donnees['userId']); ?></th>
                    <td><a href="index.php?action=profileUser&amp;id=<?= htmlspecialchars($donnees['userId']); ?>"><?= htmlspecialchars($donnees['pseudo']); ?></a></td>
                    <td><?= htmlspecialchars($donnees['email']); ?></td>
                    <td><?= htmlspecialchars($donnees['role']); ?></td>
                    <td><?= htmlspecialchars($donnees['register_date']); ?></td>
                    <td><?= htmlspecialchars($donnees['postsNb']); ?></td>
                    <td><?= htmlspecialchars($donnees['commentsNb']); ?></td>
                    <td>
                        <a href="index.php?action=profileUser&amp;id=<?= htmlspecialchars($donnees['userId']); ?>" class="btn btn-outline-dark btn-sm" title="Voir">
                            <i class="fas fa-eye"></i>
                        </a>
                        <a href="index.php?action=editUser&amp;id=<?= htmlspecialchars($donnees['userId']); ?>" class="btn btn-outline-dark btn-sm" title="Modifier">
                            <i class="fas fa-pencil-alt"></i>
                        </a>
                    </td>
                </tr>

                
                <?php
                }
                $allUsers->closeCursor();
                ?>

            </tbody>
        </table>
    </div>
    
</div>



<?php $content = ob_get_clean(); ?>

<?php require('backend_template.php'); ?>
