<!-- $title definition -->

<?php $title = 'Admin - Editer le profil'; ?>

<!-- Content title definition -->

<?php $contentTitle = 'Editer le profil'; ?>

<!-- $content definition -->

<?php ob_start(); ?>

<form class="form" method="" action="">

    <div class="row">
        <div class="col-11 mx-auto">
            <div class="card profile-user-card">

                <div class="card-header">
                    <div class="form-group">
                        <label for="pseudo" hidden>Pseudo : </label>
                        <input type="text" class="form-control" name="pseudo" placeholder="Pseudo : charlotteS"/>
                    </div>
                </div>

                <div class="card-body editProfileView">

                    <div class="profile-card-avatar text-center">
                        <img class="img-thumbnail" src="../../public/images/photo.jpg" alt="User profil picture" />
                        <div class="form-group mt-2">
                            <button type="button" class="d-none d-sm-inline-block btn btn-sm btn-primary-custom shadow-sm ml-1"><i class="fas fa-upload mr-1"></i> Modifier la photo de profil</button>
                        </div>
                    </div>

                    <div class="profile-card-info">
                        <div class="card-title">
                            <div class="form-group">
                                <label for="firstname">Prénom : </label>
                                <input type="text" class="form-control" name="firstname" placeholder="Charlotte"/>
                            </div>
                            <div class="form-group">
                                <label for="lastname">Nom : </label>
                                <input type="text" class="form-control" name="lastname" placeholder="Saury"/>
                            </div>
                        </div>

                        <div class="card-text">

                            <div class="form-group">
                                <label for="birthdate">Né(e) le : </label>
                                    <input type="text" class="form-control" name="birthdate" placeholder="12/04/1990"/>
                            </div>
                            <div class="form-group">
                                <label for="">Habite à : </label>
                                <input type="text" class="form-control" name="" placeholder="Vannes"/>
                            </div>
                            <div class="form-group">
                                <label for="birthdate">A propos de moi : </label>
                                <textarea type="text" class="form-control" name="">Proin magna. Aliquam lobortis. Maecenas ullamcorper, dui et placerat feugiat, eros pede varius nisi, condimentum viverra felis nunc et lorem. Vivamus consectetuer hendrerit lacus.</textarea>                               
                            </div>
                            <div class="form-group">
                                <label for="">Email : </label>
                                <input type="text" class="form-control" name="" placeholder="saury.charlotte@wanadoo.fr"/>
                            </div>
                            <div class="form-group">
                                <label for="">Tel : </label>
                                <input type="text" class="form-control" name="" placeholder="0606060606"/>
                            </div>
                            <div class="form-group">
                                <label for="">Site internet : </label>
                                <input type="text" class="form-control" name="" placeholder="zeiferfsdfj.com"/>
                            </div>
                            <hr>
                            <div class="form-group">
                                <div class="form-check form-check-inline">
                                    <input class="form-check-input" type="checkbox" id="rolecheckboxadmin" value="admin" checked>
                                    <label class="form-check-label" for="rolecheckboxadmin">Admin</label>
                                </div>
                                <div class="form-check form-check-inline">
                                    <input class="form-check-input" type="checkbox" id="rolecheckboxuser" value="user">
                                    <label class="form-check-label" for="rolecheckboxuser">User</label>
                                </div>
                            </div>

                        </div>

                        <input type="submit" class="d-none d-sm-inline-block btn btn-sm btn-primary-custom shadow-sm ml-1" value="Enregistrer les modifications"/>
                    </div>
                </div>
            </div>
        </div>
    </div>

</form>                      
                            



<?php $content = ob_get_clean(); ?>

<?php require('backend_template.php'); ?>
