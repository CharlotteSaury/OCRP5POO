<?php $this->_title = 'CV Charlotte SAURY, développeuse PHP'; ?>


<!-- $header definition -->

<?php ob_start(); ?>

<header class="masthead" id="home">
  <div class="container d-flex h-100 align-items-center">
    <div class="mx-auto text-center">
      <h1 class="mx-auto my-0 text-uppercase">Charlotte SAURY</h1>
      <h2 class="text-white-50 mx-auto mt-2 mb-5">Développeuse d'applications PHP</h2>
      <a href="#profile" class="btn btn-primary-custom js-scroll-trigger">En savoir plus</a>
    </div>
  </div>
</header>


<?php $this->_header = ob_get_clean(); ?>


<!-- $content definition -->

<?php ob_start(); ?>


<!-- Emphase Section -->

<section id="profile" class="emphase-section text-center">
  <div class="container">
    <div class="row">
      <div class="col-lg-8 mx-auto">
        <h2 class="text-white mb-4">A propos de moi</h2>
        <p class="text-white-50">" Rien ne sert de courir, il faut partir à point."</p>
        
      </div>
    </div>
  </div>
</section>

<!-- Profile Section -->
<section class="profile-section bg-light">
  <div class="container">

    <!-- Featured Project Row -->
    <div class="row align-items-center no-gutters mb-4 mb-lg-5">
      <div class="col-lg-5">
        <img class="img-fluid mb-3 mb-lg-0" src="public/images/photo.jpg" alt="">
      </div>
      <div class="col-lg-7">
        <div class="featured-text text-center text-lg-left">
          <h4>Charlotte SAURY</h4>
          <p class="text-black-50 mb-0">Née le 12 avril 1990</p>
          <p class="text-black-50 mb-0" id="about-me-text">Fusce egestas elit eget lorem. Aliquam lorem ante, dapibus in, viverra quis, feugiat a, tellus. Sed augue ipsum, egestas nec, vestibulum et, malesuada adipiscing, dui. Curabitur ullamcorper ultricies nisi. Nunc sed turpis.</br>
          Cras dapibus. Phasellus nec sem in justo pellentesque facilisis. Pellentesque libero tortor, tincidunt et, tincidunt eget, semper nec, quam. Aenean commodo ligula eget dolor.</br>
        Sed in libero ut nibh placerat accumsan. Quisque ut nisi. Sed cursus turpis vitae tortor. Sed consequat, leo eget bibendum sodales, augue velit cursus nunc, quis gravida magna mi a libero. Vestibulum turpis sem, aliquet eget, lobortis pellentesque, rutrum eu, nisl.</p>
      </div>
      <div class="featured-btn text-center text-lg-left">
        <a href="#contact-form" class="btn btn-primary-custom js-scroll-trigger">Me contacter</a>
        <a href="public/pdf/cv_saury.pdf" class="btn btn-primary-custom" id="btnCV" download="cv_saury.pdf">Télécharger mon CV</a>
      </div>
    </div>
  </div>

  <!-- Project One Row -->
  <div class="row justify-content-center no-gutters mb-5 mb-lg-0">
    <div class="col-lg-6">
      <img class="img-fluid" src="public/vendor/startbootstrap-grayscale-gh-pages/img/demo-image-01.jpg" alt="">
    </div>
    <div class="col-lg-6">
      <div class="bg-black text-center h-100 project">
        <div class="d-flex h-100">
          <div class="project-text w-100 my-auto text-center text-lg-left">
            <h4 class="text-white">Une formation dans la biologie...</h4>
            <p class="mb-0 text-white-50">De longues études et une expérience dans la recherche biomédicale m'ont apporté curiosité, autonomie et persévérance !</p>
            <hr class="d-none d-lg-block mb-0 ml-0">
          </div>
        </div>
      </div>
    </div>
  </div>

  <!-- Project Two Row -->
  <div class="row justify-content-center no-gutters">
    <div class="col-lg-6">
      <img class="img-fluid" src="public/vendor/startbootstrap-grayscale-gh-pages/img/demo-image-02.jpg" alt="">
    </div>
    <div class="col-lg-6 order-lg-first">
      <div class="bg-black text-center h-100 project">
        <div class="d-flex h-100">
          <div class="project-text w-100 my-auto text-center text-lg-right">
            <h4 class="text-white">Mais... un futur dans le web</h4>
            <p class="mb-0 text-white-50">C'est dans le domaine du développement et du web que je me projette à partir d'aujourdhui !</p>
            <hr class="d-none d-lg-block mb-0 mr-0">
          </div>
        </div>
      </div>
    </div>
  </div>

