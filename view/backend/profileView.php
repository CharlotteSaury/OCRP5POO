<!-- $title definition -->

<?php $title = 'Admin - Profil utilisateur'; ?>

<!-- Content title definition -->

<?php $contentTitle = ''; ?>

<!-- $content definition -->

<?php ob_start(); ?>

<div class="row">

    <div class="col-11 mx-auto">

        <div class="card profile-user-card">

            <h5 class="card-header text-primary-custom">Pseudo</h5>

            <div class="card-body profileView">
                <div class="profile-card-avatar">
                    <img class="img-thumbnail" src="../../public/images/photo.jpg" alt="User profil picture" />
                </div>

                <div class="profile-card-info">
                    <h5 class="card-title">Charlotte SAURY</h5>
                    <p class="card-text">Née le 12/04/1990 - 29 ans</p>
                    <p class="card-text">Habite à Vannes</p>
                    <p class="card-text"><strong>A propos de moi : </strong> Proin magna. Aliquam lobortis. Maecenas ullamcorper, dui et placerat feugiat, eros pede varius nisi, condimentum viverra felis nunc et lorem. Vivamus consectetuer hendrerit lacus.</p>
                    <hr>
                    <p class="card-text"><strong>Email : </strong>saury.charlotte@wanadoo.fr</p>
                    <p class="card-text"><strong>Tel : </strong>0606060606</p>
                    <p class="card-text"><strong>Site internet : </strong>zeiferfsdfj.com</p>
                    <hr>
                    <p class="card-text"><strong>Rôle : </strong>admin</p>
                    <p class="card-text"><strong>Date de création : </strong>12/12/2019</p>
                    <hr>
                    <p class="card-text"><i class="fas fa-newspaper"> 3</i> - <i class="fas fa-comments"> 5</i></p>
                    <hr>
                    <a href="editProfileView.php" class="btn btn-outline-dark btn-sm" title="Modifier">
                        <i class="fas fa-pencil-alt"></i>
                    </a>
                    <button type="button" class="btn btn-outline-dark btn-sm" title="Supprimer">
                        <i class="fas fa-trash-alt"></i>
                    </button>

                </div>

            </div>
            
        </div>

    </div>

</div>
                            


<?php $content = ob_get_clean(); ?>

<?php require('backend_template.php'); ?>
