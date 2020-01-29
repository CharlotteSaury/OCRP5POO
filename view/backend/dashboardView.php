<!-- $title definition -->

<?php $title = 'Tableau de bord'; ?>


<!-- Content title definition -->

<?php $contentTitle = 'Tableau de bord'; ?>

<!-- $content definition -->

<?php ob_start(); ?>

<!-- Content Card Row -->
<div class="row">

    <!-- Post number card -->
    <div class="col-xl-4 col-md-12 mb-4">
        <div class="card border-left-primary-custom shadow h-100 py-2">
            <div class="card-body">
                <div class="row no-gutters align-items-center">
                    <div class="col mr-2">
                        <div class="text-xs font-weight-bold text-primary-custom text-uppercase mb-1">Nombre de posts</div>
                        <div class="h5 mb-0 font-weight-bold text-gray-800"><?= $publishedPostsNb ?> publiés / <?= $totalPostsNb ?></div>
                    </div>
                    <div class="col-auto">
                        <i class="fas fa-newspaper fa-2x text-gray-300"></i>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <!-- Comment number card -->
    <div class="col-xl-4 col-md-12 mb-4">
        <div class="card border-left-primary-custom shadow h-100 py-2">
            <div class="card-body">
                <div class="row no-gutters align-items-center">
                    <div class="col mr-2">
                        <div class="text-xs font-weight-bold text-primary-custom text-uppercase mb-1">Nombre de commentaires</div>
                        <div class="h5 mb-0 font-weight-bold text-gray-800"><?= $approvedCommentsNb ?> approuvés / <?= $totalCommentsNb ?></div>
                    </div>
                    <div class="col-auto">
                        <i class="fas fa-comments fa-2x text-gray-300"></i>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <!-- User number card -->
    <div class="col-xl-4 col-md-12 mb-4">
        <div class="card border-left-primary-custom shadow h-100 py-2">
            <div class="card-body">
                <div class="row no-gutters align-items-center">
                    <div class="col mr-2">
                        <div class="text-xs font-weight-bold text-primary-custom text-uppercase mb-1">Nombre d'utilisateurs</div>
                        <div class="h5 mb-0 font-weight-bold text-gray-800"><?= $usersNb ?></div>
                    </div>
                    <div class="col-auto">
                        <i class="fas fa-users fa-2x text-gray-300"></i>
                    </div>
                </div>
            </div>
        </div>
    </div>
                    
</div>

<!-- Recent Post Row -->

<div class="row">

    <div class="col-12">
        <div class="card shadow mb-4">
            <!-- Card Header - Accordion -->
            <a href="#recentPostsCard" class="d-block card-header py-3" data-toggle="collapse" role="button" aria-expanded="true" aria-controls="recentPostsCard">
                <h6 class="m-0 font-weight-bold text-primary-custom">Articles récents</h6>
            </a>
            <!-- Card Content - Collapse -->
            <div class="collapse show" id="recentPostsCard">
                <div class="card-body">
                    <table class="table table-hover">
                        <thead>
                         <tr>
                            <th scope="col">Id</th>
                            <th scope="col">Auteur</th>
                            <th scope="col">Titre</th>
                            <th scope="col">Chapô</th>
                            <th scope="col">Date de création</th>
                            <th scope="col">Action</th>
                        </tr>
                        </thead>
                        <tbody>
                            <?php

                            while ($donnees = $recentPosts->fetch())
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
                                <th scope="row"><?= htmlspecialchars($donnees['postId']); ?></th>
                                <td><?= htmlspecialchars($donnees['first_name']); ?> <?= htmlspecialchars($donnees['last_name']); ?></td>
                                <td><?= htmlspecialchars($donnees['title']); ?></td>
                                <td><?= substr(htmlspecialchars($donnees['chapo']), 0, 50); ?>...</td>
                                <td><?= $donnees['date_creation']; ?></td>
                                <td>
                                    <button type="button" class="btn btn-outline-dark btn-sm" title="Voir">
                                        <i class="fas fa-eye"></i>
                                    </button>
                                    <button type="button" class="btn btn-outline-dark btn-sm" title="Modifier">
                                        <i class="fas fa-pencil-alt"></i>
                                    </button>
                                    <button type="button" class="btn btn-outline-dark btn-sm" title="Supprimer">
                                        <i class="fas fa-trash-alt"></i>
                                    </button>
                                </td>
                            </tr>

                            <?php
                            }

                            $recentPosts->closeCursor();

                            ?>
                        </tbody>
                    </table>
                </div>
            </div>
        </div>
    </div>
