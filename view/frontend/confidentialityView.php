<?php $title = 'Politique de confidentialité'; ?>


<!-- $header definition -->

<?php ob_start(); ?>

<header class="post-view-head pb-5">
    <div class="container d-flex h-100 align-items-end">
        <div class="mx-auto text-center">
            <h1 class="mx-auto my-auto text-uppercase">Politique de confidentialité</h1>
        </div>
    </div>
</header>


<?php $header = ob_get_clean(); ?>


<!-- $content definition -->

<?php ob_start(); ?>


<section class="bg-light">
    <div class="post-section row container-fluid mx-0">
        <div class="blog-post col-lg-9 col-sm-12 mx-auto">

            <p>Charlotte SAURY est soucieuse de préserver la confidentialité de vos informations personnelles et attache une grande importance à la protection de la vie privée des utilisateurs de ses services.</p>

 
            <h4>Qui suis-je ?</h4>
            <p>Je suis une étudiante du parcours "Développeur d'application PHP/Symfony" de la plateforme de formation en ligne OpenClassrooms. L’adresse de mon blog professsionnel créé dans le cadre du 5e projet de cette formation est la suivante : http://www.saurycharlotte.fr.</p>

            <h4>Utilisation des données personnelles collectées</h4>

            <h5>Commentaires</h5>

            <p>Quand vous laissez un commentaire sur notre site web, les données inscrites dans le formulaire de commentaire sont collectés pour nous aider à la détection des commentaires indésirables.</p>

            <p>Après validation de votre commentaire, votre photo de profil sera visible publiquement à coté de votre commentaire.</p>

            <h5>Formulaires de contact</h5>

            <p>Si vous nous contactez via le formulaire de contact, il vous sera proposé d’enregistrer votre adresse de messagerie dans des cookies. C’est uniquement pour votre confort afin de ne pas avoir à saisir ces informations si vous souhaitez nous contacter plus tard. Ces cookies expirent au bout d’un an.</p>

            <h5>Cookies</h5>

            <p>Lorsque vous vous connecterez, nous mettrons en place un certain nombre de cookies pour enregistrer vos informations de connexion. Si vous cochez « Se souvenir de moi », votre cookie de connexion sera conservé pendant un an. Si vous vous déconnectez de votre compte, le cookie de connexion sera effacé.</p>

            <h5>Contenu embarqué depuis d’autres sites</h5>
            <p>Les articles de ce site peuvent inclure des contenus intégrés (par exemple des vidéos, images, articles…). Le contenu intégré depuis d’autres sites se comporte de la même manière que si le visiteur se rendait sur cet autre site.</p>

            <p>Ces sites web pourraient collecter des données sur vous, utiliser des cookies, embarquer des outils de suivis tiers, suivre vos interactions avec ces contenus embarqués si vous disposez d’un compte connecté sur leur site web.</p>

            <h4>Utilisation et transmission de vos données personnelles</h4>

            <h4>Durées de stockage de vos données</h4>
            <p>Si vous laissez un commentaire, le commentaire et ses métadonnées sont conservés indéfiniment. Cela permet de reconnaître et approuver automatiquement les commentaires suivants au lieu de les laisser dans la file de modération.</p>

            <p>Pour les utilisateurs et utilisatrices qui s’enregistrent, nous stockons également les données personnelles indiquées dans leur profil. Tous les utilisateurs et utilisatrices peuvent voir, modifier ou supprimer leurs informations personnelles à tout moment. Les gestionnaires du site peuvent aussi voir et modifier ces informations.</p>

            <h4>Les droits que vous avez sur vos données</h4>
            <p>Si vous avez un compte ou si vous avez laissé des commentaires sur le site, vous pouvez demander à recevoir un fichier contenant toutes les données personnelles que nous possédons à votre sujet, incluant celles que vous nous avez fournies. Vous pouvez également demander la suppression des données personnelles vous concernant. Cela ne prend pas en compte les données stockées à des fins administratives, légales ou pour des raisons de sécurité.</p>

            <h4>Transmission de vos données personnelles</h4>

            <h4>Informations de contact</h4>

            <ul>
                <li>Charlotte SAURY</li>
                <li>saury.charlotte@wanadoo.fr</li>
            </ul>
        </div>
    </div>
</section>

<?php $content = ob_get_clean(); ?>

<?php require('frontend_template.php'); ?>