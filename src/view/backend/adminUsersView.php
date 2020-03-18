<!-- $title definition -->

<?php $this->_title = 'Admin - Utilisateurs'; ?>

<!-- Content title definition -->

<?php $this->_contentTitle; ?>

<!-- $content definition -->

<?php ob_start(); ?>

<!-- Sorting Row -->

<div class="row mb-5">
    <div class="col-12 mb-3">
        <a href="index.php?action=adminUsers">Tous (<?= $allUsersNb ?>)</a>
         | 
         <a href="index.php?action=adminUsers&sort=3">Super Admin (<?= $superAdminNb ?>)</a>
         | 
         <a href="index.php?action=adminUsers&sort=1">Admin (<?= $adminNb ?>)</a>
         | 
         <a href="index.php?action=adminUsers&sort=2">Users (<?= $usersNb ?>)</a>                        
    </div>
</div>

<!-- Posts List Row -->

<div class="row">

    <div class="col-12">
        <table class="table table-hover table-responsive">
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
                foreach ($users as $user) {
                    ?> 
                    <tr>
                        <th scope="row"><?= htmlspecialchars($user->getId(), ENT_QUOTES); ?></th>
                        <td><a href="index.php?action=profileUser&amp;id=<?= htmlspecialchars($user->getId(), ENT_QUOTES); ?>"><?= htmlspecialchars($user->getPseudo(), ENT_QUOTES); ?></a></td>
                        <td><?= htmlspecialchars($user->getEmail(), ENT_QUOTES); ?></td>
                        <td><?= htmlspecialchars($user->getRole(), ENT_QUOTES); ?></td>
                        <td><?= htmlspecialchars($user->getRegisterDate(), ENT_QUOTES); ?></td>
                        <td><?= htmlspecialchars($user->getPostsNb(), ENT_QUOTES); ?></td>
                        <td><?= htmlspecialchars($user->getCommentsNb(), ENT_QUOTES); ?></td>
                        <td>
                            <a href="index.php?action=profileUser&amp;id=<?= htmlspecialchars($user->getId(), ENT_QUOTES); ?>" class="btn btn-outline-dark btn-sm" title="Voir">
                                <i class="fas fa-eye"></i>
                            </a>
                            <a href="index.php?action=editUser&amp;id=<?= htmlspecialchars($user->getId(), ENT_QUOTES); ?>" class="btn btn-outline-dark btn-sm" title="Modifier">
                                <i class="fas fa-pencil-alt"></i>
                            </a>
                        </td>
                    </tr>

                    
                    <?php
                }
                ?>

            </tbody>
        </table>
    </div>
    
</div>



<?php $this->_content = ob_get_clean(); ?>

