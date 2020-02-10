<!-- $title definition -->

<?php $title = 'Admin - Profil utilisateur'; ?>

<!-- Content title definition -->

<?php $contentTitle = ''; ?>

<!-- $content definition -->

<?php ob_start(); ?>

<?php

while ($donnees = $userInfos->fetch())
{
?>

<div class="row">

    <div class="col-11 mx-auto">

        <div class="card profile-user-card">

            <h5 class="card-header text-primary-custom"><?= htmlspecialchars($donnees['pseudo']); ?></h5>

            <div class="card-body profileView">
                <div class="profile-card-avatar">
                    <img class="img-thumbnail" src="<?= htmlspecialchars($donnees['avatar']); ?>" alt="User profil picture" />
                </div>

                <div class="profile-card-info">
                    <h5 class="card-title"><?= htmlspecialchars($donnees['first_name']); ?> <?= htmlspecialchars($donnees['last_name']); ?></h5>
                    <p class="card-text">Née le <?= $donnees['birth_date']; ?></p>
                    <p class="card-text">Habite à <?= htmlspecialchars($donnees['home']); ?></p>
                    <p class="card-text"><strong>A propos de moi : </strong> <?= htmlspecialchars($donnees['about']); ?></p>
                    <hr>
                    <p class="card-text"><strong>Email : </strong><?= htmlspecialchars($donnees['email']); ?></p>
                    <p class="card-text"><strong>Tel : </strong><?= htmlspecialchars($donnees['mobile']); ?></p>
                    <p class="card-text"><strong>Site internet : </strong><?= htmlspecialchars($donnees['website']); ?></p>
                    <hr>
                    <p class="card-text"><strong>Rôle : </strong><?= htmlspecialchars($donnees['role']); ?></p>
                    <p class="card-text"><strong>Date de création : </strong><?= $donnees['register_date']; ?></p>
                    <hr>


                    <p class="card-text"><i class="fas fa-newspaper"> <?= $userPostsNb['postsNb']; ?></i> - <i class="fas fa-comments"> <?= $userCommentsNb['commentsNb']; ?></i></p>
                    <hr>
                    <a href="index.php?action=editUser&amp;id=<?= htmlspecialchars($donnees['userId']); ?>" class="btn btn-outline-dark btn-sm" title="Modifier">
                        <i class="fas fa-pencil-alt"></i>
                    </a>

                </div>

            </div>
            
        </div>

    </div>

</div>

<?php
}
$userInfos->closeCursor();
?>


                            
<?php $content = ob_get_clean(); ?>

<?php require('backend_template.php'); ?>
