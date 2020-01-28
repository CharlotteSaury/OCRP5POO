<!DOCTYPE html>
<html lang="fr">

	<head>
		
		<meta charset="utf-8">
        <meta name="viewport" content="width=device-width, initial-scale=1, shrink-to-fit=no">
        <meta name="description" content="">
        <meta name="author" content="">
        <meta name="keywords" content="">
        <meta http-equiv="x-ua-compatible" content="ie=edge">

        <title><?= $title ?></title>

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
                <a class="navbar-brand js-scroll-trigger" href="index.php?action=home"><i class="fas fa-home"></i></a>
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
                        <li class="nav-item">
                            <a class="nav-link js-scroll-trigger" href="connexionView.php">Se connecter</a>
                        </li>
                	</ul>
              	</div>
            </div>
        </nav>


		<!-- Content -->

		<?= $content ?>

		<!-- Footer -->
	    <footer class="bg-black small text-center text-white-50">
	    	<div class="text-center" id="footerLinks">
	    		<a href="../backend/dashboardView.php">ADMIN</a> | 
	    		<a href="#">MENTIONS LEGALES</a> | 
	    		<a href="#">POLITIQUE DE CONFIDENTIALITE</a>
	    		
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