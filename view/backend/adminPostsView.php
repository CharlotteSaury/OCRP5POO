<!-- title deinition -->

<?php $title = 'Admin - Tous les articles'; ?>

<!-- Content title definition -->

<?php $contentTitle = 'Tous les articles'; ?>

<!-- $content definition -->

<?php ob_start(); ?>


<!-- Sorting Row -->

<div class="row mb-5">
    <div class="col-12 mb-3">
        <a href="">Tous (6)</a> | <a href="">Publiés (3)</a>
    </div>
    <div class="col-12">
        <form class="form-inline sorting-form">
            <label for="admin-postslist-date" hidden>Tri par date</label>
            <select class="form-control block" id="admin-postslist-date">
                <option value="desc" selected>Du plus récent au plus ancien</option>
                <option value="asc">Du plus ancien au plus récent</option>
            </select>
            <label for="admin-postslist-category" hidden>Tri par category</label>
            <select class="form-control block" id="admin-postslist-category">
                <option value="categorie" selected disabled>Catégorie</option>
                <option value="php">PHP</option>
                <option value="javascript">Javascript</option>
                <option value="développement">Développement</option>
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
                    <th scope="col">Status</th>
                    <th scope="col">Titre</th>
                    <th scope="col" class="responsive-table-custom">Auteur</th>
                    <th scope="col" class="responsive-table-custom">Chapô</th>
                    <th scope="col">Date de création</th>
                    <th scope="col" class="responsive-table-custom"><i class="fas fa-comments"></i></th>
                </tr>
            </thead>
            <tbody>
                <tr>
                    <th scope="row">6</th>
                    <td><i class="fas fa-toggle-on"></i></td>
                    <td>
                        <a href="adminPostView.php">Sed in</a>
                        <hr class="postlist-divider">
                        <a href="adminPostView.php" class="btn btn-outline-dark btn-sm" title="Voir">
                            <i class="fas fa-eye"></i>
                        </a>
                        <button type="button" class="btn btn-outline-dark btn-sm" title="Modifier">
                            <i class="fas fa-pencil-alt"></i>
                        </button>
                        <button type="button" class="btn btn-outline-dark btn-sm" title="Supprimer">
                            <i class="fas fa-trash-alt"></i>
                        </button>
                    </td>
                    <td class="responsive-table-custom">Charlotte S</td>
                    <td class="responsive-table-custom">
                        Fusce vulputate eleifend sapien. Sed aliquam ultrices mauris...
                        <hr class="postlist-divider">
                        Catégorie(s) : 
                        <a class="btn btn-outline-secondary" href="#">Développement</a>
                    </td>
                    <td>29/12/2019</td>
                    <td class="responsive-table-custom">0</td>
                </tr>
                <tr>
                    <th scope="row">5</th>
                    <td><i class="fas fa-toggle-on"></i></td>
                    <td>
                        <a href="adminPostView.php">Suspendisse faucibus nunc</a>
                        <hr class="postlist-divider">
                        <a href="adminPostView.php" class="btn btn-outline-dark btn-sm" title="Voir">
                            <i class="fas fa-eye"></i>
                        </a>
                        <button type="button" class="btn btn-outline-dark btn-sm" title="Modifier">
                            <i class="fas fa-pencil-alt"></i>
                        </button>
                        <button type="button" class="btn btn-outline-dark btn-sm" title="Supprimer">
                            <i class="fas fa-trash-alt"></i>
                        </button>
                    </td>                   
                    <td class="responsive-table-custom">Charlotte S</td>
                    <td class="responsive-table-custom">
                        Ut leo. Proin sapien ipsum, porta a, auctor quis, euismod ut, mi...
                        <hr class="postlist-divider">
                        Catégorie(s) : 
                        <a class="btn btn-outline-secondary" href="#">Développement</a>
                        <a class="btn btn-outline-secondary" href="#">PHP</a>
                    </td>
                    <td>25/12/2019</td>
                    <td class="responsive-table-custom"><a href="#">2</a></td>
                </tr>
                <tr>
                    <th scope="row">4</th>
                    <td><i class="fas fa-toggle-on"></i></td>
                    <td>
                        <a href="adminPostView.php">Maecenas vestibulum mollis diam</a>
                        <hr class="postlist-divider">
                        <a href="adminPostView.php" class="btn btn-outline-dark btn-sm" title="Voir">
                            <i class="fas fa-eye"></i>
                        </a>
                        <button type="button" class="btn btn-outline-dark btn-sm" title="Modifier">
                            <i class="fas fa-pencil-alt"></i>
                        </button>
                        <button type="button" class="btn btn-outline-dark btn-sm" title="Supprimer">
                            <i class="fas fa-trash-alt"></i>
                        </button>
                    </td>
                    <td class="responsive-table-custom">Céline D</td>
                    <td class="responsive-table-custom">
                        Sed aliquam ultrices mauris. Donec interdum, metus et...
                        <hr class="postlist-divider">
                        Catégorie(s) : 
                        <a class="btn btn-outline-secondary" href="#">Développement</a>
                        <a class="btn btn-outline-secondary" href="#">PHP</a>
                    </td>
                    <td>19/12/2019</td>
                    <td class="responsive-table-custom">0</td>
                </tr>
                <tr>
                    <th scope="row">3</th>
                    <td><i class="fas fa-toggle-off"></i></td>                                        
                    <td>
                        <a href="adminPostView.php">Sed in</a>
                        <hr class="postlist-divider">
                        <a href="adminPostView.php" class="btn btn-outline-dark btn-sm" title="Voir">
                            <i class="fas fa-eye"></i>
                        </a>
                        <button type="button" class="btn btn-outline-dark btn-sm" title="Modifier">
                            <i class="fas fa-pencil-alt"></i>
                        </button>
                        <button type="button" class="btn btn-outline-dark btn-sm" title="Supprimer">
                            <i class="fas fa-trash-alt"></i>
                        </button>
                    </td>
                    <td class="responsive-table-custom">Charlotte S</td>
                    <td class="responsive-table-custom">
                        Fusce vulputate eleifend sapien. Sed aliquam ultrices mauris...
                        <hr class="postlist-divider">
                        Catégorie(s) :
                    </td>
                    <td>29/12/2019</td>
                    <td class="responsive-table-custom"><a href="#">2</a></td>
                </tr>
                <tr>
                    <th scope="row">2</th>
                    <td><i class="fas fa-toggle-on"></i></td>                 
                    <td>
                        <a href="adminPostView.php">Suspendisse faucibus nunc</a>
                        <hr class="postlist-divider">
                        <a href="adminPostView.php" class="btn btn-outline-dark btn-sm" title="Voir">
                            <i class="fas fa-eye"></i>
                        </a>
                        <button type="button" class="btn btn-outline-dark btn-sm" title="Modifier">
                            <i class="fas fa-pencil-alt"></i>
                        </button>
                        <button type="button" class="btn btn-outline-dark btn-sm" title="Supprimer">
                            <i class="fas fa-trash-alt"></i>
                        </button>
                    </td>
                    <td class="responsive-table-custom">Charlotte S</td>
                    <td class="responsive-table-custom">
                        Ut leo. Proin sapien ipsum, porta a, auctor quis, euismod ut, mi...
                        <hr class="postlist-divider">
                        Catégorie(s) : 
                        <a class="btn btn-outline-secondary" href="#">Développement</a>
                        <a class="btn btn-outline-secondary" href="#">Javascript</a>
                    </td>
                    <td>25/12/2019</td>
                    <td class="responsive-table-custom"><a href="#">2</a></td>
                </tr>
                <tr>
                    <th scope="row">1</th>
                    <td>
                        <i class="fas fa-toggle-on"></i>
                        <hr class="postlist-divider">

                    </td>
                    <td>
                        <a href="adminPostView.php">Maecenas vestibulum mollis diam</a>
                        <hr class="postlist-divider">
                        <a href="adminPostView.php" class="btn btn-outline-dark btn-sm" title="Voir">
                            <i class="fas fa-eye"></i>
                        </a>
                        <button type="button" class="btn btn-outline-dark btn-sm" title="Modifier">
                            <i class="fas fa-pencil-alt"></i>
                        </button>
                        <button type="button" class="btn btn-outline-dark btn-sm" title="Supprimer">
                            <i class="fas fa-trash-alt"></i>
                        </button>
                    </td>                                       
                    <td class="responsive-table-custom">Céline D</td>
                    <td class="responsive-table-custom">
                        Sed aliquam ultrices mauris. Donec interdum, metus et...
                        <hr class="postlist-divider">
                        Catégorie(s) : 
                        <a class="btn btn-outline-secondary" href="#">Développement</a>
                        <a class="btn btn-outline-secondary" href="#">PHP</a>
                    </td>
                    <td>19/12/2019</td>
                    <td class="responsive-table-custom"><a href="#">2</a></td>
                </tr>
            </tbody>
        </table>
    </div>
    
</div>


<?php $content = ob_get_clean(); ?>

<?php require('backend_template.php'); ?>
