<?php session_start();?>
<!doctype html>
<html lang="fr">
    <head>
        <meta charset="utf-8">
        <title>ReSoC - Flux</title>
        <meta name="author" content="Julien Falconnet">
        <link rel="stylesheet" href="style.css?v=<?php echo time(); ?>">
    </head>
    <body>
    <?php
// Vérification de l'autorisation
    if (!isset($_SESSION['connected_id'])) {
        // Si l'utilisateur n'est pas autorisé il est reconduit
        // sur le formulaire d'identification
        header("Location: login.php");
        die();
    }
?>
<?php include 'header.php'; ?>
        <div id="wrapper">
            <?php
            /**
             * Cette page est TRES similaire à wall.php.
             * Vous avez sensiblement à y faire la meme chose.
             * Il y a un seul point qui change c'est la requete sql.
             */
            /**
             * Etape 1: Le mur concerne un utilisateur en particulier
             */
            $userId = intval($_GET['user_id']);
            ?>
            <?php
            /**
             * Etape 2: se connecter à la base de donnée
             */
            include 'connexion_bdd.php';
            ?>

            <aside>
                <?php
                /**
                 * Etape 3: récupérer le nom de l'utilisateur
                 */
                $laQuestionEnSql = "SELECT * FROM `users` WHERE id= '$userId' ";
                $lesInformations = $mysqli->query($laQuestionEnSql);
                $user = $lesInformations->fetch_assoc();
                //@todo: afficher le résultat de la ligne ci dessous, remplacer XXX par l'alias et effacer la ligne ci-dessous
                //echo "<pre>" . print_r($user, 1) . "</pre>";
                ?>
                <?php
                switch ($userId) {
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
                <section>
                    <h3>Présentation</h3>
                    <p>Sur cette page vous trouverez tous les message des utilisatrices
                        auxquel est abonnée l'utilisatrice <a href="wall.php?user_id=<?php echo $userId ?>"><?php echo $user['alias']?></a>
                        (n° <?php echo $userId ?>)
                    </p>

                </section>
            </aside>
            <main>
                <?php
                /**
                 * Etape 3: récupérer tous les messages des abonnements
                 */
                $laQuestionEnSql = "
                    SELECT posts.content,
                    posts.created,
                    users.id  as author_id,
                    users.alias as author_name,
                    count(likes.id) as like_number,
                    GROUP_CONCAT(DISTINCT tags.label) AS taglist
                    FROM followers
                    JOIN users ON users.id=followers.followed_user_id
                    JOIN posts ON posts.user_id=users.id
                    LEFT JOIN posts_tags ON posts.id = posts_tags.post_id
                    LEFT JOIN tags       ON posts_tags.tag_id  = tags.id
                    LEFT JOIN likes      ON likes.post_id  = posts.id
                    WHERE followers.following_user_id='$userId'
                    GROUP BY posts.id
                    ORDER BY posts.created DESC
                    ";
                $lesInformations = $mysqli->query($laQuestionEnSql);
                if ( ! $lesInformations)
                {
                    echo("Échec de la requete : " . $mysqli->error);
                }

                /**
                 * Etape 4: @todo Parcourir les messsages et remplir correctement le HTML avec les bonnes valeurs php
                 * A vous de retrouver comment faire la boucle while de parcours...

                 */
                while ($post = $lesInformations->fetch_assoc())
                {

                //echo "<pre>" . print_r($post, 1) . "</pre>";
                ?>
                <article>
                    <h3>
                    <time datetime='<?php echo $post['created'] ?>' >
                            <?php
                            setlocale(LC_TIME, "fr_FR","French");
                            echo strftime("%d %B %G à %Hh%M", strtotime($post['created']));?>
                    </time>
                    </h3>
                    <address><a href="wall.php?user_id=<?php echo $post['author_id'] ?>"><?php echo $post['author_name'] ?></a></address>
                    <div>
                        <p><?php echo $post['content'] ?></p>
                    </div>
                    <footer>
                    <small id="smallwall"><?php echo $post['like_number'] ?> </small>
                    <small>
                              <form action="feed.php?user_id=<?php echo $_SESSION['connected_id']?>" method="post">
                                <input type="hidden" name="liker_id" value="<?php echo $_SESSION['connected_id']?>">
                                <input type="hidden" name="post_id" value= "<?php echo $post['id'] ?>">
                                <button style="border: none; background-color: white; text-decoration: none; display: inline-block; padding: 5px;" type="submit">
                                <img src="love.png" alt="" style="float: left; padding-right: 0.5em; width:5%;" /></button>

                              </form>
                            </small>
                        <?php
                            $array = explode(',', $post['taglist']);
                            foreach ($array as $valeur) {
                                echo "<a href=''>#$valeur, </a>";}
                            ?>
                    </footer>
                </article>
                <?php
                }
                // et de pas oublier de fermer ici vote while
                ?>


            </main>
        </div>
    </body>
</html>
