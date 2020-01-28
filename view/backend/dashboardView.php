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
                        <div class="h5 mb-0 font-weight-bold text-gray-800">9</div>
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
                        <div class="h5 mb-0 font-weight-bold text-gray-800">12</div>
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
                        <div class="h5 mb-0 font-weight-bold text-gray-800">18</div>
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
                            <tr>
                                <th scope="row">10</th>
                                <td>Charlotte S</td>
                                <td>Sed in</td>
                                <td>Fusce vulputate eleifend sapien. Sed aliquam ultrices mauris...</td>
                                <td>29/12/2019</td>
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
                            <tr>
                                <th scope="row">9</th>
                                <td>Charlotte S</td>
                                <td>Suspendisse faucibus nunc</td>
                                <td>Ut leo. Proin sapien ipsum, porta a, auctor quis, euismod ut, mi...</td>
                                <td>25/12/2019</td>
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
                            <tr>
                                <th scope="row">8</th>
                                <td>Céline D</td>
                                <td>Maecenas vestibulum mollis diam</td>
                                <td>Sed aliquam ultrices mauris. Donec interdum, metus et...</td>
                                <td>19/12/2019</td>
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
                            <tr class="table-success-custom">
                                <th scope="row">44</th>
                                <td>Charlotte S</td>
                                <td>Sed in</td>
                                <td>29/12/2019</td>
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
                            <tr class="table-success-custom">
                                <th scope="row">43</th>
                                <td>Charlotte S</td>
                                <td>Suspendisse faucibus nunc</td>
                                <td>25/12/2019</td>
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
                            <tr class="table-success-custom">
                                <th scope="row">42</th>
                                <td>Céline D</td>
                                <td>Maecenas vestibulum mollis diam</td>
                                <td>19/12/2019</td>
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
                            <tr>
                                <th scope="row">41</th>
                                <td>Céline D</td>
                                <td>Maecenas vestibulum mollis diam</td>
                                <td>19/12/2019</td>
                                <td>                               
                                    <a href="adminPostView.php" class="btn btn-outline-dark btn-sm" title="Voir l'article concerné">
                                        <i class="fas fa-eye"></i>
                                    </a>         
                                    <button type="button" class="btn btn-outline-dark btn-sm" title="Supprimer">
                                        <i class="fas fa-times"></i>
                                    </button>
                                </td>
                            </tr>
                            <tr>
                                <th scope="row">40</th>
                                <td>Céline D</td>
                                <td>Maecenas vestibulum mollis diam</td>
                                <td>19/12/2019</td>
                                <td>
                                    <a href="adminPostView.php" class="btn btn-outline-dark btn-sm" title="Voir l'article concerné">
                                        <i class="fas fa-eye"></i>
                                    </a>
                                    <button type="button" class="btn btn-outline-dark btn-sm" title="Supprimer">
                                        <i class="fas fa-times"></i>
                                    </button>
                                </td>
                            </tr>
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
                            <tr>
                                <th scope="row">18</th>
                                <td>Celiiine</td>
                                <td>celiiine@email.fr</td>
                                <td>user</td>
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
                            <tr>
                                <th scope="row">17</th>
                                <td>georgesClooney</td>
                                <td>clooney@hollywood.com</td>
                                <td>user</td>
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
                            <tr>
                                <th scope="row">16</th>
                                <td>JoeDé</td>
                                <td>joe.dalton@email.com</td>
                                <td>user</td>
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
                            <tr>
                                <th scope="row">15</th>
                                <td>Charlotte S</td>
                                <td>saury.charlotte@wanadoo.fr</td>
                                <td>admin</td>
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
                            <tr>
                                <th scope="row">14</th>
                                <td>jacadi</td>
                                <td>jacadi@email.fr</td>
                                <td>user</td>
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
                        </tbody>
                    </table>
                </div>
            </div>
        </div>
    </div>

</div>


<?php $content = ob_get_clean(); ?>

<?php require('backend_template.php'); ?>
