<!-- $title definition -->

<?php $this->_title = 'Admin - Editer l\'article'; ?>

<!-- Content title definition -->

<?php $this->_contentTitle = 'Editer l\'article'; ?>

<!-- $content definition -->

<?php ob_start(); ?>

    <form class="form" enctype="multipart/form-data"  method="POST" action="index.php?action=editPost">
        <div class="row adminPostView">

            <div class="col-11 mx-auto my-3 d-flex justify-content-between">

                <div class="col-8">
                    <div class="form-group">
                        <input type="hidden" class="form-control" name="postId" value="<?= htmlspecialchars($post->id()); ?>"/>
                    </div>

                    <div class="form-group">
                        <label for="post-title">Titre : </label>
                        <input type="text" class="form-control" name="title" value="<?= htmlspecialchars($post->title()); ?>" required/>
                    </div>

                    <hr class="d-none d-lg-block ml-0">

                    <div class="post-content post-content-text text-black-50 text-justify">
                        <p class="mb-0">Auteur : <?= htmlspecialchars($post->pseudo()); ?></p>
                        <p class="mb-0">le <?= htmlspecialchars($post->dateCreation()); ?></p>
                        <p class="mb-0">Dernière modification le <?= htmlspecialchars($post->dateUpdate()); ?></p>
                    </div>

                    <hr class="d-none d-lg-block ml-0">

                    <div class="form-group">
                        <label for="post-chapo">Chapô : </label>
                        <textarea class="form-control" name="chapo" required><?= htmlspecialchars($post->chapo()); ?></textarea>   
                    </div>
                    <input type="submit" name="updatePostInfos" class="d-none d-sm-inline-block btn btn-sm btn-primary-custom shadow-sm ml-1" value="Enregistrer les modifications"/> 
                </div>
                <div class="col-4">
                    <?php
                    if ($post->mainImage() != null)
                    {
                        ?>

                        <div class="my-4 text-center">
                            <p class="">
                                <strong>Image principale </strong>
                                <a data-toggle="modal" data-target="#deleteMainPictureModal" class="btn btn-outline-dark btn-sm mr-2" title="Supprimer"><i class="fas fa-trash-alt"></i>
                                </a>
                            </p>
                            <img class="admin-post-img mb-4" src="<?= htmlspecialchars($post->mainImage()); ?>"/>
                        </div>

                        <?php
                    }
                    else
                    {
                        ?>
                        <div class="my-4 text-center">
                            <p class=""><strong>Image principale</strong></p>
                            <p>Aucune image sélectionnée</p>
                            <input name="MainPicture" type="file" />
                        </div>
                        <?php
                    }
                    ?>

                    <!-- deletePicture Modal-->
                    <div class="modal fade" id="deleteMainPictureModal" tabindex="-1" role="dialog" aria-labelledby="deleteMainPictureLabel" aria-hidden="true">
                        <div class="modal-dialog" role="document">
                            <div class="modal-content">
                                <div class="modal-header">
                                    <h5 class="modal-title" id="deleteMainPictureLabel">Voulez-vous vraiment supprimer cette image ?</h5>
                                    <button class="close" type="button" data-dismiss="modal" aria-label="Close">
                                        <span aria-hidden="true">×</span>
                                    </button>
                                </div>
                                <div class="modal-body">
                                    <p>Cliquez sur "Valider" pour supprimer définitivement cette image.</p>
                                    <p><em>Veuillez enregistrer les autres modifications avant de modifier ce contenu au risque que les informations soient perdues.</em></p>
                                </div>

                                <div class="modal-footer">
                                    <button class="btn btn-secondary" type="button" data-dismiss="modal">Annuler</button>
                                    <button type="submit" name="deleteMainPicture" class="btn btn-primary-custom" value="Valider">Valider</button>
                                </div>
                            </div>
                        </div>
                    </div>

                </div>
            </div>

            <div class="col-11 mx-auto my-3">

                <hr class="d-none d-lg-block ml-0">

                <h4>Contenu</h4>

                <?php


                foreach ($contents as $content)
                {
                    if ($content->contentTypeId() == 1) 
                    {
                        ?>
                        <div class="my-4 text-center d-flex flex-column align-items-center">  
                            <img class="admin-post-img mb-4" src="<?= htmlspecialchars($content->content()); ?>" /> 
                            <div>

                                <a data-toggle="modal" data-target="#updatePictureModal<?= htmlspecialchars($content->id()); ?>" class="btn btn-outline-dark btn-sm mr-2" title="Modifier l'image"><i class="fas fa-pencil-alt mr-1"></i></a> 
                                <a data-toggle="modal" data-target="#deleteContentModal<?= htmlspecialchars($content->id()); ?>" class="btn btn-outline-dark btn-sm mr-2" title="Supprimer">
                                    <i class="fas fa-trash-alt"></i>
                                </a>
                            </div> 
                        </div>

                        <!-- updatePicture Modal-->

                        <div class="modal fade" id="updatePictureModal<?= htmlspecialchars($content->id()); ?>" tabindex="-1" role="dialog" aria-labelledby="updatePictureLabel" aria-hidden="true">
                            <div class="modal-dialog" role="document">
                                <div class="modal-content">
                                    <div class="modal-header">
                                        <h5 class="modal-title" id="updatePictureLabel">Choisissez votre nouvelle photo</h5>
                                        <button class="close" type="button" data-dismiss="modal" aria-label="Close">
                                            <span aria-hidden="true">×</span>
                                        </button>
                                    </div>
                                    <div class="modal-body">
                                        <p><em>Veuillez enregistrer les autres modifications avant de modifier ce contenu au risque que les informations soient perdues.</em></p>
                                        <input name="picture<?= htmlspecialchars($content->id()); ?>" type="file" />
                                    </div>
                                    <div class="modal-footer">
                                        <button class="btn btn-secondary" type="button" data-dismiss="modal">Annuler</button>
                                        <button type="submit" class="btn btn-primary-custom" name="updatePicture" value="updatePicture">Envoyer</button>
                                    </div>

                                </div>
                            </div>
                        </div>
                        
                        <!-- deleteContent Modal-->
                        <div class="modal fade" id="deleteContentModal<?= htmlspecialchars($content->id()); ?>" tabindex="-1" role="dialog" aria-labelledby="deleteContentLabel" aria-hidden="true">
                            <div class="modal-dialog" role="document">
                                <div class="modal-content">
                                    <div class="modal-header">
                                        <h5 class="modal-title" id="deleteContentLabel">Voulez-vous vraiment supprimer ce contenu ?</h5>
                                        <button class="close" type="button" data-dismiss="modal" aria-label="Close">
                                            <span aria-hidden="true">×</span>
                                        </button>
                                        <input type="hidden" name="postId" value="<?= htmlspecialchars($content->postId()); ?>"/>
                                        <input type="hidden" name="contentId" value="<?= htmlspecialchars($content->id()); ?>"/>
                                    </div>
                                    <div class="modal-body">Cliquez sur "Valider" pour supprimer définitivement ce contenu</div>
                                    <div class="modal-footer">
                                        <button class="btn btn-secondary" type="button" data-dismiss="modal">Annuler</button>
                                        <a href="index.php?action=deleteContent&amp;id=<?= htmlspecialchars($content->postId()); ?>&amp;content=<?= htmlspecialchars($content->id()); ?>&amp;type=<?= htmlspecialchars($content->contentTypeId()); ?>" class="btn btn-primary-custom" >Valider</a>
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

                            <textarea class="form-control" name="<?= htmlspecialchars($content->id()); ?>" rows="5"><?= htmlspecialchars($content->content()); ?></textarea>
                            
                            <button type="submit" name="editContent" class="btn btn-outline-success btn-sm mx-2" title="Enregistrer les modifications" value="<?= htmlspecialchars($content->id()); ?>">Enregistrer</button>
                            
                            <a data-toggle="modal" data-target="#deleteContentModal<?= htmlspecialchars($content->id()); ?>" class="btn btn-outline-dark btn-sm mr-2" title="Supprimer">
                                <i class="fas fa-trash-alt"></i>
                            </a>

                        </div>

                        <!-- deleteContent Modal-->
                        <div class="modal fade" id="deleteContentModal<?= htmlspecialchars($content->id()); ?>" tabindex="-1" role="dialog" aria-labelledby="deleteContentLabel" aria-hidden="true">
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
                                        <a href="index.php?action=deleteContent&amp;id=<?= htmlspecialchars($content->postId()); ?>&amp;content=<?= htmlspecialchars($content->id()); ?>&amp;type=<?= htmlspecialchars($content->contentTypeId()); ?>" class="btn btn-primary-custom" >Valider</a>
                                    </div>
                                </div>
                            </div>
                        </div>

                        <?php
                    }
                }

                ?> 

                <div class="my-2">
                    <button type="submit" name="addParagraph" class="d-none d-sm-inline-block btn btn-sm btn-light shadow-sm"  value="add"><i class="fas fa-plus fa-sm mr-1"></i> Ajouter un paragraphe</button>
                    <a data-toggle="modal" data-target="#addPictureModal<?= $postId; ?>" class="d-none d-sm-inline-block btn btn-sm btn-light shadow-sm"><i class="fas fa-plus fa-sm mr-1"></i> Ajouter une image</a>

                    <!-- addPicture Modal-->
                    <div class="modal fade" id="addPictureModal<?= $postId; ?>" tabindex="-1" role="dialog" aria-labelledby="addPictureLabel" aria-hidden="true">
                        <div class="modal-dialog" role="document">
                            <div class="modal-content">
                                <div class="modal-header">
                                    <h5 class="modal-title" id="addPictureLabel">Choisissez votre nouvelle photo</h5>
                                    <button class="close" type="button" data-dismiss="modal" aria-label="Close">
                                        <span aria-hidden="true">×</span>
                                    </button>
                                </div>
                                <div class="modal-body">
                                    <p><em>Veuillez enregistrer les autres modifications avant de modifier ce contenu au risque que les informations soient perdues.</em></p>
                                    <input name="picture" type="file" />
                                </div>
                                <div class="modal-footer">
                                    <button class="btn btn-secondary" type="button" data-dismiss="modal">Annuler</button>
                                    <button type="submit" class="btn btn-primary-custom" name="addPicture" value="add">Envoyer</button>
                                </div>

                            </div>
                        </div>
                    </div>
                </div>

                <hr class="d-none d-lg-block ml-0">

                <h4 class="mb-4">Catégories</h4>
                <div class="">

                    <?php
                    if ($post->categories() != null)
                    {
                        foreach($post->categories() as $category)
                        {
                            ?>
                            <a href="index.php?action=deleteCategory&amp;id=<?= htmlspecialchars($category['postId']); ?>&amp;cat=<?= htmlspecialchars($category['id']); ?>" class="btn btn-outline-secondary mr-2"><?= htmlspecialchars($category['name']); ?> <i class="ml-1 fas fa-times"></i></a>
                            <?php 
                        }
                    }
                    else
                    {
                        echo '<p>Pas de catégorie associée à ce post. </p>';
                    }
                    
                    ?>

                </div>
                <div class="form-group form-inline mt-3">
                    <label for="new-category">Sélectionner / Ajouter une catégorie</label>
                    <input type="text" class="form-control mx-2" id="new-category" name="categoryName"/> 

                    <button type="submit" name="addCategory" class="d-none d-sm-inline-block btn btn-sm btn-light shadow-sm" value="add"><i class="fas fa-plus"></i></button>
                </div>

                <hr class="d-none d-lg-block ml-0">

            </div>         
        </div>  
    </form>  
    
    <?php $this->_content = ob_get_clean(); ?>

