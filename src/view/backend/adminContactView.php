<!-- $title definition -->

<?php $this->_title = 'Admin - Contact'; ?>

<!-- Content title definition -->

<?php $this->_contentTitle = ''; ?>

<!-- $content definition -->

<?php ob_start(); ?>

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
            <p>De : <strong><?= htmlspecialchars($contact->name()); ?></strong> <"<?= htmlspecialchars($contact->email()); ?>"></p>
            <p>Le : <?= htmlspecialchars($contact->dateMessage()); ?></p>
            <p>Objet : <strong><?= htmlspecialchars($contact->subject()); ?></strong></p>
        </div>

        <hr class="d-none d-lg-block ml-0">

        <div class="post-content post-content-text text-black-50 text-justify my-5">
            <p><?= htmlspecialchars($contact->content()); ?></p>
        </div>

        <hr class="d-none d-lg-block ml-0">

    </div>
</div>




<!-- Answer section-->
<div class="row mt-4">
    <div class="col-11 mx-auto mb-5">

        <?php
        if (isset($answer)) 
        {
            ?>
            <h2 class="mb-5">Réponse</h2>

            <div class="post-content post-content-text text-black-50 text-justify ml-5">
                <p>Le : <?= htmlspecialchars($answer->dateAnswer()); ?></p>
                <p>Objet : <strong><?= htmlspecialchars($answer->subject()); ?></strong></p>
            </div>

            <hr class="d-none d-lg-block ml-0">

            <div class="post-content post-content-text text-black-50 text-justify my-5 ml-5">
                <p><?= htmlspecialchars($answer->content()); ?></p>
            </div>

            <?php
        }
        else
        {
            ?>
            <h2 class="mb-5">Répondre</h2>

            <form method="POST" action="index.php?action=answer" class="answer-form form-inline d-flex flex-column align-items-start">
                <input type="text" name="answerSubject" class="answer-form form-control mr-0 mr-sm-2 mb-2" placeholder="Objet" value="Re: <?= htmlspecialchars($contact->subject()); ?>" required/>

                <?= isset($errors['answerSubject']) ? '<p class="form-error">' . $errors['answerSubject'] . '</p>' : ''; ?>

                <input type="hidden" name="contactId" class="answer-form form-control flex-fill mr-0 mr-sm-2 mb-2" value="<?= htmlspecialchars($contact->id()); ?>"/>
                <input type="hidden" name="email" class="answer-form form-control flex-fill mr-0 mr-sm-2 mb-2" value="<?= htmlspecialchars($contact->email()); ?>"/>
                <textarea name="answerContent" class="answer-form form-control flex-fill mr-0 mr-sm-2 mb-2" rows="8" required></textarea>

                <?= isset($errors['answerContent']) ? '<p class="form-error">' . $errors['answerContent'] . '</p>' : ''; ?>

                <button type="submit" class="btn btn-primary-custom">Répondre</button>
            </form>
            <?php
        }
        ?> 

    </div>
</div>  


<?php $this->_content = ob_get_clean(); ?>

