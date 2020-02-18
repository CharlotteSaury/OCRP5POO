<!-- $title definition -->

<?php $title = 'Admin - Contact'; ?>

<!-- Content title definition -->

<?php $contentTitle = ''; ?>

<!-- $content definition -->

<?php ob_start(); ?>

<?php
while ($donnees = $contactInfos->fetch())
{
?>
<div class="row adminPostView">
    <div class="col-11 mx-auto my-3">
        <div class="d-flex flex-row justify-content-between">

            <!--<div>
                <a href="index.php?action=deletePost&amp;id=<?= htmlspecialchars($donnees['postId']); ?>" class="btn btn-outline-dark btn-sm" title="Supprimer">
                    <i class="fas fa-trash-alt"></i>
                </a>
            </div>-->
        </div>

        <div class="post-content post-content-text text-black-50 text-justify">
            <p>De : <strong><?= htmlspecialchars($donnees['name']); ?></strong> <"<?= htmlspecialchars($donnees['email']); ?>"></p>
            <p>Le : <?= htmlspecialchars($donnees['date_message']); ?></p>
            <p>Objet : <strong><?= htmlspecialchars($donnees['subject']); ?></strong></p>
        </div>

        <hr class="d-none d-lg-block ml-0">

        <div class="post-content post-content-text text-black-50 text-justify my-5">
            <p><?= htmlspecialchars($donnees['content']); ?></p>
        </div>

        <hr class="d-none d-lg-block ml-0">

    </div>
</div>




<!-- Answer section-->
<div class="row mt-4">
    <div class="col-11 mx-auto mb-5">

        <?php
        if (isset($answerInfos)) 
        {
            ?>
            <h2 class="mb-5">Réponse</h2>

            <div class="post-content post-content-text text-black-50 text-justify ml-5">
                <p>Le : <?= htmlspecialchars($answerInfos[0]['date_answer']); ?></p>
                <p>Objet : <strong><?= htmlspecialchars($answerInfos[0]['subject']); ?></strong></p>
            </div>

            <hr class="d-none d-lg-block ml-0">

            <div class="post-content post-content-text text-black-50 text-justify my-5 ml-5">
                <p><?= htmlspecialchars($answerInfos[0]['content']); ?></p>
            </div>

            <?php
        }
        else
        {
            ?>
            <h2 class="mb-5">Répondre</h2>

            <form method="POST" action="index.php?action=answer" class="answer-form form-inline d-flex flex-column">
                <input type="text" name="answerSubject" class="answer-form form-control mr-0 mr-sm-2 mb-2" placeholder="Votre nom" value="Re: <?= htmlspecialchars($donnees['subject']); ?>" required/>
                <input type="hidden" name="id" class="answer-form form-control flex-fill mr-0 mr-sm-2 mb-2" value="<?= htmlspecialchars($donnees['contactId']); ?>"/>
                <input type="hidden" name="email" class="answer-form form-control flex-fill mr-0 mr-sm-2 mb-2" value="<?= htmlspecialchars($donnees['email']); ?>"/>
                <textarea name="answerContent" class="answer-form form-control flex-fill mr-0 mr-sm-2 mb-2" rows="8" required></textarea>
                <button type="submit" class="btn btn-primary-custom">Répondre</button>
            </form>
            <?php
        }
        ?> 

    </div>
</div>  

<?php
}
$contactInfos->closeCursor();
?>






<?php $content = ob_get_clean(); ?>

<?php require('backend_template.php'); ?>
