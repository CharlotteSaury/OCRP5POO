<!-- $title definition -->

<?php $this->_title = 'Admin - Profil utilisateur'; ?>

<!-- Content title definition -->

<?php $this->_contentTitle = ''; ?>

<!-- $content definition -->

<?php ob_start(); ?>

<div class="row">

    <div class="col-11 mx-auto">

        <div class="card profile-user-card mb-5 text-md-center">

            <h5 class="card-header text-primary-custom"><?= htmlspecialchars($user->getPseudo(), ENT_QUOTES); ?></h5>

            <div class="card-body profileView">
                <div class="profile-card-avatar">

                    <?php
                    if ($user->getAvatar() != null) {
                        ?>
                        <img class="img-thumbnail" src="<?= htmlspecialchars($user->getAvatar(), ENT_QUOTES); ?>" alt="User profil picture" />
                        <?php
                    } else {
                        ?>
                        <img class="img-thumbnail" src="public/images/profile.jpg" alt="User profil picture" />
                        <?php
                    }

                    if ($user->getId() == $session->get('id')) {
                        ?>
                        <div class="form-group mt-2">
                            <a data-toggle="modal" data-target="#updateProfilePictureModal<?= htmlspecialchars($user->getId(), ENT_QUOTES); ?>" class="d-inline-block btn btn-sm btn-primary-custom shadow-sm ml-1 text-white updateBtn"><i class="fas fa-upload mr-1"></i> Modifier la photo de profil</a>
                        </div>
                        <?php
                    }
                    ?>
                    
                    
                </div>

                <!-- updateProfilePicture Modal-->
                    <div class="modal fade" id="updateProfilePictureModal<?= htmlspecialchars($user->getId(), ENT_QUOTES); ?>" tabindex="-1" role="dialog" aria-labelledby="updateProfilePictureLabel" aria-hidden="true">
                        <div class="modal-dialog" role="document">
                            <div class="modal-content">
                                <div class="modal-header">
                                    <h5 class="modal-title" id="updateProfilePictureLabel">Choisissez votre nouvelle photo</h5>
                                    <button class="close" type="button" data-dismiss="modal" aria-label="Close">
                                        <span aria-hidden="true">×</span>
                                    </button>
                                </div>
                                
                                <form enctype="multipart/form-data" action="index.php?action=updateProfilePicture&amp;id=<?= htmlspecialchars($user->getId(), ENT_QUOTES); ?>&amp;ct=<?= $session->get('csrf_token'); ?>" method="POST">
                                    <div class="modal-body">
                                        <input name="picture" type="file" />
                                    </div>
                                    <div class="modal-footer">
                                        <button class="btn btn-secondary" type="button" data-dismiss="modal">Annuler</button>
                                        <button type="submit" class="btn btn-primary-custom" >Envoyer</button>
                                    </div>
                                </form>

                            </div>
                        </div>
                    </div>

                <div class="profile-card-info">
                    <h5 class="card-title"><?= htmlspecialchars($user->getFirstName(), ENT_QUOTES); ?> <?= htmlspecialchars($user->getLastName(), ENT_QUOTES); ?></h5>
                    
                    <?= $user->getBirthDate() ? '<p class="card-text">Née le ' . $user->getBirthDate() . ' </p>' : ''; ?>

                    <?= $user->getHome() ? '<p class="card-text">Habite à ' . htmlspecialchars($user->getHome(), ENT_QUOTES) . ' </p>' : ''; ?>

                    <?= $user->getUserAbout() ? '<p class="card-text"><strong>A propos de moi : </strong>' . htmlspecialchars($user->getUserAbout(), ENT_QUOTES) . ' </p>' : ''; ?>
                    <hr>
                    <p class="card-text"><strong>Email : </strong><?= htmlspecialchars($user->getEmail(), ENT_QUOTES); ?></p>

                    <?= $user->getMobile() ? '<p class="card-text"><strong>Tel : </strong>' . htmlspecialchars($user->getMobile(), ENT_QUOTES) . ' </p>' : ''; ?>

                    <?= $user->getWebsite() ? '<p class="card-text"><strong>Website : </strong>' . htmlspecialchars($user->getWebsite(), ENT_QUOTES) . ' </p>' : ''; ?>
                    
                    <hr>
                    <p class="card-text"><strong>Rôle : </strong><?= htmlspecialchars($user->getRole(), ENT_QUOTES); ?></p>
                    <p class="card-text"><strong>Date de création : </strong><?= $user->getRegisterDate(); ?></p>
                    <hr>


                    <p class="card-text"><i class="fas fa-newspaper"> <?= $user->getPostsNb(); ?></i> - <i class="fas fa-comments"> <?= $user->getCommentsNb(); ?></i></p>
                    <hr>

                    <?php
                    if ($user->getId() == $session->get('id') || $session->get('role') == 3) {
                        ?>
                        <a href="index.php?action=editUser&amp;id=<?= htmlspecialchars($user->getId()); ?>" class="btn btn-outline-dark btn-sm" title="Modifier">
                            <i class="fas fa-pencil-alt"></i>
                        </a>
                        <?php
                    }
                    ?>

                </div>

            </div>
            
        </div>

    </div>

</div>

                            
<?php $this->_content = ob_get_clean(); ?>