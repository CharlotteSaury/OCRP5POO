<!-- $title definition -->

<?php $title = 'Admin - Editer le profil'; ?>

<!-- Content title definition -->

<?php $contentTitle = 'Editer le profil'; ?>

<!-- $content definition -->

<?php ob_start(); ?>

<?php

while ($donnees = $userInfos->fetch())
{

?>

<form class="form" method="POST" action="index.php?action=editUserInfos">

    <div class="row">
        <div class="col-11 mx-auto">
            <div class="card profile-user-card">

                <div class="card-header">
                    <div class="form-group">
                        <input type="hidden" name="id" value="<?= $donnees['userId']; ?>"/>
                    </div>
                    <div class="form-group">
                        <label for="pseudo" hidden>Pseudo : </label>
                        <input type="text" class="form-control" name="pseudo" placeholder="Pseudo : <?= htmlspecialchars($donnees['pseudo']); ?>"/>
                    </div>
                </div>

                <div class="card-body editProfileView">

                    <div class="profile-card-avatar text-center">
                        <img class="img-thumbnail" src="<?= htmlspecialchars($donnees['avatar']); ?>" alt="User profil picture" />
                        <div class="form-group mt-2">
                            <a data-toggle="modal" data-target="#updateProfilePictureModal<?= htmlspecialchars($donnees['userId']); ?>" class="d-none d-sm-inline-block btn btn-sm btn-primary-custom shadow-sm ml-1"><i class="fas fa-upload mr-1"></i> Modifier la photo de profil</a>
                        </div>
                    </div>

                    <!-- updateProfilePicture Modal-->
                    <div class="modal fade" id="updateProfilePictureModal<?= htmlspecialchars($donnees['userId']); ?>" tabindex="-1" role="dialog" aria-labelledby="updateProfilePictureLabel" aria-hidden="true">
                        <div class="modal-dialog" role="document">
                            <div class="modal-content">
                                <div class="modal-header">
                                    <h5 class="modal-title" id="updateProfilePictureLabel">Entrez l'url de votre nouvelle photo</h5>
                                    <button class="close" type="button" data-dismiss="modal" aria-label="Close">
                                        <span aria-hidden="true">×</span>
                                    </button>
                                </div>
                                <form method="POST" action="index.php?action=updateProfilePicture&amp;id=<?= htmlspecialchars($donnees['userId']); ?>">
                                    <div class="modal-body">
                                        <label for="avatarUrl" hidden>Url :</label>
                                        <input type="text" class="form-control" name="avatar" placeholder="url"/>
                                    </div>
                                    <div class="modal-footer">
                                        <button class="btn btn-secondary" type="button" data-dismiss="modal">Annuler</button>
                                        <button type="submit" class="btn btn-primary-custom" >Valider</button>
                                    </div>
                                </form>
                            </div>
                        </div>
                    </div>

                    <div class="profile-card-info">
                        <div class="card-title">
                            <div class="form-group">
                                <label for="firstname">Prénom : </label>
                                <input type="text" class="form-control" name="first_name" placeholder="<?= htmlspecialchars($donnees['first_name']); ?>"/>
                            </div>
                            <div class="form-group">
                                <label for="lastname">Nom : </label>
                                <input type="text" class="form-control" name="last_name" placeholder="<?= htmlspecialchars($donnees['last_name']); ?>"/>
                            </div>
                        </div>

                        <div class="card-text">

                            <div class="form-group">
                                <label for="birthdate">Né(e) le : </label>
                                    <input type="text" class="form-control" name="birth_date" placeholder="<?= $donnees['birth_date']; ?>"/>
                            </div>
                            <div class="form-group">
                                <label for="home">Habite à : </label>
                                <input type="text" class="form-control" name="home" placeholder="<?= htmlspecialchars($donnees['home']); ?>"/>
                            </div>
                            <div class="form-group">
                                <label for="user_about">A propos de moi : </label>
                                <textarea type="text" class="form-control" name="user_about"><?= htmlspecialchars($donnees['about']); ?></textarea>                               
                            </div>
                            <div class="form-group">
                                <label for="email">Email : </label>
                                <input type="text" class="form-control" name="email" placeholder="<?= htmlspecialchars($donnees['email']); ?>"/>
                            </div>
                            <div class="form-group">
                                <label for="mobile">Tel : </label>
                                <input type="text" class="form-control" name="mobile" placeholder="<?= htmlspecialchars($donnees['mobile']); ?>"/>
                            </div>
                            <div class="form-group">
                                <label for="website">Site internet : </label>
                                <input type="text" class="form-control" name="website" placeholder="<?= htmlspecialchars($donnees['website']); ?>"/>
                            </div>
                            
                            <hr>

                            <?php
                            if ($donnees['role'] == 'admin')
                            {
                            ?>

                            <div class="form-group">
                                <div class="form-check form-check-inline">
                                    <input class="form-check-input" type="radio" id="rolecheckboxadmin" value="1" name="user_role_id" checked>
                                    <label class="form-check-label" for="rolecheckboxadmin">Admin</label>
                                </div>
                                <div class="form-check form-check-inline">
                                    <input class="form-check-input" type="radio" id="rolecheckboxuser" name="user_role_id" value="2">
                                    <label class="form-check-label" for="rolecheckboxuser">User</label>
                                </div>
                            </div>

                            <?php
                            }
                            else
                            {
                            ?>

                            <div class="form-group">
                                <div class="form-check form-check-inline">
                                    <input class="form-check-input" type="radio" id="rolecheckboxadmin" name="user_role_id" value="1">
                                    <label class="form-check-label" for="rolecheckboxadmin">Admin</label>
                                </div>
                                <div class="form-check form-check-inline">
                                    <input class="form-check-input" type="radio" id="rolecheckboxuser" name="user_role_id" value="2" checked>
                                    <label class="form-check-label" for="rolecheckboxuser">User</label>
                                </div>
                            </div>

                            <?php
                            }
                            ?>
                            

                        </div>

                        <input type="submit" class="d-none d-sm-inline-block btn btn-sm btn-primary-custom shadow-sm ml-1" value="Enregistrer les modifications"/>
                    </div>
                </div>
            </div>
        </div>
    </div>

</form>                      
                            
<?php
}
$userInfos->closeCursor();

$content = ob_get_clean(); ?>

<?php require('backend_template.php'); ?>
