<!DOCTYPE html>
<html lang="fr">

<head>
  
  <meta charset="utf-8">
  <meta name="viewport" content="width=device-width, initial-scale=1, shrink-to-fit=no">
  <meta name="description" content="">
  <meta name="author" content="">
  <meta name="keywords" content="">
  <meta http-equiv="x-ua-compatible" content="ie=edge">

  <title><?= $this->_title ?></title>

  <!-- Bootstrap core CSS -->
  <link href="public/vendor/startbootstrap-grayscale-gh-pages/vendor/bootstrap/css/bootstrap.min.css" rel="stylesheet">

  <!-- CSS -->
  <link href="public/css/style.css" rel="stylesheet" />

  <!-- Custom fonts for this template -->
  <link href="public/vendor/startbootstrap-grayscale-gh-pages/vendor/fontawesome-free/css/all.min.css" rel="stylesheet">
  <link href="https://fonts.googleapis.com/css?family=Varela+Round" rel="stylesheet">
  <link href="https://fonts.googleapis.com/css?family=Nunito:200,200i,300,300i,400,400i,600,600i,700,700i,800,800i,900,900i" rel="stylesheet">

  <!-- Custom styles for this template -->
  <link href="public/vendor/startbootstrap-grayscale-gh-pages/css/grayscale.css" rel="stylesheet">

  <!-- Custom css -->
  <link rel="stylesheet" type="text/css" href="public/css/style.css">



</head>

<body id="page-top">

    <!-- Navigation -->
    <nav class="navbar navbar-expand-lg navbar-light fixed-top" id="mainNav">
        <div class="container">
            <a class="navbar-brand js-scroll-trigger" href="index.php"><i class="fas fa-home"></i></a>
            <button class="navbar-toggler navbar-toggler-right" type="button" data-toggle="collapse" data-target="#navbarResponsive" aria-controls="navbarResponsive" aria-expanded="false" aria-label="Toggle navigation">
             <i class="fas fa-bars"></i>
         </button>
         <div class="collapse navbar-collapse" id="navbarResponsive">
            <ul class="navbar-nav ml-auto">
                <li class="nav-item">
                  <a class="nav-link js-scroll-trigger" href="index.php#profile">A propos</a>
              </li>
              <li class="nav-item">
                  <a class="nav-link js-scroll-trigger" href="index.php?action=listPosts">Mon blog</a>
              </li>
              <li class="nav-item">
                  <a class="nav-link js-scroll-trigger" href="index.php#contact-form">Me contacter</a>
              </li>

              <?php

              if (isset($_SESSION['id']) && isset($_SESSION['pseudo']))
              {
                ?>
                <li class="nav-item dropdown no-arrow nav-avatar">
                    <a class="nav-link dropdown-toggle" href="#" id="userDropdown" role="button" data-toggle="dropdown" aria-haspopup="true" aria-expanded="false">
                        Bonjour, <?= htmlspecialchars($_SESSION['pseudo']); ?><img class="img-profile rounded-circle ml-2" src="<?= htmlspecialchars($_SESSION['avatar']); ?>" />
                        
                    </a>
                    <!-- Dropdown - User Information -->
                    <div class="dropdown-menu dropdown-menu-right shadow animated--grow-in" aria-labelledby="userDropdown">
                        <a class="dropdown-item" href="index.php?action=profileUser&id=<?= $_SESSION['id']; ?>">
                            <i class="fas fa-user fa-sm fa-fw mr-2 text-gray-400"></i>
                            Mon profil
                        </a>

                        <?php

                        if ($_SESSION['role'] == 1 || $_SESSION['role'] == 3)
                        {
                            ?>
                            <div class="dropdown-divider"></div>
                            <a class="dropdown-item" href="index.php?action=admin">
                                <i class="fas fa-user-cog fa-sm fa-fw mr-2 text-gray-400"></i>
                                Admin
                            </a>

                            <?php

                        }
                        ?>

                        <div class="dropdown-divider"></div>
                        <a class="dropdown-item" href="#" data-toggle="modal" data-target="#logoutModal">
                            <i class="fas fa-sign-out-alt fa-sm fa-fw mr-2 text-gray-400"></i>
                            Se déconnecter
                        </a>
                    </div>
                </li>

                <?php
            }
            else
            {
                ?>
                <li class="nav-item">
                    <a class="nav-link js-scroll-trigger" href="index.php?action=connexionView">Se connecter</a>
                </li>
                <?php
            }

            ?>
            
            
        </ul>
    </div>
</div>

</nav>

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

<!-- Header -->


<?php 
if (isset($this->_header)) 
{
    echo $this->_header; 
}
?>
  

<!-- Content -->

<?= $this->_content ?>



<!-- Footer -->
<footer class="bg-black small text-center text-white-50">
    <div class="text-center" id="footerLinks">

        <?php

        if (isset($_SESSION['role']) && ($_SESSION['role'] == 1  || $_SESSION['role'] == 3))
        {
            echo '<a href="index.php?action=admin">ADMIN</a> | ';
        }

        ?>

        <a href="index.php?action=legalView">MENTIONS LEGALES</a> | 
        <a href="index.php?action=confidentiality">POLITIQUE DE CONFIDENTIALITE</a>
        
    </div>
    <div class="text-center">
        Copyright &copy; Site réalisé par Charlotte SAURY dans le cadre de la formation OpenClassrooms parcours "Développeur d'applications"
    </div>
</footer>

<!-- Bootstrap core JavaScript -->
<script src="public/vendor/startbootstrap-grayscale-gh-pages/vendor/jquery/jquery.min.js"></script>
<script src="public/vendor/startbootstrap-grayscale-gh-pages/vendor/bootstrap/js/bootstrap.bundle.min.js"></script>

<!-- Plugin JavaScript -->
<script src="public/vendor/startbootstrap-grayscale-gh-pages/vendor/jquery-easing/jquery.easing.min.js"></script>

<!-- Custom scripts for this template -->
<script src="public/vendor/startbootstrap-grayscale-gh-pages/js/grayscale.min.js"></script>

</body>

</html>