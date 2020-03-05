
<!DOCTYPE html>
<html lang="en">

<head>

    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1, shrink-to-fit=no">
    <meta name="description" content="">
    <meta name="author" content="">
    <meta name="keywords" content="">
    <meta http-equiv="x-ua-compatible" content="ie=edge">

    <title><?= $this->_title ?></title>

    <!-- Custom fonts for this template-->
    <link href="public/vendor/startbootstrap-sb-admin-2/vendor/fontawesome-free/css/all.min.css" rel="stylesheet" type="text/css">
    <link href="https://fonts.googleapis.com/css?family=Nunito:200,200i,300,300i,400,400i,600,600i,700,700i,800,800i,900,900i" rel="stylesheet">

    <!-- Custom styles for this template-->
    <link href="public/vendor/startbootstrap-sb-admin-2/css/sb-admin-2.css" rel="stylesheet">

    <!-- Custom css -->
    <link rel="stylesheet" type="text/css" href="public/css/style.css">

    <!-- jquery -->
    <script src="https://code.jquery.com/jquery-1.11.3.min.js"></script>

</head>

<body id="page-top">

    <!-- Page Wrapper -->
    <div id="wrapper">

        <?php
        if ($session->get('role') && ($session->get('role') == 1 || $session->get('role') == 3))
        {
            ?>
            <!-- Sidebar -->
            <ul class="navbar-nav bg-gradient-dark sidebar sidebar-dark accordion" id="accordionSidebar">

                <!-- Sidebar - Brand -->
                <a class="sidebar-brand d-flex align-items-center justify-content-center" href="index.php?action=admin">
                    <div class="sidebar-brand-text mx-3"><?= htmlspecialchars($session->get('pseudo')); ?></div>
                </a>

                <!-- Divider -->
                <hr class="sidebar-divider my-0">

                <!-- Nav Item - Dashboard -->
                <li class="nav-item active">
                    <a class="nav-link" href="index.php?action=admin">
                        <i class="fas fa-fw fa-tachometer-alt"></i><span>Tableau de bord</span>
                    </a>
                </li>

                <!-- Divider -->
                <hr class="sidebar-divider">

                <!-- Heading -->
                <div class="sidebar-heading">
                    Admin
                </div>

                <!-- Nav Item - Pages Collapse Menu -->
                <li class="nav-item">
                    <a class="nav-link collapsed" href="#" data-toggle="collapse" data-target="#collapseTwo" aria-expanded="true" aria-controls="collapseTwo">
                        <i class="fas fa-newspaper"></i>
                        <span>Articles</span>
                    </a>
                    <div id="collapseTwo" class="collapse" aria-labelledby="headingTwo" data-parent="#accordionSidebar">
                        <div class="bg-white py-2 collapse-inner rounded">
                            <a class="collapse-item" href="index.php?action=adminPosts">Tous les articles</a>
                            <a class="collapse-item" href="index.php?action=adminNewPost"><i class="fas fa-plus mr-1"></i> Ajouter</a>
                        </div>
                    </div>
                </li>

                <!-- Nav Item - Comments -->
                <li class="nav-item">
                    <a class="nav-link" href="index.php?action=adminComments">
                        <i class="fas fa-comments"></i>
                        <span>Commentaires</span>
                    </a>
                </li>

                <!-- Nav Item - users -->
                <li class="nav-item">
                    <a class="nav-link" href="index.php?action=adminUsers">
                        <i class="fas fa-users"></i>
                        <span>Utilisateurs</span>
                    </a>
                </li>

                <?php
                if ($session->get('role') == 3)
                {
                    ?>

                <!-- Nav Item - Contacts -->
                <li class="nav-item">
                    <a class="nav-link" href="index.php?action=adminContacts">
                        <i class="fas fa-envelope"></i>
                        <span>Contacts</span>

                        <?php
                        if ($unreadContactsNb != 0)
                        {
                            echo '<span class="badge badge-light ml-2">' . $unreadContactsNb . '</span>';
                        }
                        ?>
                        
                    </a>
                </li>

                    <?php
                }
                ?>

                        <!-- Divider -->
                        <hr class="sidebar-divider">

                        <!-- Heading -->
                        <div class="sidebar-heading">
                            Profil
                        </div>

                        <!-- Profile nav card -->

                        <div class="adminProfileNavCard text-center mb-4">

                            <a href="index.php?action=profileUser&amp;id=<?= htmlspecialchars($session->get('id')); ?>">
                                <img class="my-4" src="<?= htmlspecialchars($session->get('avatar')); ?>" alt="User profil picture" />
                            </a>
                            <a href="index.php?action=editUser&id=<?= htmlspecialchars($session->get('id')); ?>" class="btn btn-primary-custom updateBtn px-1 px-md-3 py-md-3">
                              <i class="fas fa-user mr-1"></i> Modifier</a>

                          </div>

                          <!-- Sidebar Toggler (Sidebar) -->
                          <div class="text-center d-none d-md-inline">
                            <button class="rounded-circle border-0" id="sidebarToggle"></button>
                        </div>

                    </ul>
                    <!-- End of Sidebar -->
                    <?php
                }

                ?>



                <!-- Content Wrapper -->
                <div id="content-wrapper" class="d-flex flex-column">

                    <!-- Main Content -->
                    <div id="content">

                        <!-- Topbar -->
                        <nav class="navbar navbar-expand navbar-light bg-black topbar mb-4 static-top shadow">

                            <!-- Sidebar Toggle (Topbar) -->
                            <button id="sidebarToggleTop" class="btn btn-link d-md-none rounded-circle mr-3">
                                <i class="fa fa-bars"></i>
                            </button>

                            <!-- Topbar Navbar -->
                            <ul class="navbar-nav ml-auto">

                                <!-- Nav Item - User Information -->
                                <li class="nav-item dropdown no-arrow">
                                  <a class="nav-link dropdown-toggle" href="#" id="userDropdown" role="button" data-toggle="dropdown" aria-haspopup="true" aria-expanded="false">
                                    <span class="mr-3 d-none d-lg-inline text-primary-custom font-weight-bold">Bonjour, <?= htmlspecialchars($session->get('pseudo')); ?></span>
                                    <img class="img-profile rounded-circle" src="<?= htmlspecialchars($session->get('avatar')); ?>">
                                </a>
                                <!-- Dropdown - User Information -->
                                <div class="dropdown-menu dropdown-menu-right shadow animated--grow-in" aria-labelledby="userDropdown">
                                    <a class="dropdown-item" href="index.php?action=profileUser&id=<?= htmlspecialchars($session->get('id')); ?>">
                                      <i class="fas fa-user fa-sm fa-fw mr-2 text-gray-400"></i>
                                      Mon profil
                                  </a>
                                  <div class="dropdown-divider"></div>
                                  <a class="dropdown-item" href="index.php">
                                      <i class="fas fa-tachometer-alt fa-sm fa-fw mr-2 text-gray-400"></i>
                                      Le blog
                                  </a>
                                  <div class="dropdown-divider"></div>
                                  <a class="dropdown-item" data-toggle="modal" data-target="#logoutModal">
                                      <i class="fas fa-sign-out-alt fa-sm fa-fw mr-2 text-gray-400"></i>
                                      Se déconnecter
                                  </a>
                              </div>
                          </li>

                      </ul>

                  </nav>
                  <!-- End of Topbar -->

                  <!-- Begin Page Content -->
                  <div class="container-fluid">

                    <!-- Page Heading -->
                    <div class="mb-4">
                        <h1 class="h3 mb-0 text-gray-800">
                        <?php
                        if (isset($this->_contentTitle))
                        {
                            echo $this->_contentTitle . '</h1>';
                        }
                        else
                        {
                            echo $contentTitle . '</h1>';
                        }

                        if (isset($message))
                        {
                            echo '<div class="adminMessage text-center">' . $message . '</div>';
                        }

                        /*if (isset($errors))
                        {
                            ?>

                            <div class="adminMessage text-center">

                            <?php
                            foreach ($errors as $key => $value)
                            {
                                echo '<p>' . $value . '</p>';
                            }
                            ?>

                            </div>

                        <?php    
                        }*/
                        ?>
                    </div>

                    <!-- Content -->
                    
                    <?= $this->_content ?>
                    
                </div>

            </div>
            <!-- End of Main Content -->

            <!-- Footer -->
            <footer class="sticky-footer bg-white">
                <div class="container my-auto">
                    <div class="copyright text-center my-auto">
                        <span>Copyright &copy; Réalisé par Charlotte SAURY dans le cadre du parcours "Développeur d'applications PHP/Symfony" d'OpenClassrooms.</span>
                    </div>
                </div>
            </footer>
            <!-- End of Footer -->

        </div>
        <!-- End of Content Wrapper -->

    </div>
    <!-- End of Page Wrapper -->

    <!-- Scroll to Top Button-->
    <a class="scroll-to-top rounded" href="#page-top">
        <i class="fas fa-angle-up"></i>
    </a>

    <!-- Logout Modal-->
    <div class="modal fade" id="logoutModal" tabindex="-1" role="dialog" aria-labelledby="exampleModalLabel" aria-hidden="true">
        <div class="modal-dialog" role="document">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title" id="exampleModalLabel">Voulez-vous vraiment quitter cette page ?</h5>
                    <button class="close" type="button" data-dismiss="modal" aria-label="Close">
                        <span aria-hidden="true">×</span>
                    </button>
                </div>
                <div class="modal-body">Cliquez sur "Se déconnecter" si vous souhaitez vous déconnecter du tableau de bord</div>
                <div class="modal-footer">
                    <button class="btn btn-secondary" type="button" data-dismiss="modal">Annuler</button>
                    <a class="btn btn-primary-custom" href="index.php?action=deconnexion">Se déconnecter</a>
                </div>
            </div>
        </div>
    </div>

    <!-- Bootstrap core JavaScript-->
    <script src="public/vendor/startbootstrap-sb-admin-2/vendor/jquery/jquery.min.js"></script>
    <script src="public/vendor/startbootstrap-sb-admin-2/vendor/bootstrap/js/bootstrap.bundle.min.js"></script>

    <!-- Core plugin JavaScript-->
    <script src="public/vendor/startbootstrap-sb-admin-2/vendor/jquery-easing/jquery.easing.min.js"></script>

    <!-- Custom scripts for all pages-->
    <script src="public/vendor/startbootstrap-sb-admin-2/js/sb-admin-2.min.js"></script>

    <!-- Page level plugins -->
    <script src="public/vendor/startbootstrap-sb-admin-2/vendor/chart.js/Chart.min.js"></script>

    <!-- Page level custom scripts -->
    <script src="public/vendor/startbootstrap-sb-admin-2/js/demo/chart-area-demo.js"></script>
    <script src="public/vendor/startbootstrap-sb-admin-2/js/demo/chart-pie-demo.js"></script>

    <!-- Custom JS -->
    <!--<script type="text/javascript" src="public/js/customjs.js" rel="stylesheet" />"></script>-->


</body>

</html>
