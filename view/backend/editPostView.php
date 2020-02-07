<!-- $title definition -->

<?php $title = 'Admin - Editer l\'article'; ?>

<!-- Content title definition -->

<?php $contentTitle = 'Editer l\'article'; ?>

<!-- $content definition -->

<?php ob_start(); ?>

<?php

while ($donnees = $postInfos->fetch())
{
    ?>

    <form class="form" method="POST" action="index.php?action=editPost">
        <div class="row adminPostView">

            <div class="col-11 mx-auto my-3 d-flex justify-content-between">

                <div class="col-8">
                    <div class="form-group">
                        <input type="hidden" class="form-control" name="id" value="<?= htmlspecialchars($donnees['postId']); ?>"/>
                    </div>

                    <div class="form-group">
                        <label for="post-title">Titre : </label>
                        <input type="text" class="form-control" name="title" placeholder="<?= htmlspecialchars($donnees['title']); ?>"/>
                    </div>

                    <hr class="d-none d-lg-block ml-0">

                    <div class="post-content post-content-text text-black-50 text-justify">
                        <p class="mb-0">Auteur : <?= htmlspecialchars($donnees['first_name']); ?> <?= htmlspecialchars($donnees['last_name']); ?></p>
                        <p class="mb-0">le <?= htmlspecialchars($donnees['date_creation']); ?></p>
                        <p class="mb-0">Dernière modification le <?= htmlspecialchars($donnees['date_update']); ?></p>
                    </div>

                    <hr class="d-none d-lg-block ml-0">

                    <div class="form-group">
                        <label for="post-chapo">Chapô : </label>
                        <textarea class="form-control" name="chapo"><?= htmlspecialchars($donnees['chapo']); ?></textarea>   
                    </div>
                    <input type="submit" name="updatePostInfos" class="d-none d-sm-inline-block btn btn-sm btn-primary-custom shadow-sm ml-1" value="Enregistrer les modifications"/> 
                </div>
                <div class="col-4">
                    <?php
                    if ($donnees['main_image'] != null)
                    {
                        ?>

                        <div class="my-4 text-center">  
                            <img class="admin-post-img mb-4" src="<?= htmlspecialchars($donnees['main_image']); ?>" />
                            <div>

                                <a data-toggle="modal" data-target="#updateMainPictureModal<?= htmlspecialchars($donnees['postId']); ?>" class="d-none d-sm-inline-block btn btn-sm btn-primary-custom shadow-sm ml-1"><i class="fas fa-upload mr-1"></i> Modifier l'image principale</a> 
                                <a data-toggle="modal" data-target="#deleteMainPictureModal<?= htmlspecialchars($donnees['postId']); ?>" class="btn btn-outline-dark btn-sm mr-2" title="Supprimer">
                                    <i class="fas fa-trash-alt"></i>
                                </a>
                            </div>  
                        </div>

                        <?php
                    }
                    else
                    {
                        ?>
                        <div class="my-4 text-center">
                            <p class=""><strong>Image principale : </strong></p>
                            <p>Aucune image sélectionnée</p>
                            <a data-toggle="modal" data-target="#updateMainPictureModal<?= htmlspecialchars($donnees['postId']); ?>" class="d-none d-sm-inline-block btn btn-sm btn-primary-custom shadow-sm ml-1"><i class="fas fa-upload mr-1"></i> Ajouter une image</a>
                        </div>
                        <?php
                    }
                    ?>

                    <!-- updateMainPicture Modal-->
                    <div class="modal fade" id="updateMainPictureModal<?= htmlspecialchars($donnees['postId']); ?>" tabindex="-1" role="dialog" aria-labelledby="updateMainPictureLabel" aria-hidden="true">
                        <div class="modal-dialog" role="document">
                            <div class="modal-content">
                                <div class="modal-header">
                                    <h5 class="modal-title" id="updateMainPictureLabel">Entrez l'url de votre nouvelle photo</h5>
                                    <button class="close" type="button" data-dismiss="modal" aria-label="Close">
                                        <span aria-hidden="true">×</span>
                                    </button>
                                </div>
                                <div class="modal-body">
                                    <p><em>Veuillez enregistrer les autres modifications avant de modifier ce contenu au risque que les informations soient perdues.</em></p>
                                    <input type="hidden" name="postId" value="<?= htmlspecialchars($donnees['postId']); ?>"/>
                                    <label for="main_image" hidden>Url :</label>
                                    <input type="text" class="form-control" name="main_image" placeholder="url"/>
                                </div>
                                <div class="modal-footer">
                                    <button class="btn btn-secondary" type="button" data-dismiss="modal">Annuler</button>
                                    <button type="submit" name="updateMainPicture" class="btn btn-primary-custom" >Valider</button>
                                </div>
                            </div>
                        </div>
                    </div>

                    <!-- deletePicture Modal-->
                    <div class="modal fade" id="deleteMainPictureModal<?= htmlspecialchars($donnees['postId']); ?>" tabindex="-1" role="dialog" aria-labelledby="deleteMainPictureLabel" aria-hidden="true">
                        <div class="modal-dialog" role="document">
                            <div class="modal-content">
                                <div class="modal-header">
                                    <h5 class="modal-title" id="deleteMainPictureLabel">Voulez-vous vraiment supprimer cette image ?</h5>
                                    <button class="close" type="button" data-dismiss="modal" aria-label="Close">
                                        <span aria-hidden="true">×</span>
                                    </button>
                                    <input type="hidden" name="postId" value="<?= htmlspecialchars($donnees['postId']); ?>"/>
                                </div>
                                <div class="modal-body">
                                    <p>Cliquez sur "Valider" pour supprimer définitivement cette image.</p>
                                    <p><em>Veuillez enregistrer les autres modifications avant de modifier ce contenu au risque que les informations soient perdues.</em></p>
                                </div>

                                <div class="modal-footer">
                                    <button class="btn btn-secondary" type="button" data-dismiss="modal">Annuler</button>
                                    <button type="submit" name="deleteMainPicture" class="btn btn-primary-custom" >Valider</button>
                                </div>
                            </div>
                        </div>
                    </div>
                    <?php
                }
                $postInfos->closeCursor();
                ?>
                </div>
            </div>

            <div class="col-11 mx-auto my-3">

                <hr class="d-none d-lg-block ml-0">

                <h4>Contenu</h4>

                <?php


                while ($donnees = $postContents->fetch())
                {
                    if ($donnees['content_type'] == 1) 
                    {
                        ?>
                        <div class="my-4 text-center d-flex flex-column align-items-center">  
                            <img class="admin-post-img mb-4" src="<?= htmlspecialchars($donnees['content']); ?>" /> 
                            <div>

                                <a data-toggle="modal" data-target="#updatePictureModal<?= htmlspecialchars($donnees['contentId']); ?>" class="d-none d-sm-inline-block btn btn-sm btn-primary-custom shadow-sm ml-1"><i class="fas fa-upload mr-1"></i> Modifier l'image</a> 
                                <a data-toggle="modal" data-target="#deleteContentModal<?= htmlspecialchars($donnees['contentId']); ?>" class="btn btn-outline-dark btn-sm mr-2" title="Supprimer">
                                    <i class="fas fa-trash-alt"></i>
                                </a>
                            </div> 
                        </div>

                        <!-- updatePicture Modal-->
                        <div class="modal fade" id="updatePictureModal<?= htmlspecialchars($donnees['contentId']); ?>" tabindex="-1" role="dialog" aria-labelledby="updatePictureLabel" aria-hidden="true">
                            <div class="modal-dialog" role="document">
                                <div class="modal-content">
                                    <div class="modal-header">
                                        <h5 class="modal-title" id="updatePictureLabel">Entrez l'url de votre nouvelle photo</h5>
                                        <button class="close" type="button" data-dismiss="modal" aria-label="Close">
                                            <span aria-hidden="true">×</span>
                                        </button>
                                    </div>
                                    <div class="modal-body">
                                        <p><em>Veuillez enregistrer les autres modifications avant de modifier ce contenu au risque que les informations soient perdues.</em></p>
                                        <input type="hidden" name="postId" value="<?= htmlspecialchars($donnees['postId']); ?>"/>
                                        <input type="hidden" name="contentId" value="<?= htmlspecialchars($donnees['contentId']); ?>"/>
                                        <label for="avatarUrl" hidden>Url :</label>
                                        <input type="text" class="form-control" name="content<?= htmlspecialchars($donnees['contentId']); ?>" placeholder="url"/>
                                    </div>
                                    <div class="modal-footer">
                                        <button class="btn btn-secondary" type="button" data-dismiss="modal">Annuler</button>
                                        <button type="submit" name="updatePicture<?= htmlspecialchars($donnees['contentId']); ?>" class="btn btn-primary-custom" >Valider</button>
                                    </div>
                                </div>
                            </div>
                        </div>
                        <!-- deleteContent Modal-->
                        <div class="modal fade" id="deleteContentModal<?= htmlspecialchars($donnees['contentId']); ?>" tabindex="-1" role="dialog" aria-labelledby="deleteContentLabel" aria-hidden="true">
                            <div class="modal-dialog" role="document">
                                <div class="modal-content">
                                    <div class="modal-header">
                                        <h5 class="modal-title" id="deleteContentLabel">Voulez-vous vraiment supprimer ce contenu ?</h5>
                                        <button class="close" type="button" data-dismiss="modal" aria-label="Close">
                                            <span aria-hidden="true">×</span>
                                        </button>
                                        <input type="hidden" name="postId" value="<?= htmlspecialchars($donnees['postId']); ?>"/>
                                        <input type="hidden" name="contentId" value="<?= htmlspecialchars($donnees['contentId']); ?>"/>
                                    </div>
                                    <div class="modal-body">Cliquez sur "Valider" pour supprimer définitivement ce contenu</div>
                                    <div class="modal-footer">
                                        <button class="btn btn-secondary" type="button" data-dismiss="modal">Annuler</button>
                                        <a href="index.php?action=deleteContent&amp;id=<?= htmlspecialchars($donnees['postId']); ?>&amp;content=<?= htmlspecialchars($donnees['contentId']); ?>" class="btn btn-primary-custom" >Valider</a>
                                    </div>
                                </div>
                            </div>
                        </div>

                        <?php
                    }
                    else
                    {
                        ?>

                        <div class="form-group d-flex">                        

                            <textarea class="form-control" name="<?= htmlspecialchars($donnees['contentId']); ?>"><?= htmlspecialchars($donnees['content']); ?></textarea>
                            
                            <input type="submit" name="editContent" class="btn btn-outline-success btn-sm mx-2" title="Enregistrer les modifications" value="Enregistrer"/>
                            
                            <a data-toggle="modal" data-target="#deleteContentModal<?= htmlspecialchars($donnees['contentId']); ?>" class="btn btn-outline-dark btn-sm mr-2" title="Supprimer">
                                <i class="fas fa-trash-alt"></i>
                            </a>

                        </div>

                        <!-- deleteContent Modal-->
                        <div class="modal fade" id="deleteContentModal<?= htmlspecialchars($donnees['contentId']); ?>" tabindex="-1" role="dialog" aria-labelledby="deleteContentLabel" aria-hidden="true">
                            <div class="modal-dialog" role="document">
                                <div class="modal-content">
                                    <div class="modal-header">
                                        <h5 class="modal-title" id="deleteContentLabel">Voulez-vous vraiment supprimer ce contenu ?</h5>
                                        <button class="close" type="button" data-dismiss="modal" aria-label="Close">
                                            <span aria-hidden="true">×</span>
                                        </button>
                                    </div>
                                    <div class="modal-body">Cliquez sur "Valider" pour supprimer définitivement ce contenu</div>
                                    <div class="modal-footer">
                                        <button class="btn btn-secondary" type="button" data-dismiss="modal">Annuler</button>
                                        <a href="index.php?action=deleteContent&amp;id=<?= htmlspecialchars($donnees['postId']); ?>&amp;content=<?= htmlspecialchars($donnees['contentId']); ?>" class="btn btn-primary-custom" >Valider</a>
                                    </div>
                                </div>
                            </div>
                        </div>

                        <?php
                    }
                }
                $postContents->closeCursor();

                ?>       

                <div class="my-2">
                    <button type="submit" name="addParagraph" class="d-none d-sm-inline-block btn btn-sm btn-light shadow-sm"><i class="fas fa-plus fa-sm mr-1"></i> Ajouter un paragraphe</button>
                    <a data-toggle="modal" data-target="#addPictureModal<?= $postId; ?>" class="d-none d-sm-inline-block btn btn-sm btn-light shadow-sm"><i class="fas fa-plus fa-sm mr-1"></i> Ajouter une image</a>

                    <!-- addPicture Modal-->
                    <div class="modal fade" id="addPictureModal<?= $postId; ?>" tabindex="-1" role="dialog" aria-labelledby="addPictureLabel" aria-hidden="true">
                        <div class="modal-dialog" role="document">
                            <div class="modal-content">
                                <div class="modal-header">
                                    <h5 class="modal-title" id="addPictureLabel">Entrez l'url de votre nouvelle photo</h5>
                                    <button class="close" type="button" data-dismiss="modal" aria-label="Close">
                                        <span aria-hidden="true">×</span>
                                    </button>
                                </div>
                                <div class="modal-body">
                                    <p><em>Veuillez enregistrer les autres modifications avant de modifier ce contenu au risque que les informations soient perdues.</em></p>
                                    <input type="hidden" name="postId" value="<?= $postId; ?>"/>
                                    <label for="image_url" hidden>Url :</label>
                                    <input type="text" class="form-control" name="image_url" placeholder="url"/>
                                </div>
                                <div class="modal-footer">
                                    <button class="btn btn-secondary" type="button" data-dismiss="modal">Annuler</button>
                                    <button type="submit" name="addPicture" class="btn btn-primary-custom" >Valider</button>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>

                <hr class="d-none d-lg-block ml-0">

                <h4 class="mb-4">Catégories</h4>
                <div class="">

                    <?php
                    while ($donnees = $postCategories->fetch())
                    {
                        ?>
                        <a href="index.php?action=deleteCategory&amp;id=<?= htmlspecialchars($donnees['postId']); ?>&amp;cat=<?= htmlspecialchars($donnees['categoryId']); ?>" class="btn btn-outline-secondary mr-2"><?= htmlspecialchars($donnees['categoryName']); ?> <i class="ml-1 fas fa-times"></i></a>
                        <?php 
                    }

                    $postCategories->closeCursor();
                    ?>

                </div>
                <div class="form-group form-inline mt-3">
                    <label for="new-category">Sélectionner / Ajouter une catégorie</label>
                    <input type="text" class="form-control mx-2" id="new-category" name="categoryName"/> 
                    <button type="submit" name="addCategory" class="d-none d-sm-inline-block btn btn-sm btn-light shadow-sm"><i class="fas fa-plus"></i></button>
                </div>

                <hr class="d-none d-lg-block ml-0">

            </div>         
        </div>  
    </form>  
    






    <?php $content = ob_get_clean(); ?>

    <?php require('backend_template.php'); ?>