</div>

<!-- Comments-Users Row -->

<div class="row">

    <div class="col-lg-6">
        <div class="card shadow mb-4">

            <!-- Card Header - Accordion -->
            <a href="#recentCommentsCard" class="d-block card-header py-3" data-toggle="collapse" role="button" aria-expanded="true" aria-controls="recentCommentsCard">
                <h6 class="m-0 font-weight-bold text-primary-custom">Commentaires récents</h6>
            </a>

            <!-- Card Content - Collapse -->
            <div class="collapse show" id="recentCommentsCard">
                <div class="card-body">
                    <table class="table table-hover">
                        <thead>
                            <tr>
                                <th scope="col">Id</th>
                                <th scope="col">Auteur</th>
                                <th scope="col">Contenu</th>
                                <th scope="col">Date</th>
                                <th scope="col">Action</th>
                            </tr>
                        </thead>
                        <tbody>
                            <?php

                            while ($donnees = $recentComments->fetch())
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
                                <td><?= htmlspecialchars($donnees['first_name']); ?> <?= htmlspecialchars($donnees['last_name']); ?></td>
                                <td><?= substr(htmlspecialchars($donnees['content']), 0, 50); ?></td>
                                <td><?= $donnees['commentDate']; ?></td>
                                <td>
                                    <a href="adminPostView.php" class="btn btn-outline-dark btn-sm" title="Voir l'article concerné">
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
                            $recentComments->closeCursor();
                            ?>

                        </tbody>
                    </table>
                </div>
            </div>
        </div>
    </div>

    <div class="col-lg-6">
        <div class="card shadow mb-4">
            <!-- Card Header - Accordion -->
            <a href="#recentUsersCard" class="d-block card-header py-3" data-toggle="collapse" role="button" aria-expanded="true" aria-controls="recentUsersCard">
                <h6 class="m-0 font-weight-bold text-primary-custom">Utilisateurs</h6>
            </a>
            <!-- Card Content - Collapse -->
            <div class="collapse show" id="recentUsersCard">
                <div class="card-body">
                    <table class="table table-hover">
                        <thead>
                         <tr>
                            <th scope="col">Id</th>
                            <th scope="col">Pseudo</th>
                            <th scope="col">Email</th>
                            <th scope="col">Rôle</th>
                            <th scope="col">Action</th>
                        </tr>
                        </thead>
                        <tbody>
                            <?php

                            while ($donnees = $recentUsers->fetch())
                            {
                            ?>
                            <tr>
                                <th scope="row"><?= htmlspecialchars($donnees['userId']); ?></th>
                                <td><?= htmlspecialchars($donnees['pseudo']); ?></td>
                                <td><?= htmlspecialchars($donnees['email']); ?></td>
                                <td><?= htmlspecialchars($donnees['role']); ?></td>
                                <td>
                                    <button type="button" class="btn btn-outline-dark btn-sm" title="Voir">
                                        <i class="fas fa-eye"></i>
                                    </button>
                                    <button type="button" class="btn btn-outline-dark btn-sm" title="Modifier">
                                        <i class="fas fa-pencil-alt"></i>
                                    </button>
                                    <button type="button" class="btn btn-outline-dark btn-sm" title="Supprimer">
                                        <i class="fas fa-trash-alt"></i>
                                    </button>
                                </td>
                            </tr>

                            <?php
                            }
                            $recentUsers->closeCursor();
                            ?>
                            
                        </tbody>
                    </table>
                </div>
            </div>
        </div>
    </div>

</div>


<?php $content = ob_get_clean(); ?>

<?php require('backend_template.php'); ?>
