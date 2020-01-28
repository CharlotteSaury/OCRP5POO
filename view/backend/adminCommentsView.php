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
                <tr class="table-success-custom">
                    <th scope="row">44</th>
                    <td><a href="adminPostView.php">Fusce neque</a></td>
                    <td>Charlotte S</td>
                    <td>Sed in</td>
                    <td>29/12/2019</td>
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
                <tr class="table-success-custom">
                    <th scope="row">43</th>
                    <td><a href="adminPostView.php">Fusce</td>
                    </a>                                                    <td>Charlotte S</td>
                    <td>Suspendisse faucibus nunc</td>
                    <td>25/12/2019</td>
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
                <tr class="table-success-custom">
                    <th scope="row">42</th>
                    <td><a href="adminPostView.php">Fusce neque</a></td>
                    <td>Céline D</td>
                    <td>Maecenas vestibulum mollis diam</td>
                    <td>19/12/2019</td>
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
                <tr>
                    <th scope="row">41</th>
                    <td><a href="adminPostView.php">Qsfd eque</</td>
                        <td>Céline D</td>
                        <td>Maecenas vestibulum mollis diam</td>
                        <td>19/12/2019</td>
                        <td>                                <a href="adminPostView.php" class="btn btn-outline-dark btn-sm" title="Voir l'article">
                            <i class="fas fa-eye"></i>
                        </a>        
                        <button type="button" class="btn btn-outline-dark btn-sm" title="Supprimer">
                            <i class="fas fa-times"></i>
                        </button>
                    </td>
                </tr>
                <tr>
                    <th scope="row">40</th>
                    <td><a href="adminPostView.php">Fusce neque</a></td>
                    <td>Céline D</td>
                    <td>Maecenas vestibulum mollis diam</td>
                    <td>19/12/2019</td>
                    <td>
                        <a href="adminPostView.php" class="btn btn-outline-dark btn-sm" title="Voir l'article">
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


<?php $content = ob_get_clean(); ?>

<?php require('backend_template.php'); ?>