</div>
</section>

<!-- Contact form Section -->
<section id="contact-form" class="contact-form-section">
  <div class="container">
    <div class="row">
      <div class="col-md-10 col-lg-8 mx-auto text-center">

        <i class="far fa-paper-plane fa-2x mb-2 text-white"></i>
        <h2 class="text-white mb-5">Me contacter !</h2>

        <?php
        if (isset($message))
        {
          echo '<div class="adminMessage text-white-50 text-center">' . $message . '</div>';
        }
        ?>

        <form method="POST" action="index.php?action=contactForm#contact-form" class="form-inline d-flex flex-column">
          <input type="text" name="name" class="form-control flex-fill mr-0 mr-sm-2 mb-3 mb-sm-0" id="contactName" placeholder="Votre nom" required/>

          <?php
          if ($session->get('email'))
          {
           echo '<input type="email" name="email" class="form-control flex-fill mr-0 mr-sm-2 mb-3 mb-sm-0" id="contactEmail" value="' . htmlspecialchars($session->get('email')) . '" required/>'; 
         }
         else
         {
          echo '<input type="email" name="email" class="form-control flex-fill mr-0 mr-sm-2 mb-3 mb-sm-0" id="contactEmail" placeholder="Votre adresse email" required/>'; 
        }
        ?>

        
        <input type="text" name="subject" class="form-control flex-fill mr-0 mr-sm-2 mb-3 mb-sm-0" id="contactSubject" placeholder="Objet" required/>
        <textarea name="content" class="form-control flex-fill mr-0 mr-sm-2 mb-3 mb-sm-0" id="contactMessage" placeholder="Votre message" required></textarea>
        <button type="submit" class="btn btn-primary-custom mx-auto">Envoyer</button>
      </form>
    </div>
  </div>
</div>
</section>

<!-- Contact Section -->
<section class="contact-section bg-black">
  <div class="container">

    <div class="row">

      <div class="col-md-4 mb-3 mb-md-0">
        <div class="card py-4 h-100">
          <div class="card-body text-center">
            <i class="fas fa-map-marked-alt fa-2x text-primary mb-2"></i>
            <h4 class="text-uppercase m-1">Adresse</h4>
            <hr class="my-4">
            <div class="small text-black-50">8 rue du 28E R.A, 56000 Vannes</div>
          </div>
        </div>
      </div>

      <div class="col-md-4 mb-3 mb-md-0">
        <div class="card py-4 h-100">
          <div class="card-body text-center">
            <i class="fas fa-envelope fa-2x text-primary mb-2"></i>
            <h4 class="text-uppercase m-1">Email</h4>
            <hr class="my-4">
            <div class="small text-black-50">
              <a href="mailto:saury.charlotte@wanadoo.fr">saury.charlotte@wanadoo.fr</a>
            </div>
          </div>
        </div>
      </div>

      <div class="col-md-4 mb-3 mb-md-0">
        <div class="card py-4 h-100">
          <div class="card-body text-center">
            <i class="fas fa-mobile-alt fa-2x text-primary mb-2"></i>
            <h4 class="text-uppercase m-1">Tel</h4>
            <hr class="my-4">
            <div class="small text-black-50">
              <a href="tel:+33 6 48 08 35 40">+(00)33 6 48 08 35 40</a>
            </div>
          </div>
        </div>
      </div>
    </div>

    <div class="social d-flex justify-content-center">
      <a href="www.linkedin.com/in/charlotte-saury-654a834a" class="mx-2">
        <i class="fab fa-linkedin"></i>
      </a>
      <a href="https://www.facebook.com/charlotte.saury.1" class="mx-2">
        <i class="fab fa-facebook-f"></i>
      </a>
      <a href="https://github.com/CharlotteSaury" class="mx-2">
        <i class="fab fa-github"></i>
      </a>
    </div>

  </div>

</section>


<?php $this->_content = ob_get_clean(); ?>

