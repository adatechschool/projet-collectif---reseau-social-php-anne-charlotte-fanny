<?php include 'session.php'; ?>
<?php
session_start();
// Vérification de l'autorisation
    if (!$_SESSION['connected_id']) {
        // Si l'utilisateur n'est pas autorisé il est reconduit
        // sur le formulaire d'identification
        header("Location: login.php");
        die();
    }
?>
<!doctype html>
<html lang="fr">
    <head>
        <meta charset="utf-8">
        <title>ReSoC - Mes abonnements</title>
        <meta name="author" content="Julien Falconnet">
        <style>
        <?php include "style.css" ?>
        </style>
    </head>
    <body>
    <?php include 'header.php'; ?>
        <div id="wrapper">
            <aside>
            
                <section>
                <?php
                switch ($_SESSION['connected_id']) {
                    case 11:
                        echo " <img src='fanny.png' alt='Portrait de l'utilisatrice'/>";
                        break;
                    case 12:
                        echo "<img src='anne.png' alt='Portrait de l'utilisatrice'/>";
                        break;
                    case 13:
                        echo "<img src='virginie.png' alt='Portrait de l'utilisatrice'/>";
                        break;
                    case 14:
                        echo "<img src='julie.png' alt='Portrait de l'utilisatrice'/>";
                        break;
                    case 17:
                        echo "<img src='marine.png' alt='Portrait de l'utilisatrice'/>";
                        break;
                    case 18:
                        echo "<img src='oihan.png' alt='Portrait de l'utilisatrice'/>";
                        break;
                    case 19:
                        echo "<img src='unknown.png' alt='Portrait de l'utilisatrice'/>";
                        break;
                }
                ?>
                    <h3>Présentation</h3>
                    <p>Sur cette page vous trouverez la liste des personnes dont
                    <a href="wall.php?user_id=<?php echo intval($_GET['user_id']) ?>">l'utilisatrice
                        n° <?php echo intval($_GET['user_id']) ?></a>
                        suit les messages
                    </p>

                </section>
            </aside>
            <main class='contacts'>
                <?php
                // Etape 1: récupérer l'id de l'utilisateur
                $userId = intval($_GET['user_id']);
                // Etape 2: se connecter à la base de donnée
                include 'connexion_bdd.php';
                // Etape 3: récupérer le nom de l'utilisateur
                $laQuestionEnSql = "
                    SELECT users.*
                    FROM followers
                    LEFT JOIN users ON users.id=followers.followed_user_id
                    WHERE followers.following_user_id='$userId'
                    GROUP BY users.id
                    ";
                $lesInformations = $mysqli->query($laQuestionEnSql);
                // Etape 4: à vous de jouer
                //@todo: faire la boucle while de parcours des abonnés et mettre les bonnes valeurs ci dessous
                while ($following = $lesInformations->fetch_assoc())
                {
                    //echo "<pre>" . print_r($followers, 1) . "</pre>";
               ?>
                <article>
                <?php
                switch ($following['id']) {
                    case 11:
                        echo " <img src='fanny.png' alt='Portrait de l'utilisatrice'/>";
                        break;
                    case 12:
                        echo "<img src='anne.png' alt='Portrait de l'utilisatrice'/>";
                        break;
                    case 13:
                        echo "<img src='virginie.png' alt='Portrait de l'utilisatrice'/>";
                        break;
                    case 14:
                        echo "<img src='julie.png' alt='Portrait de l'utilisatrice'/>";
                        break;
                    case 17:
                        echo "<img src='marine.png' alt='Portrait de l'utilisatrice'/>";
                        break;
                    case 18:
                        echo "<img src='oihan.png' alt='Portrait de l'utilisatrice'/>";
                        break;
                    case 19:
                        echo "<img src='unknown.png' alt='Portrait de l'utilisatrice'/>";
                        break;
                }
                ?>
                    <h3><a href="wall.php?user_id=<?php echo $following['id'] ?>"><?php echo $following['alias'] ?></a></h3>
                    <p>id:<?php echo $following['id'] ?></p>
                </article>
                <?php } ?>
            </main>
        </div>
    </body>
</html>
