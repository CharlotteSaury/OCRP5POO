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
                <tr>
                    <th scope="row">5</th>
                    <td><a href="profileView.php">Celiiine</a></td>
                    <td>celiiine@email.fr</td>
                    <td>user</td>
                    <td>29/12/19</td>
                    <td>-</td>
                    <td>12</td>
                    <td>
                        <a href="profileView.php" class="btn btn-outline-dark btn-sm" title="Voir">
                            <i class="fas fa-eye"></i>
                        </a>
                        <button type="button" class="btn btn-outline-dark btn-sm" title="Modifier">
                            <i class="fas fa-pencil-alt"></i>
                        </button>
                        <button type="button" class="btn btn-outline-dark btn-sm" title="Supprimer">
                            <i class="fas fa-trash-alt"></i>
                        </button>
                    </td>
                </tr>
                <tr>
                    <th scope="row">4</th>
                    <td><a href="profileView.php">georgesClooney</a></td>
                    <td>clooney@hollywood.com</td>
                    <td>user</td>
                    <td>29/12/19</td>
                    <td>-</td>
                    <td>12</td>
                    <td>
                        <a href="profileView.php" class="btn btn-outline-dark btn-sm" title="Voir">
                            <i class="fas fa-eye"></i>
                        </a>
                        <button type="button" class="btn btn-outline-dark btn-sm" title="Modifier">
                            <i class="fas fa-pencil-alt"></i>
                        </button>
                        <button type="button" class="btn btn-outline-dark btn-sm" title="Supprimer">
                            <i class="fas fa-trash-alt"></i>
                        </button>
                    </td>
                </tr>
                <tr>
                    <th scope="row">3</th>
                    <td><a href="profileView.php">JoeDé</a></td>
                    <td>joe.dalton@email.com</td>
                    <td>user</td>
                    <td>29/12/19</td>
                    <td>-</td>
                    <td>12</td>
                    <td>
                        <a href="profileView.php" class="btn btn-outline-dark btn-sm" title="Voir">
                            <i class="fas fa-eye"></i>
                        </a>
                        <button type="button" class="btn btn-outline-dark btn-sm" title="Modifier">
                            <i class="fas fa-pencil-alt"></i>
                        </button>
                        <button type="button" class="btn btn-outline-dark btn-sm" title="Supprimer">
                            <i class="fas fa-trash-alt"></i>
                        </button>
                    </td>
                </tr>
                <tr>
                    <th scope="row">2</th>
                    <td><a href="profileView.php">Charlotte S</a></td>
                    <td>saury.charlotte@wanadoo.fr</td>
                    <td>admin</td>
                    <td>29/12/19</td> 
                    <td>5</td>                                           
                    <td>12</td>
                    <td>                                        
                        <a href="profileView.php" class="btn btn-outline-dark btn-sm" title="Voir">
                            <i class="fas fa-eye"></i>
                        </a>
                        <button type="button" class="btn btn-outline-dark btn-sm" title="Modifier">
                            <i class="fas fa-pencil-alt"></i>
                        </button>
                        <button type="button" class="btn btn-outline-dark btn-sm" title="Supprimer">
                            <i class="fas fa-trash-alt"></i>
                        </button>
                    </td>
                </tr>
                <tr>
                    <th scope="row">1</th>
                    <td><a href="profileView.php">jacadi</a></td>
                    <td>jacadi@email.fr</td>
                    <td>user</td>
                    <td>29/12/19</td>
                    <td>-</td>
                    <td>12</td>
                    <td>
                        <a href="profileView.php" class="btn btn-outline-dark btn-sm" title="Voir">
                            <i class="fas fa-eye"></i>
                        </a>
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



<?php $content = ob_get_clean(); ?>

<?php require('backend_template.php'); ?>
